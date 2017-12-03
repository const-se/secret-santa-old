<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Participant;
use AppBundle\Form\Type\Participant\RegistrationType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class GeneralController extends Controller
{
    /**
     * @Config\Route("/", name = "general_index")
     * @return Response
     */
    public function index()
    {
        return $this->render('AppBundle:General:index.html.twig');
    }

    /**
     * @Config\Route("/registration", name = "general_registration")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return RedirectResponse|Response
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     */
    public function registration(Request $request, SerializerInterface $serializer): Response
    {
        $participant = new Participant();
        $registrationForm = $this
            ->createForm(RegistrationType::class, $participant)
            ->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            /** @var EntityManager $entityManager */
            $entityManager = $this->get('doctrine.orm.default_entity_manager');
            $entityManager->persist($participant);
            $entityManager->flush();

            // TODO notification

            return $this->redirectToRoute('general_welcome', ['participant' => $participant->getId()]);
        }

        return $this->render('AppBundle:General:registration.html.twig', [
            'registration_form' => $registrationForm->createView(),
            'participant' => $serializer->serialize($participant, 'json', ['groups' => ['registration']]),
        ]);
    }

    /**
     * @Config\Route("/welcome/{participant}", name = "general_welcome", requirements = {"participant": "^\d+$"})
     * @param Participant $participant
     * @return Response
     */
    public function welcome(Participant $participant): Response
    {
        return $this->render('AppBundle:General:welcome.html.twig');
    }
}
