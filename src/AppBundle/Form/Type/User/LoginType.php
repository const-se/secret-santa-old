<?php

namespace AppBundle\Form\Type\User;

use AppBundle\Entity\User;
use AppBundle\Form\Type\AbstractEntityType;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class LoginType extends AbstractEntityType
{
    public function __construct()
    {
        parent::__construct(User::class, ['login']);
    }

    /**
     * @inheritdoc
     * @throws ConstraintDefinitionException
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('username', TextType::class, ['label' => 'Логин'])
            ->add('password', PasswordType::class, ['label' => 'Пароль'])
            ->add('rememberMe', HiddenType::class, ['mapped' => false, 'data' => true])
            ->add('recaptcha', EWZRecaptchaType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'groups' => $this->validationGroups,
                        'message' => 'Пожалуйста, подтвердите, что Вы не робот',
                    ]),
                ],
            ]);
    }
}
