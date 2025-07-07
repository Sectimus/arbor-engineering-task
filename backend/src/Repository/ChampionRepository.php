<?php

namespace Acme\CountUp\Repository;

use Acme\CountUp\Entity\Champion;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Champion>
 */
class ChampionRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Champion::class));
    }

    public function save(Champion $champion, bool $flush = false): void
    {
        $this->getEntityManager()->persist($champion);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function delete(Champion $track, bool $flush = false): void
    {
        $this->getEntityManager()->remove($track);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByName(string $name): ?Champion{
        $qb = $this->createQueryBuilder('c');
        $qb->select('c')
           ->where([$qb->expr()->eq(
            'c.name', ':name'
        )]);

        $qb->setParameter('name', $name);

        return $qb->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return Champion[] Returns an array of Champion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Champion
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
