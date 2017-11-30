<?php

namespace AppBundle\Form\Type\User;

use AppBundle\Entity\User;
use AppBundle\Form\Type\AbstractEntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractEntityType
{
    public function __construct()
    {
        parent::__construct(User::class, ['login']);
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('username', TextType::class, ['label' => 'Логин'])
            ->add('password', PasswordType::class, ['label' => 'Пароль'])
            ->add('rememberMe', HiddenType::class, ['data' => true]);
    }
}
