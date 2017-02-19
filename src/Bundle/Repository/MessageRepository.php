<?php

namespace Endroid\CmSms\Bundle\Repository;

use Doctrine\ORM\EntityRepository;
use Endroid\CmSms\Bundle\Entity\Message;
use Endroid\CmSms\Message as DomainMessage;

class MessageRepository extends EntityRepository
{
    /**
     * @param DomainMessage $domainMessage
     */
    public function save(DomainMessage $domainMessage)
    {
        $message = Message::fromDomainMessage($domainMessage);
        $this->getEntityManager()->persist($message);
        $this->getEntityManager()->flush();
    }
}