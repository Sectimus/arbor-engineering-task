<?php
declare(strict_types=1);

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

    /**
     * Persist a champion with the entity manager
     */
    public function save(Champion $champion, bool $flush = false): void
    {
        $this->getEntityManager()->persist($champion);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Finds a champion by their name, will be converted to lowercase
     */
    public function findByName(string $name): ?Champion{
        $name = strtolower($name);
        $qb = $this->createQueryBuilder('c');
        $qb->select('c')
           ->where([$qb->expr()->eq(
            'c.name', ':name'
        )]);

        $qb->setParameter('name', $name);

        return $qb->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Finds the top n champions.
     * @return array<Champion>
     */
    public function findTop(int $n): array{
        $qb = $this->createQueryBuilder('c');
        $qb->select('c')
            ->setMaxResults($n)
            ->addOrderBy('c.score', 'DESC');

        return $qb->getQuery()
            ->getResult();
    }
}
