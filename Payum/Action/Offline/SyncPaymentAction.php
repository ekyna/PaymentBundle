<?php

namespace Ekyna\Bundle\PaymentBundle\Payum\Action\Offline;

use Ekyna\Component\Sale\Payment\PaymentInterface;
use Payum\Core\Action\PaymentAwareAction;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Sync;

/**
 * Class SyncPaymentAction
 * @package Ekyna\Bundle\PaymentBundle\Payum\Action\Offline
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class SyncPaymentAction extends PaymentAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param $request Sync
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        return;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Sync &&
            $request->getModel() instanceof PaymentInterface
        ;
    }
}
