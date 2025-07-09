<?php

namespace Acme\CountUp\Repository;

use Acme\CountUp\Entity\Champion;
use Acme\CountUp\Model\CharFrequency;
use Acme\CountUp\Entity\Word;
use Acme\CountUp\Model\Interface\FrequencyInterface;
use BadMethodCallException;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use InvalidArgumentException;
use LogicException;
use Psr\Cache\InvalidArgumentException as CacheInvalidArgumentException;
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

    //TODO fix docblock (ALSO fix this method to use the new performant system instead of REPLACE)
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

        foreach (new CharFrequency($string) as $char => $count) {
            $conditions[] = "(LENGTH(term) - LENGTH(REPLACE(term, ?, ''))) >= ?";
            $parameters[] = $char;
            $parameters[] = $count;
            // Required to ensure that we don't pull strings that are too long. ["e","x","a","m"] Could also trigger 'admixtures' otherwise.
            $searchLength += $count;
        }

        if (empty($conditions)) {
            // There are no anagrams when there are no term conditions (perhaps there are no letters left?)
            return [];
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

        if($count < 2){
            throw new RuntimeException('Database has less than 2 word entries, cannot select a random word. Please update the database with more word records.');
        }

        $randomOffset = rand(0, $count - 1);

        return $qb->select('w')
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    //YUCK
    /**
     * @return array<string>
     */
    public function findWordTermByCharFrequency(FrequencyInterface $charFreq): array{
        $paddedCharFreq = new CharFrequency('', true)->addFrequency($charFreq);
        $qb = $this->createQueryBuilder('w');

        $qb->select('w.term');

        $maxWordLength = 0;
        foreach ($paddedCharFreq as $char => $freq) {
            $qb->andWhere($qb->expr()->lte('w.l_'.$char, $freq));
            $maxWordLength += $freq;
        }

        $qb->andWhere($qb->expr()->lte('w.term_length', $maxWordLength));

        // $qb->addOrderBy('w.term_length', 'DESC'); EXPENSIVE!!
        
        $qb->setMaxResults(10);

        return $qb->getQuery()->getResult(AbstractQuery::HYDRATE_SCALAR);
    }
}
