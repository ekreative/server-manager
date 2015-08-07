<?php

namespace AppBundle\Form;

use AppBundle\Entity\Login;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LoginType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('loginType', 'choice', [
                'choices' => [
                    Login::TYPE_SITE => 'Site',
                    Login::TYPE_SSH => 'SSH',
                    Login::TYPE_DB => 'Database'
                ],
                'expanded' => true,
                'attr' => [
                    'class' => 'btn-group',
                    'data-toggle' => 'buttons',
                    'data-login-type-toggle' => null
                ]
            ])
            ->add('username')
            ->add('password')
            ->add('sshKey', null, [
                'attr' => [
                    'help-block' => 'The text of a PEM file used for login',
                    'data-login-type' => Login::TYPE_SSH
                ]
            ])
            ->add('hostname', null, [
                'attr' => [
                    'help-block' => 'DNS name for this login',
                    'data-login-type' => implode(' ', [Login::TYPE_SSH, Login::TYPE_DB])
                ]
            ])
            ->add('port', null, [
                'attr' => [
                    'data-login-type' => implode(' ', [Login::TYPE_SSH, Login::TYPE_DB])
                ]
            ])
            ->add('url', null, [
                'attr' => [
                    'data-login-type' => Login::TYPE_SITE
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
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Login'
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_login';
    }
}
