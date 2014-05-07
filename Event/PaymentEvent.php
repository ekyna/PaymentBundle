<?php

namespace Ekyna\Bundle\PaymentBundle\Event;

use Ekyna\Component\Sale\Payment\PaymentInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * PaymentEvent.
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class PaymentEvent extends Event
{
    /**
     * @var \Ekyna\Component\Sale\Payment\PaymentInterface
     */
    private $payment;

    /**
     * Constructor.
     * 
     * @param \Ekyna\Component\Sale\Payment\PaymentInterface $payment
     */
    public function __construct(PaymentInterface $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Returns payment.
     * 
     * @return \Ekyna\Component\Sale\Payment\PaymentInterface
     */
    public function getPayment()
    {
        return $this->payment;
    }
}
