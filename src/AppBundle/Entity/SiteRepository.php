<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SiteRepository extends EntityRepository
{
    /**
     * @param string $name
     * @param int $framework
     * @return array
     */
    public function search( $name = null, $framework = null)
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
        return $qb->getQuery()->getResult();
    }
}
