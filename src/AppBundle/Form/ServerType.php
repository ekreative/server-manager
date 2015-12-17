<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ip')
            ->add('os')
            ->add('hostedOn')
            ->add('autoUpdates', 'checkbox', [
                'required' => false,
                'attr' => [
                    'help-block' => 'Did you enable <a href="https://help.ubuntu.com/community/AutomaticSecurityUpdates">automatic updates</a>?. You should also set <code>Unattended-Upgrade::Remove-Unused-Dependencies</code> to <code>true</code> and possibly enable <code>"${distro_id} ${distro_codename}-updates";</code>'
                ]
            ])
            ->add('ntp', 'checkbox', [
                'required' => false,
                'attr' => [
                    'help-block' => 'Did you install NTP (<kbd>apt-get install ntp</kbd>)? If this is an OpsWorks server it is installed by default'
                ]
            ])
            ->add('hostingLogin', new LoginType())
            ->add('rootLogin', new LoginType())
            ->add('userLogin', new LoginType());
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Server'
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_server';
    }
}
