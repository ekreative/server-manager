<?php

namespace AppBundle\Form;

use AppBundle\Entity\Client;
use AppBundle\Entity\Framework;
use AppBundle\Form\ModelTransformer\SitesFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SitesFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['placeholder' => 'Name'],
                'required' => false,
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => function (Client $client) {
                    return $client->getName();
                },
                'required' => false,
                'placeholder' => 'All',
            ])
            ->add('framework', EntityType::class, [
                'class' => Framework::class,
                'required' => false,
                'placeholder' => 'All',
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'required' => false,
                'choices' => [
                    'All' => 'All',
                    'Supported' => 'Supported',
                    'Unsupported' => 'UnSupported',
                ],
                'placeholder' => null,
            ])
        ;
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => "GET",
            'data_class' => SitesFilter::class
        ]);
    }
}
