<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class SiteRepository extends EntityRepository
{
    /**
     * @param string $name
     * @param int $framework
     * @param boolean $responsibility
     * @return Query
     */
    public function searchQuery($name = null, $framework = null, $responsibility = null)
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

        if ($responsibility == 0) {
            $qb
                ->andWhere('s.responsibility = :responsibility')
                ->setParameter('responsibility', $responsibility);
        }

        return $qb->getQuery();
    }
}
