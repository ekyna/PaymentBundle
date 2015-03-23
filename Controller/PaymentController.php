<?php

namespace Ekyna\Bundle\PaymentBundle\Controller;

use Ekyna\Bundle\CoreBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Payum\Core\Request\BinaryMaskStatusRequest;

/**
 * Class PaymentController
 * @package Ekyna\Bundle\PaymentBundle\Controller
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class PaymentController extends Controller
{
    public function paypalExpressCheckoutPaymentAction()
    {
        //$paymentName = 'paypal_express_checkout';
        $paymentName = 'paypal_test';

        $storage = $this->get('payum')->getStorageForClass(
            'Ekyna\Bundle\PaymentBundle\Entity\Payment',
            //'Ekyna\Bundle\OrderBundle\Entity\OrderPayment',
            $paymentName
        );

        /** @var \Ekyna\Bundle\PaymentBundle\Entity\Payment $paymentDetails */
        $paymentDetails = $storage->createModel();
        $paymentDetails->setMethod($paymentName);
        $paymentDetails['PAYMENTREQUEST_0_CURRENCYCODE'] = 'USD';
        $paymentDetails['PAYMENTREQUEST_0_AMT'] = 1.23;
        $storage->updateModel($paymentDetails);

        $captureToken = $this->get('payum.security.token_factory')->createCaptureToken(
            $paymentName,
            $paymentDetails,
            'ekyna_payment_done' // the route to redirect after capture;
        );

        $paymentDetails['INVNUM'] = $paymentDetails->getId();
        $paymentDetails['RETURNURL'] = $captureToken->getTargetUrl();
        $paymentDetails['CANCELURL'] = $captureToken->getTargetUrl();
        $storage->updateModel($paymentDetails);

        return $this->redirect($captureToken->getTargetUrl());
    }

    public function captureDoneAction(Request $request)
    {
        $token = $this->get('payum.security.http_request_verifier')->verify($request);

        $payment = $this->get('payum')->getPayment($token->getPaymentName());

        $status = new BinaryMaskStatusRequest($token);
        $payment->execute($status);

        if ($status->isSuccess()) {
            //$this->getUser()->addCredits(100);
            $this->addFlash('Payment success. Credits were added', 'success');
        } else if ($status->isPending()) {
            $this->addFlash('Payment is still pending. Credits were not added', 'warning');
        } else {
            $this->addFlash('Payment failed', 'error');
        }

        return $this->redirect($this->generateUrl('ekyna_cart_index'));
    }
}
