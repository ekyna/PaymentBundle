<?php

namespace Ekyna\Bundle\PaymentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PaymentMethodType
 * @package Ekyna\Bundle\PaymentBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class PaymentMethodType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
            ->add('method', 'choice', array(
                'choices' => array(
                    'offline' => 'Offline',
                    'paypal_express_checkout' => 'Paypal Express Checkout',
                ),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_payment_method';
    }
}
