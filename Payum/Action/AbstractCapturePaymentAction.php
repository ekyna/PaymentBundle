<?php

namespace Ekyna\Bundle\PaymentBundle\Payum\Action;

use Ekyna\Component\Sale\Payment\PaymentInterface;
use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Capture;
use Payum\Core\Security\TokenInterface;

/**
 * Class AbstractCapturePaymentAction
 * @package Ekyna\Bundle\PaymentBundle\Payum\Action
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractCapturePaymentAction extends GatewayAwareAction
{
    /**
     * {@inheritdoc}
     *
     * @param Capture $request
     */
    public function execute($request)
    {
        //echo 'Abstract Capture<br>';

        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getModel();

        $this->composeDetails($payment, $request->getToken());

        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        try {
            $request->setModel($details);

            $this->gateway->execute($request);

            $payment->setDetails($details);
        } catch (\Exception $e) {
            $payment->setDetails($details);

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $this->supportsPayment($request->getModel())
        ;
    }

    /**
     * Composes the payment details.
     *
     * @param PaymentInterface $payment
     * @param TokenInterface   $token
     */
    abstract protected function composeDetails(PaymentInterface $payment, TokenInterface $token);

    /**
     * Returns whether the payment is supported or not.
     *
     * @param mixed $payment
     * @return bool
     */
    abstract protected function supportsPayment($payment);
}
