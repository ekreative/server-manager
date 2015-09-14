<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150911143547 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE site ADD COLUMN author INT(11) DEFAULT NULL');
        $this->addSql('CREATE TABLE user (
                      id INT NOT NULL,
                      username VARCHAR(255) DEFAULT NULL,
                      firstName VARCHAR(255) DEFAULT NULL,
                      lastName VARCHAR(255) DEFAULT NULL,
                      email VARCHAR(255) DEFAULT NULL,
                      isAdmin TINYINT(1) DEFAULT NULL
                      PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE site DROP author');
        $this->addSql('DROP TABLE user');

    }
}
