<?php
declare(strict_types=1);

namespace Acme\CountUp\Repository;

use Acme\CountUp\Entity\Champion;
use Acme\CountUp\Model\CharFrequency;
use Acme\CountUp\Entity\Word;
use Acme\CountUp\Model\Interface\FrequencyInterface;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
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
