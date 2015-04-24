<?php

namespace Ekyna\Bundle\PaymentBundle\Payum\Request;

use Ekyna\Component\Sale\Payment\PaymentStates;
use Payum\Core\Request\BaseGetStatus;

/**
 * Class GetPaymentStatus
 * @package Ekyna\Bundle\PaymentBundle\Payum\Request
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class GetPaymentStatus extends BaseGetStatus
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
    public function markPending()
    {
        $this->status = PaymentStates::STATE_PROCESSING;
    }

    /**
     * {@inheritdoc}
     */
    public function isPending()
    {
        return $this->status === PaymentStates::STATE_PROCESSING;
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

    /**
     * {@inheritdoc}
     */
    public function markCaptured()
    {
        $this->status = PaymentStates::STATE_COMPLETED;
    }

    /**
     * {@inheritdoc}
     */
    public function isCaptured()
    {
        return $this->status === PaymentStates::STATE_COMPLETED;
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthorized()
    {
        return $this->status === PaymentStates::STATE_AUTHORIZED;
    }

    /**
     * {@inheritdoc}
     */
    public function markAuthorized()
    {
        $this->status = PaymentStates::STATE_AUTHORIZED;
    }

    /**
     * {@inheritdoc}
     */
    public function isRefunded()
    {
        return $this->status === PaymentStates::STATE_REFUNDED;
    }

    /**
     * {@inheritdoc}
     */
    public function markRefunded()
    {
        $this->status = PaymentStates::STATE_REFUNDED;
    }
}
