<?php

namespace Acme\CountUp\DataFixtures;

use Acme\CountUp\Entity\Word;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;

class DictionaryFixtures extends Fixture
{
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
                if (!empty($term)) {
                    $word = new Word();
                    // We manually set the ID to ensure a range from 1->MAX is complete without any gaps for *cheap* random selection.
                    $word->setId($i)->setLang('en')->setTerm($term);
                    $manager->persist($word);
                    
                    $count++;
                    if ($count === self::IMPORT_BATCH_SIZE) {
                        $manager->flush();
                        $manager->clear();
                        $count = 0;
                    }
                    $i++;
                }
            }
            fclose($handle);
            
            // Flush any remaining entities
            $manager->flush();
            $manager->clear();
        }
    }
}
