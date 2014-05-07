<?php

namespace Ekyna\Bundle\PaymentBundle\Payum\Request;

use Payum\Core\Request\BaseStatusRequest;
use Ekyna\Component\Sale\Payment\PaymentStates;

/**
 * StatusRequest.
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class PaymentStatusRequest extends BaseStatusRequest
{
    /**
     * {@inheritdoc}
     */
    public function markNew()
    {
        $this->status = PaymentStates::STATE_NEW;
    }

    /**
     * {@inheritdoc}
     */
    public function isNew()
    {
        return $this->status === PaymentStates::STATE_NEW;
    }

    /**
     * {@inheritdoc}
     */
    public function markSuccess()
    {
        $this->status = PaymentStates::STATE_SUCCESS;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        return $this->status === PaymentStates::STATE_SUCCESS;
    }

    /**
     * {@inheritdoc}
     */
    public function markSuspended()
    {
        $this->status = PaymentStates::STATE_PROCESSING;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuspended()
    {
        return $this->status === PaymentStates::STATE_PROCESSING;
    }

    /**
     * {@inheritdoc}
     */
    public function markExpired()
    {
        $this->status = PaymentStates::STATE_FAILED;
    }

    /**
     * {@inheritdoc}
     */
    public function isExpired()
    {
        return $this->status === PaymentStates::STATE_FAILED;
    }

    /**
     * {@inheritdoc}
     */
    public function markCanceled()
    {
        $this->status = PaymentStates::STATE_CANCELLED;
    }

    /**
     * {@inheritdoc}
     */
    public function isCanceled()
    {
        return $this->status === PaymentStates::STATE_CANCELLED;
    }

    /**
     * {@inheritdoc}
     */
    public function markPending()
    {
        $this->status = PaymentStates::STATE_PENDING;
    }

    /**
     * {@inheritdoc}
     */
    public function isPending()
    {
        return $this->status === PaymentStates::STATE_PENDING;
    }

    /**
     * {@inheritdoc}
     */
    public function markFailed()
    {
        $this->status = PaymentStates::STATE_FAILED;
    }

    /**
     * {@inheritdoc}
     */
    public function isFailed()
    {
        return $this->status === PaymentStates::STATE_FAILED;
    }

    /**
     * {@inheritdoc}
     */
    public function markUnknown()
    {
        $this->status = PaymentStates::STATE_UNKNOWN;
    }

    /**
     * {@inheritdoc}
     */
    public function isUnknown()
    {
        return $this->status === PaymentStates::STATE_UNKNOWN;
    }
}
