<?php

namespace Ekyna\Bundle\PaymentBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Ekyna\Bundle\CoreBundle\Model as Core;
use Ekyna\Bundle\MediaBundle\Model\MediaSubjectInterface;
use Ekyna\Bundle\PaymentBundle\Entity\Message;
use Ekyna\Component\Sale\Payment\MethodInterface as BaseInterface;

/**
 * Interface MethodInterface
 * @package Ekyna\Bundle\PaymentBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface MethodInterface extends BaseInterface,
    Core\TimestampableInterface,
    Core\SortableInterface,
    MediaSubjectInterface
{
    /**
     * Returns the identifier.
     *
     * @return integer
     */
    public function getId();

    /**
     * Sets the description.
     *
     * @param string $description
     * @return MethodInterface|$this
     */
    public function setDescription($description);

    /**
     * Returns the description.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Sets the messages.
     *
     * @param ArrayCollection $messages
     * @return MethodInterface|$this
     */
    public function setMessages(ArrayCollection $messages);

    /**
     * Returns whether the method as the message or not.
     *
     * @param Message $message
     * @return bool
     */
    public function hasMessage(Message $message);

    /**
     * Adds the message.
     *
     * @param Message $message
     * @return MethodInterface|$this
     */
    public function addMessage(Message $message);

    /**
     * Removes the message.
     *
     * @param Message $message
     * @return MethodInterface|$this
     */
    public function removeMessage(Message $message);

    /**
     * Returns the messages.
     *
     * @return ArrayCollection
     */
    public function getMessages();

    /**
     * Returns the message by state.
     *
     * @return Message|null
     */
    public function getMessageByState($state);

    /**
     * Sets the enabled.
     *
     * @param boolean $enabled
     * @return MethodInterface|$this
     */
    public function setEnabled($enabled);

    /**
     * Returns the enabled.
     *
     * @return boolean
     */
    public function getEnabled();
}