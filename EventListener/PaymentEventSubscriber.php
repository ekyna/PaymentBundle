<?php

namespace Ekyna\Bundle\PaymentBundle\EventListener;

use Ekyna\Bundle\PaymentBundle\Event\PaymentEvent;
use Ekyna\Bundle\PaymentBundle\Event\PaymentEvents;
use Payum\Core\Registry\RegistryInterface;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class PaymentEventSubscriber
 * @package Ekyna\Bundle\PaymentBundle\EventListener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class PaymentEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var RegistryInterface
     */
    protected $registry;

    /**
     * @var GenericTokenFactoryInterface
     */
    protected $factory;


    /**
     * Constructor.
     *
     * @param RegistryInterface            $registry
     * @param GenericTokenFactoryInterface $factory
     */
    public function __construct(RegistryInterface $registry, GenericTokenFactoryInterface $factory)
    {
        $this->registry = $registry;
        $this->factory  = $factory;
    }

    /**
     * Payment prepare event handler.
     *
     * @param PaymentEvent $event
     */
    public function onPaymentPrepare(PaymentEvent $event)
    {
        $payment = $event->getPayment();

        $storage = $this->registry->getStorage(get_class($payment));

        $storage->update($payment);

        $captureToken = $this->factory->createCaptureToken(
            $payment->getMethod(),
            $payment,
            'ekyna_payment_done' // the route to redirect after capture
        );

        $event->setResponse(new RedirectResponse($captureToken->getTargetUrl()));
    }

    /**
     * {@inheritdoc}
     */
    static public function getSubscribedEvents()
    {
        return array(
            PaymentEvents::PREPARE => array('onPaymentPrepare', -1024),
        );
    }
}
