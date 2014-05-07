<?php

namespace Ekyna\Bundle\PaymentBundle\Entity;

use Payum\Core\Model\ArrayObject;
use Ekyna\Component\Sale\Payment\PaymentStates;
use Ekyna\Component\Sale\Payment\PaymentInterface;

/**
 * Payment.
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class Payment extends ArrayObject implements PaymentInterface
{
    /**
     * @var integer
     */
    protected $id;

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
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $details = array();

    /**
     * @var \Datetime
     */
    protected $createdAt;

    /**
     * @var \Datetime
     */
    protected $updatedAt;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->currency = 'EUR';
        $this->state = PaymentStates::STATE_NEW;
    }

    /**
     * Returns the identifier.
     * 
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

	/**
	 * Sets the amount.
	 * 
     * @param float $amount
     * 
     * @return \Ekyna\Bundle\PaymentBundle\Entity\Payment
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
	 * Sets the currency code.
	 * 
     * @param string $currency
     * 
     * @return \Ekyna\Bundle\PaymentBundle\Entity\Payment
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
     * Sets the state.
     * 
     * @param string $state
     * 
     * @return \Ekyna\Bundle\PaymentBundle\Entity\Payment
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
     * Sets the method.
     * 
     * @param string $method
     * 
     * @return \Ekyna\Bundle\PaymentBundle\Entity\Payment
     */
    public function setMethod($method)
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
     * Sets the details.
     * 
     * @param array $details
     * 
     * @return \Ekyna\Bundle\PaymentBundle\Entity\Payment
     */
    public function setDetails(array $details)
    {
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
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->details);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->details[$offset];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->details[$offset] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->details[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->details);
    }

    /**
     * Sets the "created at" datetime.
     * 
     * @param \Datetime $createdAt
     * 
     * @return \Ekyna\Bundle\PaymentBundle\Entity\Payment
     */
    public function setCreatedAt(\Datetime $createdAt)
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
     * Sets the "updated at" datetime.
     * 
     * @param \Datetime $updateAt
     * 
     * @return \Ekyna\Bundle\PaymentBundle\Entity\Payment
     */
    public function setUpdatedAt(\Datetime $updateAt)
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
