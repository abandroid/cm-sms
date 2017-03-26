<?php

namespace Endroid\CmSms\Bundle\Repository;

use Doctrine\ORM\EntityRepository;
use Endroid\CmSms\Bundle\Entity\Status;

class StatusRepository extends EntityRepository
{
    /**
     * @param Status $status
     */
    public function save(Status $status)
    {
        $status->setMessage($this->getEntityManager()->getReference('EndroidCmSmsBundle:Message', $status->getData()['REFERENCE']));
        $this->getEntityManager()->merge($status);
        $this->getEntityManager()->flush();
    }
}