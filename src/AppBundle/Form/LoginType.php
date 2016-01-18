<?php

namespace AppBundle\Form;

use AppBundle\Entity\Login;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    private $includeProxy;

    public function __construct($includeProxy = true)
    {
        $this->includeProxy = $includeProxy;
    }

    public function getAvailableTypes($referenceName)
    {
        $types = [Login::TYPE_NONE => Login::TYPE_NONE_READ];

        if ($referenceName == 'hostingLogin') {
            $types[Login::TYPE_SITE] = Login::TYPE_SITE_READ;
        }

        if ($referenceName == 'rootLogin') {
            $types[Login::TYPE_SSH] = Login::TYPE_SSH_READ;
            $types[Login::TYPE_DB] = Login::TYPE_DB_READ;
        }

        if ($referenceName == 'userLogin') {
            $types[Login::TYPE_SSH] = Login::TYPE_SSH_READ;
            $types[Login::TYPE_DB] = Login::TYPE_DB_READ;
        }

        if ($referenceName == 'hostingLogin') {
            $types[Login::TYPE_SSH] = Login::TYPE_SSH_READ;
        }

        if ($referenceName == 'managementLogin') {
            $types[Login::TYPE_SITE] = Login::TYPE_SITE_READ;
        }

        if ($referenceName == 'adminLogin') {
            $types[Login::TYPE_SITE] = Login::TYPE_SITE_READ;
        }

        if ($referenceName == 'databaseLogin') {
            $types[Login::TYPE_SITE] = Login::TYPE_SITE_READ;
            $types[Login::TYPE_SSH] = Login::TYPE_SSH_READ;
            $types[Login::TYPE_DB] = Login::TYPE_DB_READ;
        }

        if ($referenceName == 'proxyHost') {
            $types[Login::TYPE_SSH] = Login::TYPE_SSH_READ;
        }

        return $types;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) {
            $form = $event->getForm();
            $data = $event->getData();
            if ($data) {
                $form
                    ->add('loginType', 'choice', [
                        'choices' => $this->getAvailableTypes((string)$builder->getForm()->getPropertyPath()),
                        'expanded' => true,
                        'attr' => [
                            'class' => 'btn-group',
                            'data-toggle' => 'buttons',
                            'data-login-type-toggle' => null
                        ],
                        'data' => $data && $data->getLoginType() ? $data->getLoginType() : Login::TYPE_NONE,
                    ])
                    ->add('databaseName', null, [
                        'attr' => [
                            'data-login-type' => Login::TYPE_DB
                        ]
                    ])
                    ->add('username', null, [
                        'attr' => [
                            'data-login-type' => implode(' ', [Login::TYPE_SITE, Login::TYPE_SSH, Login::TYPE_DB])
                        ]
                    ])
                    ->add('password', null, [
                        'attr' => [
                            'data-login-type' => implode(' ', [Login::TYPE_SITE, Login::TYPE_SSH, Login::TYPE_DB])
                        ]
                    ])
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
                    ]);
                if ($this->includeProxy) {
                    $builder->add('proxyHost', new LoginType(false), [
                        'attr' => [
                            'data-login-type' => Login::TYPE_DB
                        ]
                    ]);
                }
            }
        });
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Login'
        ]);
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_login';
    }
}
