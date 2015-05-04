<?php

namespace Ekyna\Bundle\PaymentBundle\Model;

use Ekyna\Component\Sale\Payment\PaymentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Trait PaymentTransitionTrait
 * @package Ekyna\Bundle\PaymentBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
trait PaymentTransitionTrait
{
    /**
     * @param PaymentInterface $payment
     * @param $transition
     */
    protected function applyPaymentTransition(PaymentInterface $payment, $transition)
    {
        if (!$this instanceof Controller) {
            throw new \LogicException(
                'PaymentTransitionTrait may be used only in instances of ' .
                'Symfony\Bundle\FrameworkBundle\Controller\Controller'
            );
        }

        $flashBag = $this->get('session')->getFlashBag();
        $sm = $this->get('sm.factory')->get($payment);
        if ($sm->can($transition)) {
            $sm->apply($transition);

            $em = $this->getDoctrine()->getManager();
            $em->persist($payment);
            $em->flush();

            $flashBag->add('success', sprintf('ekyna_payment.payment.state.%s.message', $payment->getState()));
        } else {
            $flashBag->add('danger', 'ekyna_payment.payment.impossible_transition');
        }
    }
}
