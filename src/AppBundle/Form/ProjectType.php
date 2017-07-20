<?php

namespace AppBundle\Form;

use AppBundle\Form\ModelTransformer\ProjectModelTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends TextType
{

    /**
     * @var ProjectModelTransformer
     */
    private $transformer;

    public function __construct(ProjectModelTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->transformer);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'attr' => [
                'help-block' => 'The Redmine project'
            ]
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
