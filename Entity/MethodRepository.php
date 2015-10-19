<?php

namespace Ekyna\Bundle\PaymentBundle\Entity;

use Ekyna\Bundle\AdminBundle\Doctrine\ORM\ResourceRepository;
use Ekyna\Bundle\AdminBundle\Doctrine\ORM\ResourceRepositoryInterface;
use Ekyna\Bundle\AdminBundle\Doctrine\ORM\Util\ResourceRepositoryTrait;
use Ekyna\Bundle\PaymentBundle\Model\PaymentStates;
use Gedmo\Sortable\Entity\Repository\SortableRepository;

/**
 * Class MethodRepository
 * @package Ekyna\Bundle\PaymentBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MethodRepository  extends SortableRepository implements ResourceRepositoryInterface
{
    use ResourceRepositoryTrait {
        createNew as traitCreateNew;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        /** @var Method $method */
        $method = $this->traitCreateNew();

        foreach (PaymentStates::getNotifiableStates() as $state) {
            $message = new Message();
            $method->addMessage($message->setState($state));
        }

        return $method;
    }
}
