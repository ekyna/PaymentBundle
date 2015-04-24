<?php

namespace Ekyna\Bundle\PaymentBundle\Payum\Action\Paypal;

use Doctrine\Common\Persistence\ObjectManager;
use Ekyna\Bundle\PaymentBundle\Payum\Action\AbstractPaymentStateAwareAction;
use Ekyna\Bundle\PaymentBundle\Payum\Request\GetPaymentStatus;
use Ekyna\Component\Sale\Payment\PaymentInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Request\Notify;
use Payum\Core\Request\Sync;
use SM\Factory\FactoryInterface;

/**
 * Class NotifyPaymentAction
 * @package Ekyna\Bundle\PaymentBundle\Payum\Action\Paypal
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class NotifyPaymentAction extends AbstractPaymentStateAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param $request GetStatusInterface
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var $payment PaymentInterface */
        $payment = $request->getModel();

        $this->payment->execute(new Sync($payment));

        $status = new GetPaymentStatus($payment);
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
