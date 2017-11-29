<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractEntityType extends AbstractType
{
    /**
     * @var string
     */
    protected $dataClass;

    /**
     * @var array
     */
    protected $validationGroups;

    /**
     * @param string $dataClass
     * @param array $validationGroups
     */
    public function __construct(string $dataClass, array $validationGroups = [])
    {
        $this->dataClass = $dataClass;
        $this->validationGroups = $validationGroups;
    }

    /**
     * @inheritdoc
     * @throws AccessException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(['data_class' => $this->dataClass, 'validation_groups' => $this->validationGroups]);
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
