<?php

namespace Ekyna\Bundle\PaymentBundle\Entity;

use Ekyna\Bundle\AdminBundle\Doctrine\ORM\ResourceRepository;
use Ekyna\Bundle\PaymentBundle\Model\PaymentStates;

/**
 * Class MethodRepository
 * @package Ekyna\Bundle\PaymentBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MethodRepository extends ResourceRepository
{
    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        /** @var Method $method */
        $method = parent::createNew();

        foreach (PaymentStates::getNotifiableStates() as $state) {
            $message = new Message();
            $method->addMessage($message->setState($state));
        }

        return $method;
    }
}
