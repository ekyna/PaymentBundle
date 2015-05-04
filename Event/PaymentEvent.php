<?php

namespace Ekyna\Bundle\PaymentBundle\Event;

use Ekyna\Bundle\AdminBundle\Event\ResourceEvent;
use Ekyna\Component\Sale\Payment\PaymentInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PaymentEvent
 * @package Ekyna\Bundle\PaymentBundle\Event
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class PaymentEvent extends ResourceEvent
{
    /**
     * @var PaymentInterface
     */
    private $payment;

    /**
     * @var Response
     */
    private $response;


    /**
     * Constructor.
     * 
     * @param PaymentInterface $payment
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

    /**
     * Returns the response.
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Sets the response.
     *
     * @param Response $response
     * @return PaymentEvent
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
        return $this;
    }
}
