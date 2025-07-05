<?php

declare(strict_types=1);

namespace Acme\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250705054454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the initial Prompt entity';
    }

    /*
    * pneumonoultramicroscopicsilicovolcanoconiosis is known to be the longest word (45 characters) in a standard english dictionary
    */
    public function up(Schema $schema): void
    {
        // TODO create an index against the words
        $this->addSql(<<<'SQL'
            CREATE TABLE prompt (
                id INT AUTO_INCREMENT NOT NULL, 
                text VARCHAR(45) NOT NULL, 
                lang VARCHAR(2) NOT NULL, 
                PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TABLE prompt
        SQL);
    }
}
