<?php

namespace AppBundle\Form;

use AppBundle\Form\ModelTransformer\UserModelTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends TextType
{

    /**
     * @var UserModelTransformer
     */
    private $transformer;

    public function __construct(UserModelTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->transformer);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}

