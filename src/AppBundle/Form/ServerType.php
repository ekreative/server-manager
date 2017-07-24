<?php

namespace AppBundle\Form;

use AppBundle\Entity\Server;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('live', ChoiceType::class, [
                'required' => true,
                'choices'=>[
                    'Yes'=> 1,
                    'No' => 0
                ],
                'attr' => [
                    'help-block' => 'Is this a live server or a test server?'
                ]
            ])
            ->add('ip')
            ->add('os')
            ->add('autoUpdates', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'help-block' => 'Did you enable <a href="https://help.ubuntu.com/community/AutomaticSecurityUpdates">automatic updates</a>?. You should also set <code>Unattended-Upgrade::Remove-Unused-Dependencies</code> to <code>true</code> and possibly enable <code>"${distro_id} ${distro_codename}-updates";</code>'
                ]
            ])
            ->add('ntp', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'help-block' => 'Did you install NTP (<kbd>apt-get install ntp</kbd>)? If this is an OpsWorks server it is installed by default'
                ]
            ])
            ->add('hosting')
            ->add('hostingLogin', LoginType::class)
            ->add('rootLogin', LoginType::class)
            ->add('userLogin', LoginType::class);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Server::class
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_server';
    }
}
