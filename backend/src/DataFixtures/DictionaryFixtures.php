<?php

namespace Acme\CountUp\DataFixtures;

use Acme\CountUp\Entity\Word;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * When using this fixture, it is expected that you may need to unlimit php's memory usage due to the scale of computations required.
 * `php -d memory_limit=-1 bin/console doctrine:fixtures:load`
 */
class DictionaryFixtures extends Fixture
{
    private string $projectRootDir;
    // Perhaps it would be wiser to use the word service to import? 
    // Though I don't think it *should* ever be used outside of fixtures due to how intensive creating the indexes is.
    public function __construct(KernelInterface $kernel)
    {
        $this->projectRootDir = $kernel->getProjectDir();
    }
    private const IMPORT_BATCH_SIZE = 5000;
        
    /**
     * Loads a newline delimited list of dictionary words into the database.
     * @param EntityManager $manager 
     */
    public function load(ObjectManager $manager): void
    {
        // Load english (en) alpha dictionary terms into DB
        $handle = fopen($this->projectRootDir . '/fixtures/alpha_dictionaries/en.txt', 'r');
        if ($handle) {
            $count = 0;
            $i = 1;
    
            while (($line = fgets($handle)) !== false) {
                $term = strtolower(trim($line));
                if (strlen($term) < 3) {
                    // We only want to import words that are at least 3 chars long.
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
            // Flush any remaining entities
            $manager->flush();
            $manager->clear();
        }
    }
}
