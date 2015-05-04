<?php

namespace Ekyna\Bundle\PaymentBundle\Controller;

use Ekyna\Bundle\CoreBundle\Controller\Controller;
use Ekyna\Bundle\PaymentBundle\Entity\TestPayment;
use Ekyna\Bundle\PaymentBundle\Event\PaymentEvent;
use Ekyna\Bundle\PaymentBundle\Event\PaymentEvents;
use Ekyna\Bundle\PaymentBundle\Payum\Request\Done;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PaymentController
 * @package Ekyna\Bundle\PaymentBundle\Controller
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class PaymentController extends Controller
{
    /**
     * Prepare (test) action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function prepareAction(Request $request)
    {
        $payment = new TestPayment();
        $payment
            ->setCurrency('EUR')
            ->setAmount(123)
        ;

        $form = $this->createForm('ekyna_payment_test_payment', $payment);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $event = new PaymentEvent($payment);
            $this->getDispatcher()->dispatch(PaymentEvents::PREPARE, $event);
            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $this->render('EkynaPaymentBundle:Payment:prepare.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Done action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doneAction(Request $request)
    {
        $debug = $this->container->getParameter('kernel.debug');

        $payumRequestVerifier = $this->get('payum.security.http_request_verifier');
        $token = $payumRequestVerifier->verify($request);

        $payumPayment = $this->get('payum')->getPayment($token->getPaymentName());

        $payumPayment->execute($done = new Done($token));

        if (!$debug) {
            $payumRequestVerifier->invalidate($token);
        }

        /** @var \Ekyna\Component\Sale\Payment\PaymentInterface $payment */
        $payment = $done->getFirstModel();

        $event = new PaymentEvent($payment);
        $this->getDispatcher()->dispatch(PaymentEvents::DONE, $event);
        if (null !== $response = $event->getResponse()) {
            return $response;
        }

        if ($debug) {
            return $this->render('EkynaPaymentBundle:Payment:done.html.twig', array(
                'payment' => $payment,
            ));
        }

        return $this->redirect('/');
    }
}
