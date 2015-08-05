<?php

namespace Ekyna\Bundle\PaymentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PaymentType
 * @package Ekyna\Bundle\PaymentBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class PaymentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $amountType = $options['admin_mode'] ? 'number' : 'bs_static';

        $builder
            ->add('amount', $amountType, array(
                'label' => 'ekyna_payment.payment.field.amount',
            ))
            ->add('method', 'ekyna_payment_method_choice', array(
                'label' => 'ekyna_payment.payment.field.method',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_payment_payment';
    }
}
