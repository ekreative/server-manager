<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class SiteRepository extends EntityRepository
{
    /**
     * @param string $name
     * @param int $framework
     * @param string $status
     * @return Query
     */
    public function searchQuery($name = null, $framework = null, $status = null, $projects = [])
    {
        $qb = $this->createQueryBuilder('s');
        if ($name) {
            $qb->andWhere('s.name LIKE :name')
                ->setParameter('name', "%" . addcslashes($name, '%_') . "%");
        }
        if ($framework) {
            $qb
                ->join('s.framework', 'framework')
                ->andWhere('framework.id = :framework')
                ->setParameter('framework', $framework);
        }

        if (count($projects) != 0) {
            $qb->andWhere('s.project IN (:projects)')
                ->setParameter('projects', $projects);
        }

        if ($status == Site::STATUS_SUPPORTED) {
            $qb
                ->andWhere('s.status = :status')
                ->setParameter('status', $status);
        }

        return $qb->getQuery();
    }
}
