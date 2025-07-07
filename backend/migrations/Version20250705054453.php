<?php

declare(strict_types=1);

namespace Acme\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250705054453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the initial Word entity';
    }

    /*
    * pneumonoultramicroscopicsilicovolcanoconiosis is known to be the longest word (45 characters) in a standard english dictionary
    */
    public function up(Schema $schema): void
    {
        // We are not going to autoincrement the id here, as we need it to be sequential with no gaps, IDs are managed application side.
        $this->addSql(<<<'SQL'
            CREATE TABLE word (
                id INT NOT NULL, 
                term VARCHAR(45) NOT NULL UNIQUE, 
                PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);

        // We will be searching this field quite a lot!
        $this->addSql(<<<'SQL'
            CREATE INDEX term_idx
            ON word(term);
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TABLE word;
        SQL);
    }
}
