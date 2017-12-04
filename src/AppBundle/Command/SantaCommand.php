<?php

namespace AppBundle\Command;

use AppBundle\Entity\Participant;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use LogicException;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class SantaCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setName('santa:send')
            ->setDescription('Secret Santa');
    }

    /**
     * @inheritdoc
     * @throws LogicException
     * @throws ServiceCircularReferenceException
     * @throws ServiceNotFoundException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->entityManager = $this
            ->getContainer()
            ->get('doctrine.orm.default_entity_manager');

        $participants = $this->entityManager
            ->getRepository('AppBundle:Participant')
            ->findAll();
        $participants = array_values($participants);

        if (!$this->listParticipants($participants)) {
            return 1;
        }

        do {
            if ($dative = $this->listDativeNames($participants)) {
                $this->setDativeNames($participants);
            }
        } while ($dative);

        $participants = $this->shuffleParticipants($participants);
        $this->saveRecipients($participants);

        if (!$this->checkRecipients($participants)) {
            return 1;
        }

        if (!$this->createMails($participants)) {
            return 1;
        }

        $this->output->writeln('Done!');

        return 0;
    }

    /**
     * @param Participant[] $participants
     * @return bool
     */
    private function checkParticipants(array $participants): bool
    {
        foreach ($participants as $key => $participant) {
            $nextParticipant = $participants[$key + 1] ?? $participants[0];

            if (($exceptParticipant = $participant->getExceptParticipant())
                && $exceptParticipant->getId() === $nextParticipant->getId()
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Participant[] $participants
     * @return bool
     */
    private function checkRecipients(array $participants): bool
    {
        $this->output->write('Проверяю корректность получателей: ');

        foreach ($participants as $participant) {
            if (!$recipient = $participant->getRecipient()) {
                $this->output->writeln('ошибка1!');

                return false;
            }

            $invalidIds = [
                $participant->getId(),
                $participant->getExceptParticipant()
                    ? $participant->getExceptParticipant()->getId()
                    : 0,
            ];

            if (in_array($recipient->getId(), $invalidIds, true)) {
                $this->output->writeln('ошибка2!');

                return false;
            }
        }

        $this->output->writeln('ok!');

        return true;
    }

    /**
     * @param Participant[] $participants
     * @return bool
     * @throws LogicException
     * @throws ServiceCircularReferenceException
     * @throws ServiceNotFoundException
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    private function createMails(array $participants): bool
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Сформировать письма? [y/N] ', false);

        if (!$helper->ask($this->input, $this->output, $question)) {
            return false;
        }

        $this->output->write('Формирование писем: ');

        foreach ($participants as $participant) {
            $mail = (new Swift_Message())
                ->setFrom('santaclaus@santa.const-se.ru')
                ->setTo($participant->getEmail())
                ->setSubject('Секретный Санта!')
                ->setBody(
                    $this
                        ->getContainer()
                        ->get('twig')
                        ->render('AppBundle:Mail:secret_santa.html.twig', [
                            'participant' => $participant,
                            'recipient' => $participant->getRecipient(),
                        ]),
                    'text/html'
                );
            $this->getContainer()->get('mailer')->send($mail);
            $this->output->write('.');
        }

        $this->output->writeln('');

        return true;
    }

    /**
     * @param Participant[] $participants
     * @return bool
     */
    private function listDativeNames(array $participants): bool
    {
        $this->output->writeln('Имена участников в дательном падеже:');

        foreach ($participants as $participant) {
            $this->output->writeln(
                '- ' . $participant->getFirstname() . ' ' . $participant->getLastname() . ': '
                . $participant->getDativeFirstname() . ' ' . $participant->getDativeLastname()
            );
        }

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Задать новые имена? [y/N] ', false);

        return $helper->ask($this->input, $this->output, $question);
    }

    /**
     * @param Participant[] $participants
     * @return bool
     */
    private function listParticipants(array $participants): bool
    {
        $this->output->writeln('Список участников:');

        foreach ($participants as $participant) {
            $this->output->writeln('- ' . $participant->getFirstname() . ' ' . $participant->getLastname());
        }

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Список корректен? [Y/n] ', true);

        return $helper->ask($this->input, $this->output, $question);
    }

    /**
     * @param Participant[] $participants
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     */
    private function saveRecipients(array $participants): void
    {
        $this->output->writeln('Сохраняю получателей подарка');

        foreach ($participants as $key => $participant) {
            $recipient = $participants[$key + 1] ?? $participants[0];
            $participant->setRecipient($recipient);
            $this->entityManager->persist($participant);
        }

        $this->entityManager->flush();
    }

    /**
     * @param Participant[] $participants
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     */
    private function setDativeNames(array $participants): void
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        foreach ($participants as $participant) {
            $this->output->writeln($participant->getFirstname() . ' ' . $participant->getLastname() . ':');

            $question = new Question(
                'Имя [' . $participant->getDativeFirstname() . ']: ',
                $participant->getDativeFirstname()
            );
            $participant->setDativeFirstname($helper->ask($this->input, $this->output, $question));

            $question = new Question(
                'Фамилия [' . $participant->getDativeLastname() . ']: ',
                $participant->getDativeLastname()
            );
            $participant->setDativeLastname($helper->ask($this->input, $this->output, $question));

            $this->entityManager->persist($participant);
        }

        $this->entityManager->flush();
    }

    /**
     * @param Participant[] $participants
     * @return array
     */
    private function shuffleParticipants(array $participants): array
    {
        $this->output->writeln('Перемешиваю участников');
        $correct = false;

        while (!$correct) {
            shuffle($participants);
            $correct = $this->checkParticipants($participants);
        }

        return $participants;
    }
}
