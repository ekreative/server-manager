<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150616120340 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE domain (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, server_id INT DEFAULT NULL, management_login_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_A7A91E0BF6BD1646 (site_id), INDEX IDX_A7A91E0B1844E6B7 (server_id), UNIQUE INDEX UNIQ_A7A91E0B6ACA2D99 (management_login_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE framework (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, currentVersion VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login (id INT AUTO_INCREMENT NOT NULL, site_admin_id INT DEFAULT NULL, site_database_id INT DEFAULT NULL, server_root_id INT DEFAULT NULL, server_hosting_id INT DEFAULT NULL, server_user_id INT DEFAULT NULL, domain_management_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, ssh_key LONGTEXT DEFAULT NULL, hostname VARCHAR(255) DEFAULT NULL, port INT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_AA08CB1068663DCA (site_admin_id), UNIQUE INDEX UNIQ_AA08CB101B84366B (site_database_id), UNIQUE INDEX UNIQ_AA08CB10B78733A (server_root_id), UNIQUE INDEX UNIQ_AA08CB107C5FD44D (server_hosting_id), UNIQUE INDEX UNIQ_AA08CB10D510C829 (server_user_id), UNIQUE INDEX UNIQ_AA08CB101345EDE5 (domain_management_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, root_login_id INT DEFAULT NULL, hosting_login_id INT DEFAULT NULL, user_login_id INT DEFAULT NULL, ip VARCHAR(255) NOT NULL, os VARCHAR(255) NOT NULL, auto_updates TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_5A6DD5F627EF8ABB (root_login_id), UNIQUE INDEX UNIQ_5A6DD5F64C051A52 (hosting_login_id), UNIQUE INDEX UNIQ_5A6DD5F6BC3F045D (user_login_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server_site (server_id INT NOT NULL, site_id INT NOT NULL, INDEX IDX_E2C378041844E6B7 (server_id), INDEX IDX_E2C37804F6BD1646 (site_id), PRIMARY KEY(server_id, site_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, admin_login_id INT DEFAULT NULL, database_login_id INT DEFAULT NULL, framework_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, live TINYINT(1) NOT NULL, framework_version VARCHAR(255) NOT NULL, INDEX IDX_694309E4166D1F9C (project_id), UNIQUE INDEX UNIQ_694309E4C812E75B (admin_login_id), UNIQUE INDEX UNIQ_694309E4DAF449DD (database_login_id), INDEX IDX_694309E437AECF72 (framework_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE domain ADD CONSTRAINT FK_A7A91E0BF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE domain ADD CONSTRAINT FK_A7A91E0B1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE domain ADD CONSTRAINT FK_A7A91E0B6ACA2D99 FOREIGN KEY (management_login_id) REFERENCES login (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE login ADD CONSTRAINT FK_AA08CB1068663DCA FOREIGN KEY (site_admin_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE login ADD CONSTRAINT FK_AA08CB101B84366B FOREIGN KEY (site_database_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE login ADD CONSTRAINT FK_AA08CB10B78733A FOREIGN KEY (server_root_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE login ADD CONSTRAINT FK_AA08CB107C5FD44D FOREIGN KEY (server_hosting_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE login ADD CONSTRAINT FK_AA08CB10D510C829 FOREIGN KEY (server_user_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE login ADD CONSTRAINT FK_AA08CB101345EDE5 FOREIGN KEY (domain_management_id) REFERENCES domain (id)');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F627EF8ABB FOREIGN KEY (root_login_id) REFERENCES login (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F64C051A52 FOREIGN KEY (hosting_login_id) REFERENCES login (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F6BC3F045D FOREIGN KEY (user_login_id) REFERENCES login (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE server_site ADD CONSTRAINT FK_E2C378041844E6B7 FOREIGN KEY (server_id) REFERENCES server (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE server_site ADD CONSTRAINT FK_E2C37804F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E4166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E4C812E75B FOREIGN KEY (admin_login_id) REFERENCES login (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E4DAF449DD FOREIGN KEY (database_login_id) REFERENCES login (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E437AECF72 FOREIGN KEY (framework_id) REFERENCES framework (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE login DROP FOREIGN KEY FK_AA08CB101345EDE5');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E437AECF72');
        $this->addSql('ALTER TABLE domain DROP FOREIGN KEY FK_A7A91E0B6ACA2D99');
        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F627EF8ABB');
        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F64C051A52');
        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F6BC3F045D');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E4C812E75B');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E4DAF449DD');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E4166D1F9C');
        $this->addSql('ALTER TABLE domain DROP FOREIGN KEY FK_A7A91E0B1844E6B7');
        $this->addSql('ALTER TABLE login DROP FOREIGN KEY FK_AA08CB10B78733A');
        $this->addSql('ALTER TABLE login DROP FOREIGN KEY FK_AA08CB107C5FD44D');
        $this->addSql('ALTER TABLE login DROP FOREIGN KEY FK_AA08CB10D510C829');
        $this->addSql('ALTER TABLE server_site DROP FOREIGN KEY FK_E2C378041844E6B7');
        $this->addSql('ALTER TABLE domain DROP FOREIGN KEY FK_A7A91E0BF6BD1646');
        $this->addSql('ALTER TABLE login DROP FOREIGN KEY FK_AA08CB1068663DCA');
        $this->addSql('ALTER TABLE login DROP FOREIGN KEY FK_AA08CB101B84366B');
        $this->addSql('ALTER TABLE server_site DROP FOREIGN KEY FK_E2C37804F6BD1646');
        $this->addSql('DROP TABLE domain');
        $this->addSql('DROP TABLE framework');
        $this->addSql('DROP TABLE login');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE server');
        $this->addSql('DROP TABLE server_site');
        $this->addSql('DROP TABLE site');
    }
}
