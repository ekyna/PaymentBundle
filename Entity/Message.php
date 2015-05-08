<?php

namespace Ekyna\Bundle\PaymentBundle\Entity;

/**
 * Class Message
 * @package Ekyna\Bundle\PaymentBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Message
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Method
     */
    protected $method;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $flash;

    /**
     * @var string
     */
    protected $email;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the method.
     *
     * @return Method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Sets the method.
     *
     * @param Method $method
     * @return Message
     */
    public function setMethod(Method $method = null)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Returns the state.
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets the state.
     *
     * @param string $state
     * @return Message
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Set flash
     *
     * @param string $flash
     * @return Message
     */
    public function setFlash($flash)
    {
        $this->flash = $flash;

        return $this;
    }

    /**
     * Get flash
     *
     * @return string 
     */
    public function getFlash()
    {
        return $this->flash;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Message
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
}
