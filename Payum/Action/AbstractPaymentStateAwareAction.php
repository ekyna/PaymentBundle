<?php

namespace Ekyna\Bundle\PaymentBundle\Payum\Action;

use Doctrine\Common\Persistence\ObjectManager;
use Ekyna\Component\Sale\Payment\PaymentInterface;
use Payum\Core\Action\PaymentAwareAction;
use SM\Factory\FactoryInterface;

/**
 * Class AbstractPaymentStateAwareAction
 * @package Ekyna\Bundle\PaymentBundle\Payum\Action
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractPaymentStateAwareAction extends PaymentAwareAction
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var ObjectManager
     */
    protected $objectManager;


    /**
     * @param ObjectManager    $manager
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, ObjectManager $manager)
    {
        $this->factory = $factory;
        $this->objectManager = $manager;
    }

    /**
     * Updates the payment state.
     *
     * @param PaymentInterface $payment
     * @param string           $nextState
     */
    protected function updatePaymentState(PaymentInterface $payment, $nextState)
    {
        if ($payment->getState() !== $nextState) {
            /** @var \Ekyna\Bundle\PaymentBundle\StateMachine\StateMachineInterface $stateMachine */
            $stateMachine = $this->factory->get($payment);
            if (null !== $transition = $stateMachine->getTransitionToState($nextState)) {
                $stateMachine->apply($transition);

                $this->objectManager->flush();
            }
        }
    }
}
