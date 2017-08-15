<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170812144129 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hosting (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, editor_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E9168FDAF675F31B (author_id), INDEX IDX_E9168FDA6995AC4C (editor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE health_check (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, last_sync_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_674D87D9F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, editor_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, skype VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C7440455F675F31B (author_id), INDEX IDX_C74404556995AC4C (editor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hosting ADD CONSTRAINT FK_E9168FDAF675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE hosting ADD CONSTRAINT FK_E9168FDA6995AC4C FOREIGN KEY (editor_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE health_check ADD CONSTRAINT FK_674D87D9F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455F675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404556995AC4C FOREIGN KEY (editor_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('DROP TABLE proxy_host');
        $this->addSql('ALTER TABLE site ADD responsible_manager_id INT DEFAULT NULL, ADD author_id INT DEFAULT NULL, ADD sla VARCHAR(255) NOT NULL, ADD notes LONGTEXT DEFAULT NULL, ADD site_completed_at DATETIME DEFAULT NULL, ADD sla_end_at DATETIME DEFAULT NULL, ADD status VARCHAR(60) NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, CHANGE author developer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E464DD9267 FOREIGN KEY (developer_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E4439E0BE4 FOREIGN KEY (responsible_manager_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E4F675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_694309E464DD9267 ON site (developer_id)');
        $this->addSql('CREATE INDEX IDX_694309E4439E0BE4 ON site (responsible_manager_id)');
        $this->addSql('CREATE INDEX IDX_694309E4F675F31B ON site (author_id)');
        $this->addSql('ALTER TABLE login DROP databaseName');
        $this->addSql('ALTER TABLE project ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE19EB6921 ON project (client_id)');
        $this->addSql('ALTER TABLE framework ADD key_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL, ADD last_login_at DATETIME DEFAULT NULL, ADD api_key VARCHAR(255) NOT NULL, DROP firstName, DROP lastName, CHANGE username username VARCHAR(255) NOT NULL, CHANGE isadmin status TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE server ADD hosting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F6AE9044EA FOREIGN KEY (hosting_id) REFERENCES hosting (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_5A6DD5F6AE9044EA ON server (hosting_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F6AE9044EA');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE19EB6921');
        $this->addSql('CREATE TABLE proxy_host (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE hosting');
        $this->addSql('DROP TABLE health_check');
        $this->addSql('DROP TABLE client');
        $this->addSql('ALTER TABLE framework DROP key_name');
        $this->addSql('ALTER TABLE login ADD databaseName VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('DROP INDEX IDX_2FB3D0EE19EB6921 ON project');
        $this->addSql('ALTER TABLE project DROP client_id');
        $this->addSql('DROP INDEX IDX_5A6DD5F6AE9044EA ON server');
        $this->addSql('ALTER TABLE server DROP hosting_id');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E464DD9267');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E4439E0BE4');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E4F675F31B');
        $this->addSql('DROP INDEX IDX_694309E464DD9267 ON site');
        $this->addSql('DROP INDEX IDX_694309E4439E0BE4 ON site');
        $this->addSql('DROP INDEX IDX_694309E4F675F31B ON site');
        $this->addSql('ALTER TABLE site ADD author INT DEFAULT NULL, DROP developer_id, DROP responsible_manager_id, DROP author_id, DROP sla, DROP notes, DROP site_completed_at, DROP sla_end_at, DROP status, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE user ADD firstName VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD lastName VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, DROP first_name, DROP last_name, DROP created_at, DROP last_login_at, DROP api_key, CHANGE username username VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE status isAdmin TINYINT(1) DEFAULT NULL');
    }
}
