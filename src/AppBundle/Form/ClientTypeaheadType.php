<?php

namespace AppBundle\Form;

use AppBundle\Form\ModelTransformer\ClientModelTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class ClientTypeaheadType extends TextType
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ClientModelTransformer
     */
    private $transformer;

    public function __construct(RouterInterface $router, ClientModelTransformer $transformer)
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
                'typeahead' => $this->router->generate('client_typeahead'),
                'help-block' => 'Client`s Full Name'
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'client';
    }
}
