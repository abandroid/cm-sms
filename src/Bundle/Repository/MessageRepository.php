<?php

namespace Endroid\CmSms\Bundle\Repository;

use Doctrine\ORM\EntityRepository;
use Endroid\CmSms\Bundle\Entity\Message;

class MessageRepository extends EntityRepository
{
    /**
     * @param Message $message
     */
    public function save(Message $message)
    {
        $this->getEntityManager()->merge($message);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if (is_null($orderBy)) {
            $orderBy = ['dateUpdated' => 'DESC'];
        }

        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
}
