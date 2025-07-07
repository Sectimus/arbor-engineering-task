<?php

namespace Acme\CountUp\Repository;

use Acme\CountUp\Entity\Champion;
use Acme\CountUp\Model\CharFrequency;
use Acme\CountUp\Entity\Word;
use BadMethodCallException;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use InvalidArgumentException;
use RuntimeException;

/**
 * @extends EntityRepository<Word>
 */
class WordRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Word::class));
    }

    public function save(Champion $champion, bool $flush = false): void
    {
        $this->getEntityManager()->persist($champion);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //TODO fix docblock
    // /**
    //  * Returns a list of words that contain the provided character frequencies. Kind of like scrabble!
    //  * This is useful for finding out which words could be spelled with the remaining letters (needs to match EVERY letter)
    //  * There is no performant way to do this kind of calculation at the DB level, worst still Doctrine does not allow the use of REGEX or REPLACE in DQL, so raw, yucky, SQL it is.
    //  * @param array<string, int> $frequencies A 1d array indicatng the occurance of each character, keyed per character. `['a'=>2, 'g'=>1]`
    //  */
    /**
     * @return array<string>
     */
    public function findAnagrams(string $string, int $limit = 10, int $offset = 0): array
    {
        $conditions = [];
        $parameters = [];
        $searchLength = 0;

        $frequencies = new CharFrequency($string)->getFrequencies();
        foreach ($frequencies as $char => $count) {
            $conditions[] = "(LENGTH(term) - LENGTH(REPLACE(term, ?, ''))) >= ?";
            $parameters[] = $char;
            $parameters[] = $count;
            // Required to ensure that we don't pull strings that are too long. ["e","x","a","m"] Could also trigger 'admixtures' otherwise.
            $searchLength += $count;
        }

        if (empty($conditions)) {
            throw new InvalidArgumentException('Frequencies array cannot be empty');
        }

        $whereClause = implode(' AND ', $conditions);
        $sql = "SELECT word.term FROM word WHERE LENGTH(term) = {$searchLength} AND {$whereClause} LIMIT {$limit} OFFSET {$offset}";

        return $this->getEntityManager()
            ->getConnection()
            ->executeQuery($sql, $parameters)
            ->fetchFirstColumn();
    }

    public function findWordByTerm(string $term): ?Word
    {
        return $this->findOneBy(['term' => $term]);
    }

    public function getRandomWord(): Word
    {
        $qb = $this->createQueryBuilder('w');
        /** @var int $count */
        $count = $qb->select('COUNT(w.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $randomOffset = rand(0, $count - 1);

        return $qb->select('w')
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}
