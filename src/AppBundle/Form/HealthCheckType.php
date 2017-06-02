<?php

namespace AppBundle\Form;

use AppBundle\Entity\HealthCheck;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HealthCheckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('url', 'url', [
            'attr' => [
                'placeholder' => 'http://domain.com/healthcheck'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => HealthCheck::class
        ]);
    }
}
