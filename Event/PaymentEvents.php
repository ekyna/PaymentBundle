<?php

namespace Ekyna\Bundle\PaymentBundle\Event;

/**
 * Class PaymentEvents
 * @package Ekyna\Bundle\PaymentBundle\Event
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
final class PaymentEvents
{
    const PREPARE      = 'ekyna_payment.payment.prepare';
    const STATE_CHANGE = 'ekyna_payment.payment.state_change';
    const NOTIFY       = 'ekyna_payment.payment.notify';
    const DONE         = 'ekyna_payment.payment.done';
}
