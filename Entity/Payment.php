<?php

namespace Ekyna\Bundle\PaymentBundle\Entity;

use Ekyna\Bundle\PaymentBundle\Model\PaymentInterface;
use Ekyna\Component\Sale\Payment\MethodInterface;
use Ekyna\Component\Sale\Payment\PaymentStates;
use Payum\Core\Model\ArrayObject;

/**
 * Class Payment
 * @package Ekyna\Bundle\PaymentBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class Payment extends ArrayObject implements PaymentInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var MethodInterface
     */
    protected $method;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $updatedAt;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->currency = 'EUR';
        $this->state = PaymentStates::STATE_NEW;
        $this->createdAt = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setMethod(MethodInterface $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * {@inheritdoc}
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * {@inheritDoc}
     */
    public function setDetails($details)
    {
        if ($details instanceof \Traversable) {
            $details = iterator_to_array($details);
        }
        $this->details = $details;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * {@inheritdoc}
     */
    public function getNumber()
    {
        return $this->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getClientEmail()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getClientId()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalAmount()
    {
        return $this->getAmount() * 100; // As expected by Payum
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencyCode()
    {
        return $this->getCurrency();
    }

    /**
     * {@inheritdoc}
     */
    public function getCreditCard()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritDoc}
     */
    public function setUpdatedAt(\DateTime $updateAt = null)
    {
        $this->updatedAt = $updateAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
