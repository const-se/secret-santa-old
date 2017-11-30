<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use RuntimeException;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AdminFixture extends Fixture
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @inheritdoc
     * @throws RuntimeException
     * @throws ServiceCircularReferenceException
     * @throws ServiceNotFoundException
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $encoder = $this->encoderFactory->getEncoder($user);
        $salt = sha1(uniqid('', true));
        $user
            ->setUsername('admin')
            ->setPassword($encoder->encodePassword('835659', $salt))
            ->setSalt($salt);
        $manager->persist($user);
        $manager->flush();
    }
}
