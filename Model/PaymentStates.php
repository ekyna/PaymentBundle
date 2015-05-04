<?php

namespace Ekyna\Bundle\PaymentBundle\Model;

use Ekyna\Component\Sale\Payment\PaymentStates as States;
use Ekyna\Bundle\CoreBundle\Model\AbstractConstants;

/**
 * Class PaymentStates
 * @package Ekyna\Bundle\PaymentBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
final class PaymentStates extends AbstractConstants
{
    /**
     * {@inheritdoc}
     */
    static public function getConfig()
    {
        $prefix = 'ekyna_payment.payment.state.';
        $suffix = '.label';
        return array(
            States::STATE_NEW        => array($prefix.States::STATE_NEW.$suffix,        'primary'),
            States::STATE_PENDING    => array($prefix.States::STATE_PENDING.$suffix,    'warning'),
            States::STATE_PROCESSING => array($prefix.States::STATE_PROCESSING.$suffix, 'warning'),
            States::STATE_CANCELLED  => array($prefix.States::STATE_CANCELLED.$suffix,  'default'),
            States::STATE_FAILED     => array($prefix.States::STATE_FAILED.$suffix,     'danger'),
            States::STATE_AUTHORIZED => array($prefix.States::STATE_AUTHORIZED.$suffix, 'success'),
            States::STATE_COMPLETED  => array($prefix.States::STATE_COMPLETED.$suffix,  'success'),
            States::STATE_REFUNDED   => array($prefix.States::STATE_REFUNDED.$suffix,   'primary'),
            States::STATE_UNKNOWN    => array($prefix.States::STATE_UNKNOWN.$suffix,    'default'),
        );
    }

    /**
     * Returns the theme for the given state.
     *
     * @param string $state
     * @return string
     */
    static public function getTheme($state)
    {
        static::isValid($state, true);

        return static::getConfig()[$state][1];
    }
}
