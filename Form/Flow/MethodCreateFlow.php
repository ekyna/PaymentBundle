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
        return array(
            array(
                'label' => 'factory',
                'type'  => 'ekyna_payment_method_create_factory',
            ),
            array(
                'label' => 'config',
                'type'  => 'ekyna_payment_method',
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_payment_method_create';
    }
}
