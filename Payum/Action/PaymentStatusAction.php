<?php

namespace Ekyna\Bundle\PaymentBundle\Payum\Action;

use Doctrine\Common\Persistence\ObjectManager;
use Ekyna\Bundle\PaymentBundle\Event\PaymentEvents;
use Ekyna\Bundle\PaymentBundle\Event\PaymentEvent;
use Ekyna\Bundle\PaymentBundle\Payum\Request\PaymentStatusRequest;
use Ekyna\Component\Sale\Payment\PaymentInterface;
use Payum\Core\Action\PaymentAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * PaymentStatusAction. 
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class PaymentStatusAction extends PaymentAwareAction
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param ObjectManager            $objectManager
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, ObjectManager $objectManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->objectManager   = $objectManager;
    }

    public function execute($request)
    {
        /** @var $request StatusRequestInterface */
        if (!$this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        /** @var \Ekyna\Component\Sale\Payment\PaymentInterface $payment */
        $payment = $request->getModel();
        $previousState = $payment->getState();

        $details = ArrayObject::ensureArrayObject($payment->getDetails());
        if (! empty($details)) {
            $request->setModel($details);

            $this->payment->execute($request);

            $payment->setDetails((array) $request->getModel());
            $request->setModel($payment);
        } else {
            $request->markNew();
        }
        $nextState = $request->getStatus();

        if ($nextState !== $previousState) {
            $payment->setState($nextState);
            $this->objectManager->persist($payment);
            $this->objectManager->flush();

            $this->eventDispatcher->dispatch(PaymentEvents::STATE_CHANGE, new PaymentEvent($payment));
        }
    }

    public function supports($request)
    {
        return
            $request instanceof PaymentStatusRequest &&
            $request->getModel() instanceof PaymentInterface
        ;
    }
}
