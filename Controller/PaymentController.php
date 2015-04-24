<?php

namespace Ekyna\Bundle\PaymentBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Ekyna\Bundle\CoreBundle\Controller\Controller;
use Ekyna\Bundle\PaymentBundle\Entity\TestPayment;
use Ekyna\Bundle\PaymentBundle\Event\PaymentEvent;
use Ekyna\Bundle\PaymentBundle\Event\PaymentEvents;
use Ekyna\Bundle\PaymentBundle\Payum\Request\GetPaymentStatus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $form = $this->createForm('ekyna_payment_method');

        $form->handleRequest($request);
        if ($form->isValid()) {
            $method = $form->get('method')->getData();

            $payment = new TestPayment();
            $payment
                ->setCurrency('EUR')
                ->setAmount(123)
                ->setMethod($method)
            ;

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
        $payumRequestVerifier = $this->get('payum.security.http_request_verifier');
        $token = $payumRequestVerifier->verify($request);

        $payumPayment = $this->get('payum')->getPayment($token->getPaymentName());

        //$payumRequestVerifier->invalidate($token);

        $payumPayment->execute($status = new GetPaymentStatus($token));

        /** @var \Ekyna\Component\Sale\Payment\PaymentInterface $payment */
        $payment = $status->getFirstModel();

        $event = new PaymentEvent($payment);
        $this->getDispatcher()->dispatch(PaymentEvents::DONE, $event);
        if (null !== $response = $event->getResponse()) {
            return $response;
        }

        return $this->render('EkynaPaymentBundle:Payment:done.html.twig', array(
            'status' => $status->getValue(),
            'payment' => $payment,
        ));
    }

    public function paypalIpnAction(Request $request)
    {
        if (!$this->container->getParameter('kernel.debug')) {
            throw new NotFoundHttpException();
        }

        $form = $this
            ->createFormBuilder()
            ->add('token', 'entity', array(
                'class' => 'EkynaPaymentBundle:PayumSecurityToken',
                'property' => 'hash',
                'query_builder' => function(EntityRepository $er) {
                    $qb = $er->createQueryBuilder('t');
                    return $qb
                        ->andWhere($qb->expr()->eq('t.paymentName', $qb->expr()->literal('paypal_express_checkout')))
                    ;
                },
            ))
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isValid()) {

            /** @var \Ekyna\Bundle\PaymentBundle\Entity\PayumSecurityToken $token */
            $token = $form->get('token')->getData();
            $identity = $token->getDetails();

            $storage = $this->get('payum')->getStorage($identity->getClass());

            /** @var \Ekyna\Component\Sale\Payment\PaymentInterface $payment */
            $payment = $storage->find($identity->getId());
            $details = $payment->getDetails();

            $params = [];
            foreach ($details as $key => $value) {
                $params[] = $key.'='.urlencode($value);
            }
            $req = join('&', $params);

            $ch = curl_init();

            curl_setopt($ch,CURLOPT_URL, $token->getTargetUrl());
            curl_setopt($ch,CURLOPT_POST, substr_count($req,'&')+1);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $req);

            $result = curl_exec($ch);
            curl_close($ch);

            var_dump($result);
            exit();
        }

        return $this->render('EkynaPaymentBundle:Payment:paypal_ipn.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
