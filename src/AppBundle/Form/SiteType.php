<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;

class SiteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project', 'project')
            ->add('name', null, [
                'attr' => [
                    'help-block' => 'A name for the server'
                ]
            ])
            ->add('live', 'checkbox', [
                'required' => false,
                'attr' => [
                    'help-block' => 'Is this a live server or a test server?'
                ]
            ])
            ->add('framework', null, [
                'required' => true
            ])
            ->add('frameworkVersion', null, [
                'attr' => [
                    'help-block' => 'The version of framework used in the project (must be semvar major.minor.patch)'
                ]
            ])
            ->add('adminLogin', new LoginType(), [
                'attr' => [
                    'help-block' => 'Site admin login details'
                ]
            ])
            ->add('databaseLogin', new LoginType(), [
                'attr' => [
                    'help-block' => 'Login to the database for the site'
                ]
            ])
            ->add('servers', 'collection', [
                'allow_add' => true,
                'allow_delete' => true,
                'type' => new ServerType(),
                'by_reference' => false,
                'attr' => [
                    'help-block' => 'Servers associated with this site'
                ]
            ])
            ->add('domains', 'collection', [
                'allow_add' => true,
                'allow_delete' => true,
                'type' => new DomainType(),
                'by_reference' => false,
                'attr' => [
                    'help-block' => 'Domain names associated with this site'
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
            'data_class' => 'AppBundle\Entity\Site'
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_site';
    }
}
