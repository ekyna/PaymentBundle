<?php

namespace Ekyna\Bundle\PaymentBundle\Payum\Action;

use Ekyna\Component\Sale\Payment\PaymentInterface;
use Payum\Core\Action\PaymentAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Generic;

/**
 * Class ExecuteSameRequestWithPaymentDetailsAction
 * @package Ekyna\Bundle\PaymentBundle\Payum\Action
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ExecuteSameRequestWithPaymentDetailsAction extends PaymentAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param $request Generic
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getModel();
        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        try {
            $request->setModel($details);

            $this->payment->execute($request);

            $payment->setDetails((array) $details);
        } catch (\Exception $e) {
            $payment->setDetails((array) $details);
            
            throw $e;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Generic &&
            $request->getModel() instanceof PaymentInterface
        ;
    }
}
