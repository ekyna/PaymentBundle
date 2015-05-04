<?php

namespace Ekyna\Bundle\PaymentBundle\Payum\Action\Paypal;

use Ekyna\Bundle\PaymentBundle\Entity\TestPayment;
use Ekyna\Bundle\PaymentBundle\Payum\Action\AbstractCapturePaymentAction;
use Ekyna\Component\Sale\Payment\PaymentInterface;
use Payum\Core\Security\TokenInterface;

/**
 * Class CaptureTestPaymentUsingExpressCheckoutAction
 * @package Ekyna\Bundle\PaymentBundle\Payum\Action\Paypal
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CaptureTestPaymentUsingExpressCheckoutAction extends AbstractCapturePaymentAction
{
    /**
     * {@inheritdoc}
     */
    protected function composeDetails(PaymentInterface $payment, TokenInterface $token)
    {
        $details = $payment->getDetails();

        if (array_key_exists('PAYMENTREQUEST_0_INVNUM', $details)) {
            return;
        }

        $details['NOSHIPPING'] = 1;
        $details['LANDINGPAGE'] = 'Billing';
        $details['SOLUTIONTYPE'] = 'Sole';

        $details['PAYMENTREQUEST_0_INVNUM'] = uniqid().'-'.$payment->getId();
        $details['PAYMENTREQUEST_0_CURRENCYCODE'] = $payment->getCurrency();
        $details['PAYMENTREQUEST_0_AMT'] = round($payment->getAmount(), 2);
        $details['PAYMENTREQUEST_0_ITEMAMT'] = round($payment->getAmount(), 2);

        $payment->setDetails($details);
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsPayment($payment)
    {
        return $payment instanceof TestPayment;
    }
}
