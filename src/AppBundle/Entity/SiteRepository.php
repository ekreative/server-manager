<?php

namespace AppBundle\Entity;

use AppBundle\Form\ModelTransformer\SitesFilter;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class SiteRepository extends EntityRepository
{
    /**
     * @param SitesFilter $filter
     * @return Query
     */
    public function searchQuery(SitesFilter $filter)
    {
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.project', 'p')
            ->addSelect('p')
            ->leftJoin('s.framework', 'f');

        if ($filter->getFramework() && $filter->getFramework() != 'All') {
            $qb->andWhere('s.framework = ?1')
                ->setParameter('1', $filter->getFramework())
            ;
        }

        if ($filter->getStatus() && $filter->getStatus() != 'All') {
            $qb->andWhere('s.status = :status')
                ->setParameter('status', $filter->getStatus())
            ;
        }
        if ($filter->getName()) {
            $qb->andWhere('s.name LIKE :name')
                ->setParameter('name', "%" . addcslashes($filter->getName(), '%_') . "%");
        }

        return $qb->getQuery();
    }
}
