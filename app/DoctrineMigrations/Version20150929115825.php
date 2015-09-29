<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150929115825 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE domain ADD author_id INT DEFAULT NULL, ADD editor_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE domain ADD CONSTRAINT FK_A7A91E0BF675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE domain ADD CONSTRAINT FK_A7A91E0B6995AC4C FOREIGN KEY (editor_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_A7A91E0BF675F31B ON domain (author_id)');
        $this->addSql('CREATE INDEX IDX_A7A91E0B6995AC4C ON domain (editor_id)');
        $this->addSql('ALTER TABLE framework ADD author_id INT DEFAULT NULL, ADD editor_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE framework ADD CONSTRAINT FK_9D766E19F675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE framework ADD CONSTRAINT FK_9D766E196995AC4C FOREIGN KEY (editor_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_9D766E19F675F31B ON framework (author_id)');
        $this->addSql('CREATE INDEX IDX_9D766E196995AC4C ON framework (editor_id)');
        $this->addSql('ALTER TABLE login ADD author_id INT DEFAULT NULL, ADD editor_id INT DEFAULT NULL, ADD database_name VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE login ADD CONSTRAINT FK_AA08CB10F675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE login ADD CONSTRAINT FK_AA08CB106995AC4C FOREIGN KEY (editor_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_AA08CB10F675F31B ON login (author_id)');
        $this->addSql('CREATE INDEX IDX_AA08CB106995AC4C ON login (editor_id)');
        $this->addSql('ALTER TABLE project ADD author_id INT DEFAULT NULL, ADD editor_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEF675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE6995AC4C FOREIGN KEY (editor_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEF675F31B ON project (author_id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE6995AC4C ON project (editor_id)');
        $this->addSql('ALTER TABLE server ADD author_id INT DEFAULT NULL, ADD editor_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F6F675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F66995AC4C FOREIGN KEY (editor_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_5A6DD5F6F675F31B ON server (author_id)');
        $this->addSql('CREATE INDEX IDX_5A6DD5F66995AC4C ON server (editor_id)');
        $this->addSql('ALTER TABLE site ADD editor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E46995AC4C FOREIGN KEY (editor_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_694309E46995AC4C ON site (editor_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE domain DROP FOREIGN KEY FK_A7A91E0BF675F31B');
        $this->addSql('ALTER TABLE domain DROP FOREIGN KEY FK_A7A91E0B6995AC4C');
        $this->addSql('DROP INDEX IDX_A7A91E0BF675F31B ON domain');
        $this->addSql('DROP INDEX IDX_A7A91E0B6995AC4C ON domain');
        $this->addSql('ALTER TABLE domain DROP author_id, DROP editor_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE framework DROP FOREIGN KEY FK_9D766E19F675F31B');
        $this->addSql('ALTER TABLE framework DROP FOREIGN KEY FK_9D766E196995AC4C');
        $this->addSql('DROP INDEX IDX_9D766E19F675F31B ON framework');
        $this->addSql('DROP INDEX IDX_9D766E196995AC4C ON framework');
        $this->addSql('ALTER TABLE framework DROP author_id, DROP editor_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE login DROP FOREIGN KEY FK_AA08CB10F675F31B');
        $this->addSql('ALTER TABLE login DROP FOREIGN KEY FK_AA08CB106995AC4C');
        $this->addSql('DROP INDEX IDX_AA08CB10F675F31B ON login');
        $this->addSql('DROP INDEX IDX_AA08CB106995AC4C ON login');
        $this->addSql('ALTER TABLE login DROP author_id, DROP editor_id, DROP database_name, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEF675F31B');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE6995AC4C');
        $this->addSql('DROP INDEX IDX_2FB3D0EEF675F31B ON project');
        $this->addSql('DROP INDEX IDX_2FB3D0EE6995AC4C ON project');
        $this->addSql('ALTER TABLE project DROP author_id, DROP editor_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F6F675F31B');
        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F66995AC4C');
        $this->addSql('DROP INDEX IDX_5A6DD5F6F675F31B ON server');
        $this->addSql('DROP INDEX IDX_5A6DD5F66995AC4C ON server');
        $this->addSql('ALTER TABLE server DROP author_id, DROP editor_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E46995AC4C');
        $this->addSql('DROP INDEX IDX_694309E46995AC4C ON site');
        $this->addSql('ALTER TABLE site DROP editor_id');
    }
}
