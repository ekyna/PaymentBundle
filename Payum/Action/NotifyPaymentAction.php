<?php

namespace Ekyna\Bundle\PaymentBundle\Payum\Action;

use Ekyna\Bundle\PaymentBundle\Payum\Request\GetStatus;
use Ekyna\Component\Sale\Payment\PaymentInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Notify;
use Payum\Core\Request\Sync;

/**
 * Class NotifyPaymentAction
 * @package Ekyna\Bundle\PaymentBundle\Payum\Action
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class NotifyPaymentAction extends AbstractPaymentStateAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param $request Notify
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var $payment PaymentInterface */
        $payment = $request->getModel();

        $this->payment->execute(new Sync($payment));

        $status = new GetStatus($payment);
        $this->payment->execute($status);

        $nextState = $status->getValue();

        $this->updatePaymentState($payment, $nextState);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Notify &&
            $request->getToken() &&
            $request->getModel() instanceof PaymentInterface
        ;
    }
}
