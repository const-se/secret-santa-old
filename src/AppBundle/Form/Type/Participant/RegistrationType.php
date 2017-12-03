<?php

namespace AppBundle\Form\Type\Participant;

use AppBundle\Entity\Participant;
use AppBundle\Form\Type\AbstractEntityType;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class RegistrationType extends AbstractEntityType
{
    public function __construct()
    {
        parent::__construct(Participant::class, ['registration']);
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
            ->add('firstname', TextType::class, ['label' => 'Имя', 'attr' => ['placeholder' => 'Твое имя']])
            ->add('lastname', TextType::class, ['label' => 'Фамилия', 'attr' => ['placeholder' => 'Твоя фамилия']])
            ->add('email', EmailType::class, [
                'label' => 'Адрес электронной почты',
                'attr' => ['placeholder' => 'Твой e-mail'],
            ]);

        if ($options['recaptcha']) {
            $builder->add('recaptcha', EWZRecaptchaType::class, [
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

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(['recaptcha' => true, 'allow_extra_fields' => true]);
    }
}
