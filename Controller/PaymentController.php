<?php

namespace Ekyna\Bundle\PaymentBundle\Controller;

use Ekyna\Bundle\CoreBundle\Controller\Controller;
use Ekyna\Bundle\PaymentBundle\Entity\TestPayment;
use Ekyna\Bundle\PaymentBundle\Event\PaymentEvent;
use Ekyna\Bundle\PaymentBundle\Event\PaymentEvents;
use Ekyna\Bundle\PaymentBundle\Payum\Request\Done;
use Payum\Core\Request\Notify;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @return Response
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

        return $this->render('EkynaPaymentBundle:Payment:prepare.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Notify action.
     *
     * @param Request $request
     * @return Response
     */
    public function notifyAction(Request $request)
    {
        $token = $this->getHttpRequestVerifier()->verify($request);

        $gateway = $this->getPayum()->getPayment($token->getPaymentName());

        $gateway->execute($notify = new Notify($token));

        /** @var \Ekyna\Component\Sale\Payment\PaymentInterface $payment */
        $payment = $notify->getFirstModel();

        $event = new PaymentEvent($payment);
        $this->getDispatcher()->dispatch(PaymentEvents::NOTIFY, $event);

        return new Response('', 204);
    }

    /**
     * Done action.
     *
     * @param Request $request
     * @return Response
     */
    public function doneAction(Request $request)
    {
        $debug = $this->container->getParameter('kernel.debug');

        $token = $this->getHttpRequestVerifier()->verify($request);

        $gateway = $this->getPayum()->getPayment($token->getPaymentName());

        $gateway->execute($done = new Done($token));

        if (!$debug) {
            $this->getHttpRequestVerifier()->invalidate($token);
        }

        /** @var \Ekyna\Component\Sale\Payment\PaymentInterface $payment */
        $payment = $done->getFirstModel();

        $event = new PaymentEvent($payment);
        $this->getDispatcher()->dispatch(PaymentEvents::DONE, $event);
        if (null !== $response = $event->getResponse()) {
            return $response;
        }

        if ($debug) {
            return $this->render('EkynaPaymentBundle:Payment:done.html.twig', [
                'payment' => $payment,
            ]);
        }

        return $this->redirect('/');
    }

    /**
     * Returns the payum token verifier.
     *
     * @return \Payum\Core\Bridge\Symfony\Security\HttpRequestVerifier
     */
    private function getHttpRequestVerifier()
    {
        return $this->get('payum.security.http_request_verifier');
    }

    /**
     * Returns the Payum registry.
     *
     * @return \Payum\Core\Registry\DynamicRegistry
     */
    private function getPayum()
    {
        return $this->get('payum');
    }
}
