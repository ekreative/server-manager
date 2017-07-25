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
            ->join('s.project', 'p')
            ->addSelect('p')
            ->join('s.framework', 'f');

        if ($filter->getFramework() && $filter->getFramework() != 'All') {
            $qb->andWhere('s.framework = :framework')
                ->setParameter('framework', $filter->getFramework());
        }

        if ($filter->getClient() && $filter->getClient() != 'All') {
            $qb->andWhere('p.client = :client')
                ->setParameter('client', $filter->getClient());
        }

        if ($filter->getStatus() && $filter->getStatus() != 'All') {
            $qb->andWhere('s.status = :status')
                ->setParameter('status', $filter->getStatus());
        }
        if ($filter->getName()) {
            $qb->andWhere('s.name LIKE :name')
                ->setParameter('name', "%" . addcslashes($filter->getName(), '%_') . "%");
        }

        if (!empty($filter->getProjects())) {
            $qb->andWhere('p.id IN (:projects)')
                ->setParameter('projects', $filter->getProjects());
        }

        return $qb->getQuery();
    }
}
