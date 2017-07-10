<?php

namespace AppBundle\Form;

use AppBundle\Entity\Framework;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('name', null, [
                'attr' => ['placeholder' => 'Name'],
                'required' => false,
            ])
            ->add('framework', 'entity', [
                'class' => Framework::class,
                'required' => false,
                'empty_value' => 'All',
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'required' => false,
                'choices' => [
                    'All' => 'All',
                    'Supported' => 'Supported',
                    'Unsupported' => 'UnSupported',
                ],
                'empty_value' => null,
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
            'data_class' => 'AppBundle\Form\ModelTransformer\SitesFilter'
        ]);
    }
}
