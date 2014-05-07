<?php

namespace Ekyna\Bundle\PaymentBundle\Event;

/**
 * PaymentEvents.
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
final class PaymentEvents
{
    const PRE_STATE_CHANGE = 'ekyna_payment.pre_state_updated';
    const POST_STATE_CHANGE = 'ekyna_payment.post_state_updated';
}
