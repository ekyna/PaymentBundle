<?php

namespace Ekyna\Bundle\PaymentBundle\Form\Flow;

use Craue\FormFlowBundle\Form\FormFlow;

/**
 * Class MethodCreateFlow
 * @package Ekyna\Bundle\PaymentBundle\Form\Flow
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MethodCreateFlow extends FormFlow
{
    /**
     * {@inheritdoc}
     */
    protected function loadStepsConfig()
    {
        return [
            [
                'label' => 'factory',
                'type'  => 'ekyna_payment_method_create_factory',
            ],
            [
                'label' => 'config',
                'type'  => 'ekyna_payment_method',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_payment_method_create';
    }
}
