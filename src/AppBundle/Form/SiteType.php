<?php

namespace AppBundle\Form;

use AppBundle\Entity\Framework;
use AppBundle\Entity\Site;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('project', ProjectType::class, [
                'required'=>true
            ])
            ->add('name', null, [
                'attr' => [
                    'help-block' => 'A name for the site'
                ]
            ])
            ->add('framework', null, [
                'required' => true,
                'choice_attr' => function($framework, $key, $index) {
                    /** @var Framework $framework */
                    return ['data-framework-version' => $framework->getCurrentVersion()];
                },
            ])
            ->add('frameworkVersion', null, [
                'attr' => [
                    'help-block' => 'The version of framework used in the project (must be semvar major.minor.patch)',
                    'readonly' => true
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'required' => false,
                'choices' => [
                    'Supported' => Site::STATUS_SUPPORTED,
                    'UnSupported' => Site::STATUS_UNSUPPORTED,
                ],
                'placeholder' => 'Choose a status.',
            ])

            ->add('adminLogin', LoginType::class, [
                'attr' => [
                    'help-block' => 'Site admin login details'
                ]
            ])
            ->add('databaseLogin', LoginType::class, [
                'attr' => [
                    'help-block' => 'Login to the database for the site'
                ]
            ])
            ->add('servers', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => ServerType::class,
                'by_reference' => false,
                'attr' => [
                    'help-block' => 'Servers credentials associated with this site'
                ]
            ])
            ->add('domains', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => DomainType::class,
                'by_reference' => false,
                'attr' => [
                    'help-block' => 'Domain names credentials associated with this site'
                ]
            ])
            ->add('healthChecks', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => HealthCheckType::class,
                'by_reference' => false,
                'attr' => [
                    'help-block' => 'Health checks associated with this site'
                ]
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
            'data_class' => Site::class
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_site';
    }
}
