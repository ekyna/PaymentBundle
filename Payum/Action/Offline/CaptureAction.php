<?php

namespace Ekyna\Bundle\PaymentBundle\Payum\Action\Offline;

use Ekyna\Bundle\PaymentBundle\Payum\Action\AbstractCapturePaymentAction;
use Ekyna\Component\Sale\Payment\PaymentInterface;
use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Capture;
use Payum\Core\Security\TokenInterface;
use Payum\Offline\Constants;

/**
 * Class CaptureAction
 * @package Ekyna\Bundle\PaymentBundle\Payum\Action\Offline
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CaptureAction extends GatewayAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param $request Capture
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $payment = $request->getModel();

        $details = $payment->getDetails();

        if (array_key_exists(Constants::FIELD_PAID, $details)) {
            return;
        }

        $details[Constants::FIELD_PAID] = false;

        $payment->setDetails($details);

        return;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof PaymentInterface
        ;
    }
}
