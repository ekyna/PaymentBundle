<?php

namespace Ekyna\Bundle\PaymentBundle\Entity;

use Ekyna\Component\Sale\Payment\MethodInterface;
use Payum\Core\Model\PaymentConfig as BasePaymentConfig;

/**
 * Class Method
 * @package Ekyna\Bundle\PaymentBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Method extends BasePaymentConfig implements MethodInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var bool
     */
    protected $enabled = false;


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
     * Returns the id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the enabled.
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Sets the enabled.
     *
     * @param boolean $enabled
     * @return Method
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }
}
