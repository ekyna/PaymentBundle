<?php

namespace Ekyna\Bundle\PaymentBundle\Payum\Action\Offline;

use Ekyna\Bundle\PaymentBundle\Payum\Action\AbstractCapturePaymentAction;
use Ekyna\Component\Sale\Payment\PaymentInterface;
use Payum\Core\Security\TokenInterface;
use Payum\Offline\Constants;

/**
 * Class CapturePaymentAction
 * @package Ekyna\Bundle\PaymentBundle\Payum\Action\Offline
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CapturePaymentAction extends AbstractCapturePaymentAction
{
    /**
     * {@inheritdoc}
     */
    protected function composeDetails(PaymentInterface $payment, TokenInterface $token)
    {
        $details = $payment->getDetails();

        if (!empty($details)) {
            return;
        }

        $payment->setDetails(array(
            Constants::FIELD_PAID => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsPayment($payment)
    {
        return $payment instanceof PaymentInterface;
    }
}
