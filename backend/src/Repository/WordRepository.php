<?php

namespace Acme\CountUp\Repository;

use Acme\CountUp\Entity\Champion;
use Acme\CountUp\Entity\Word;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Word>
 */
class WordRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Word::class));
    }

    public function save(Champion $track, bool $flush = false): void
    {
        $this->getEntityManager()->persist($track);
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

    public function getRandomWord(): Word
    {
        $queryBuilder = $this->createQueryBuilder('w');
        /** @var int $count */
        $count = $queryBuilder->select('COUNT(w.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $randomOffset = rand(0, $count - 1);

        return $queryBuilder->select('w')
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}
