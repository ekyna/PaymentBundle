<?php

namespace Ekyna\Bundle\PaymentBundle\Model;

use Ekyna\Component\Sale\Payment\PaymentInterface as SaleBaseInterface;
use Payum\Core\Model\PaymentInterface as PayumBaseInterface;

/**
 * Class PaymentInterface
 * @package Ekyna\Bundle\PaymentBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface PaymentInterface extends SaleBaseInterface, PayumBaseInterface
{
}
