<?php

namespace Acme\CountUp\DataFixtures;

use Acme\CountUp\Entity\Phrase;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;

class DictionaryFixtures extends Fixture
{
    private const IMPORT_BATCH_SIZE = 15000;
    
    /**
     * @param EntityManager $manager 
     */
    public function load(ObjectManager $manager): void
    {
        // Load english (en) alpha dictionary into DB
        $handle = fopen(__DIR__ . '/data/alpha_dictionaries/en.txt', 'r');
        if ($handle) {
            $count = 0;
    
            while (($line = fgets($handle)) !== false) {
                $word = trim($line);
                if (!empty($word)) {
                    $phrase = new Phrase();
                    $phrase->setLang('en')->setWord($word);
                    $manager->persist($phrase);
                    
                    $count++;
                    if ($count === self::IMPORT_BATCH_SIZE) {
                        $manager->flush();
                        $manager->clear();
                        $count = 0;
                    }
                }
            }
            fclose($handle);
            
            // Flush any remaining entities
            $manager->flush();
            $manager->clear();
            $manager->getConnection()->close();

            // Begin a new transaction (required on php 8.4 due to https://github.com/doctrine/DoctrineFixturesBundle/issues/363)
            $manager->getConnection()->beginTransaction();
        }
    }
}
