<?php

declare(strict_types=1);

namespace Acme\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250705054453 extends AbstractMigration
{
    private const ALPHABET = [
        'a','b','c','d','e','f','g','h','i',
        'j','k','l','m','n','o','p','q','r',
        's','t','u','v','w','x','y','z'
    ];
    public function getDescription(): string
    {
        return 'Create the initial Word entity';
    }

    /*
    * pneumonoultramicroscopicsilicovolcanoconiosis is known to be the longest word (45 characters) in a standard english dictionary
    */
    public function up(Schema $schema): void
    {
        $letterColumnsSql = '';
        foreach (self::ALPHABET as $letter) {
            $columnName = 'l_'.$letter;
            //set a default value for every letter column to 0, (it can be fixed in post!) - (max val allowed of 255)
            $letterColumnsSql .= sprintf("%s TINYINT UNSIGNED NOT NULL DEFAULT 0, ", $columnName);
            $letterColumnsSql .= sprintf("INDEX %s_letter_count_idx (`%s`),", $columnName, $columnName);
        }

        // We are not going to autoincrement the id here, as we need it to be sequential with no gaps, IDs are managed application side.

        $sql = "CREATE TABLE word (id INT NOT NULL, term VARCHAR(45) NOT NULL UNIQUE,";
        $sql .= $letterColumnsSql;
        $sql .= "PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB";
        
        $this->addSql($sql);

        // We will be searching this field quite a lot!
        $this->addSql(<<<'SQL'
            CREATE INDEX term_idx
            ON word(term);
        SQL);

        var_dump($this->getSql());
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TABLE word;
        SQL);
    }
}
