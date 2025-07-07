<?php

namespace Acme\CountUp\DataFixtures;

use Acme\CountUp\Entity\Word;
use Acme\CountUp\Model\CharFrequency;
use Acme\CountUp\Repository\WordRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;

class DictionaryFixtures extends Fixture
{
    //TODO use the word repo.
    public function __construct()
    {}
    private const IMPORT_BATCH_SIZE = 5000;
    
    //TODO split up this loading func
    
    /**
     * @param EntityManager $manager 
     */
    public function load(ObjectManager $manager): void
    {
        // Load english (en) alpha dictionary terms into DB
        $handle = fopen(__DIR__ . '/data/alpha_dictionaries/en.txt', 'r');
        if ($handle) {
            $count = 0;
            $i = 1;
    
            while (($line = fgets($handle)) !== false) {
                $term = strtolower(trim($line));
                if (strlen($term) < 3) {
                    //we want words at least 3 chars long. Skip this.
                    continue;
                }
                $word = new Word($term);
                // We manually set the ID to ensure a range from 1->MAX is complete without any gaps for *cheap* random selection.
                $word->setId($i)->setTerm($term);

                $manager->persist($word);
                
                $count++;
                if ($count === self::IMPORT_BATCH_SIZE) {
                    $manager->flush();
                    $manager->clear();
                    $count = 0;
                }
                $i++;
            }
            fclose($handle);
        }
    
    // Flush any remaining entities
    $manager->flush();
    $manager->clear();
    }
}
