<?php

namespace Ekyna\Bundle\PaymentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ekyna\Bundle\CoreBundle\Model as Core;
use Ekyna\Bundle\MediaBundle\Model\MediaSubjectTrait;
use Ekyna\Bundle\PaymentBundle\Model\MethodInterface;
use Ekyna\Bundle\PaymentBundle\Model\PaymentStates;
use Payum\Core\Model\GatewayConfig as BasePaymentConfig;

/**
 * Class Method
 * @package Ekyna\Bundle\PaymentBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Method extends BasePaymentConfig implements MethodInterface
{
    use Core\TimestampableTrait,
        Core\SortableTrait,
        MediaSubjectTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var ArrayCollection|Message[]
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
        return $this->getGatewayName();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function setGatewayName($gatewayName)
    {
        $this->gatewayName = $gatewayName;
        return $this;
    }

    /**
     * @return string
     */
    public function getGatewayName()
    {
        return $this->gatewayName;
    }

    /**
     * {@inheritDoc}
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
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
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
    public function getMessageByState($state)
    {
        if (PaymentStates::isValid($state)) {
            foreach ($this->messages as $message) {
                if ($message->getState() === $state) {
                    return $message;
                }
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
}
