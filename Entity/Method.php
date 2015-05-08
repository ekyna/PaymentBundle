<?php

namespace Ekyna\Bundle\PaymentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ekyna\Bundle\CmsBundle\Model\ImageSubjectTrait;
use Ekyna\Bundle\CoreBundle\Model\TimestampableTrait;
use Ekyna\Component\Sale\Payment\MethodInterface;
use Payum\Core\Model\PaymentConfig as BasePaymentConfig;

/**
 * Class Method
 * @package Ekyna\Bundle\PaymentBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Method extends BasePaymentConfig implements MethodInterface
{
    use ImageSubjectTrait,
        TimestampableTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var ArrayCollection
     */
    protected $messages;

    /**
     * @var bool
     */
    protected $enabled = false;


    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->messages = new ArrayCollection();
    }

    /**
     * Returns the string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getPaymentName();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the factory name
     * @param string $factoryName
     * @return Method
     */
    public function setFactoryName($factoryName)
    {
        $this->factoryName = $factoryName;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFactoryName()
    {
        return $this->factoryName;
    }

    /**
     * Sets the payment name.
     *
     * @param string $paymentName
     * @return Method
     */
    public function setPaymentName($paymentName)
    {
        $this->paymentName = $paymentName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentName()
    {
        return $this->paymentName;
    }

    /**
     * Sets the config.
     *
     * @param array $config
     * @return Method
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setMessages(ArrayCollection $messages)
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMessage(Message $message)
    {
        return $this->messages->contains($message);
    }

    /**
     * {@inheritdoc}
     */
    public function addMessage(Message $message)
    {
        if (!$this->hasMessage($message)) {
            $message->setMethod($this);
            $this->messages->add($message);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeMessage(Message $message)
    {
        if (!$this->hasMessage($message)) {
            $message->setMethod(null);
            $this->messages->removeElement($message);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }
}
