<?php

namespace AppBundle\Form;

use AppBundle\Entity\Client;
use AppBundle\Entity\Framework;
use AppBundle\Entity\Project;
use AppBundle\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                'required' => true
            ])
            ->add('name', null, [
                'attr' => [
                    'help-block' => 'A name for the site'
                ]
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_attr' => function (Client $client) {
                    return ['data-projects' => implode(array_map(function (Project $project) {
                        return $project->getId();
                    }, $client->getProjects()->toArray()))];
                },
                'choice_label' => function (Client $client) {
                    return $client->getName();
                },
                'required' => false,
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
                'placeholder' => 'Add New'
            ])
            ->add('newClient', ClientType::class, [
                'required' => false,
                'mapped' => false,
                'property_path' => 'client',
                'label' => false,
                'attr' => [
                    'style' => 'display:none'
                ],
            ])
            ->add('developer', UserType::class, [
                'required' => false,
                'attr' => ['disabled' => 'disabled'],
                'placeholder' => 'Choose main developer'
            ])
            ->add('responsibleManager', UserType::class, [
                'label' => 'Responsible Manager',
                'required' => false,
                'attr' => ['disabled' => 'disabled'],
                'placeholder' => 'Choose Responsible manager'
            ])
            ->add('sla', ChoiceType::class, [
                'choices' => [
                    'Standart' => 0,
                    'Advanced' => 1,
                ],
                'label' => 'SLA plan',
            ])
            ->add('framework', EntityType::class, [
                'required' => true,
                 'class' => Framework::class,
                 'choice_attr' => function ($framework, $key, $index) {
                     /** @var Framework $framework */
                     return ['data-framework-version' => $framework->getCurrentVersion()];
                 },
            ])
            ->add('frameworkVersion', TextType::class, [
                'attr' => [
                    'readonly' => true,
                    'help-block' => 'The version of framework used in the project (must be semvar major.minor.patch)'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'required' => false,
                'choices' => [
                    'Supported' => Site::STATUS_SUPPORTED,
                    'UnSupported' => Site::STATUS_UNSUPPORTED,
                ],
                'placeholder' => null,
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
            ->add('endDate', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
            ])
            ->add('notes', TextareaType::class, [
                'required' => false
            ]);
        $builder->get('developer')->resetViewTransformers();
        $builder->get('responsibleManager')->resetViewTransformers();
        $builder->get('project')->resetViewTransformers();
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Site::class,
            'clients' => []
        ]);
    }
}
