<?php

namespace Ekyna\Bundle\PaymentBundle\StateMachine;

use Ekyna\Bundle\PaymentBundle\Event\PaymentEvent;
use Ekyna\Bundle\PaymentBundle\Event\PaymentEvents;
use SM\StateMachine\StateMachine as BaseStateMachine;

/**
 * Class StateMachine
 * @package Ekyna\Bundle\PaymentBundle\StateMachine
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class StateMachine extends BaseStateMachine implements StateMachineInterface
{
    /**
     * {@inheritDoc}
     */
    public function apply($transition, $soft = false)
    {
        if (parent::apply($transition, $soft)) {
            /** @var \Ekyna\Component\Sale\Payment\PaymentInterface $payment */
            $payment = $this->getObject();
            $this->dispatcher->dispatch(PaymentEvents::STATE_CHANGE, new PaymentEvent($payment));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getTransitionFromState($fromState)
    {
        foreach ($this->getPossibleTransitions() as $transition) {
            $config = $this->config['transitions'][$transition];
            if (in_array($fromState, $config['from'])) {
                return $transition;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getTransitionToState($toState)
    {
        foreach ($this->getPossibleTransitions() as $transition) {
            $config = $this->config['transitions'][$transition];
            if ($toState === $config['to']) {
                return $transition;
            }
        }

        return null;
    }
}
