<?php

namespace Ekyna\Bundle\PaymentBundle\Payum\Action\Offline;

use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Sync;
use Payum\Offline\Constants;

/**
 * Class SyncAction
 * @package Ekyna\Bundle\PaymentBundle\Payum\Action\Offline
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class SyncAction extends GatewayAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param $request Sync
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (true == $model[Constants::FIELD_PAID]) {
            $model[Constants::FIELD_STATUS] = Constants::STATUS_CAPTURED;
        } else {
            $model[Constants::FIELD_STATUS] = Constants::STATUS_PENDING;
        }

        return;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Sync &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
