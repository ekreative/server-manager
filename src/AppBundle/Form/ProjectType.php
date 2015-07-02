<?php

namespace AppBundle\Form;

use AppBundle\Form\ModelTransformer\ProjectModelTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class ProjectType extends TextType
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ProjectModelTransformer
     */
    private $transformer;

    public function __construct(RouterInterface $router, ProjectModelTransformer $transformer)
    {
        $this->router = $router;
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
                'typeahead' => $this->router->generate('project_typeahead')
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'project';
    }
}
