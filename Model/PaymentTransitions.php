<?php

namespace Ekyna\Bundle\PaymentBundle\Model;

use Ekyna\Bundle\CoreBundle\Model\AbstractConstants;
use Ekyna\Component\Sale\Payment\PaymentTransitions as Transitions;

/**
 * Class PaymentTransitions
 * @package Ekyna\Bundle\PaymentBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class PaymentTransitions extends AbstractConstants
{
    static public function getConfig()
    {
        $prefix = 'ekyna_payment.payment.transition.';
        return array(
            Transitions::TRANSITION_CREATE    => array($prefix.Transitions::TRANSITION_CREATE,    'primary'),
            Transitions::TRANSITION_HANG      => array($prefix.Transitions::TRANSITION_HANG,      'warning'),
            Transitions::TRANSITION_CAPTURE   => array($prefix.Transitions::TRANSITION_CAPTURE,   'success'),
            Transitions::TRANSITION_CANCEL    => array($prefix.Transitions::TRANSITION_CANCEL,    'default'),
            Transitions::TRANSITION_FAIL      => array($prefix.Transitions::TRANSITION_FAIL,      'danger'),
            Transitions::TRANSITION_REFUND    => array($prefix.Transitions::TRANSITION_REFUND,    'primary'),
            Transitions::TRANSITION_AUTHORIZE => array($prefix.Transitions::TRANSITION_AUTHORIZE, 'success'),
            Transitions::TRANSITION_SUSPEND   => array($prefix.Transitions::TRANSITION_SUSPEND,   'warning'),
            Transitions::TRANSITION_EXPIRE    => array($prefix.Transitions::TRANSITION_EXPIRE,    'danger'),
            Transitions::TRANSITION_VOID      => array($prefix.Transitions::TRANSITION_VOID,      'default'),
        );
    }

    /**
     * Returns the theme for the given transition.
     *
     * @param string $transition
     * @return string
     */
    static public function getTheme($transition)
    {
        static::isValid($transition, true);

        return static::getConfig()[$transition][1];
    }
}
