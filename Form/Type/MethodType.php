<?php

namespace Ekyna\Bundle\PaymentBundle\Form\Type;

use Ekyna\Bundle\AdminBundle\Form\Type\ResourceFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MethodType
 * @package Ekyna\Bundle\PaymentBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MethodType extends ResourceFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
            ->remove('factoryName')
            ->add('factoryName', 'payum_payment_factories_choice', array(
                'disabled' => true,
            ))
            ->add('enabled', 'checkbox', array(
                'label' => 'ekyna_core.field.enabled',
                'required' => false,
                'attr' => array(
                    'align_with_widget' => true,
                ),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'payum_payment_config';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_payment_method';
    }
}
