<?php

namespace Ekyna\Bundle\PaymentBundle\Entity;

use Ekyna\Bundle\AdminBundle\Doctrine\ORM\ResourceRepositoryInterface;
use Ekyna\Bundle\PaymentBundle\Model\PaymentStates;
use Gedmo\Sortable\Entity\Repository\SortableRepository;

/**
 * Class MethodRepository
 * @package Ekyna\Bundle\PaymentBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MethodRepository extends SortableRepository implements ResourceRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        $class = $this->getClassName();
        /** @var Method $method */
        $method = new $class;

        foreach (PaymentStates::getNotifiableStates() as $state) {
            $message = new Message();
            $method->addMessage($message->setState($state));
        }

        return $method;
    }
}
