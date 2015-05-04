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
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
            ->add('method', 'ekyna_payment_method_choice')
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
