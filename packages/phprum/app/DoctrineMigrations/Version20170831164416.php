<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170831164416 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE backlog_items (id INTEGER NOT NULL, creator_id INTEGER DEFAULT NULL, sprint_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, estimate SMALLINT DEFAULT NULL, priority SMALLINT DEFAULT NULL, status VARCHAR(20) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_157410F061220EA6 ON backlog_items (creator_id)');
        $this->addSql('CREATE INDEX IDX_157410F08C24077B ON backlog_items (sprint_id)');
        $this->addSql('CREATE TABLE sprints (id INTEGER NOT NULL, creator_id INTEGER DEFAULT NULL, duration VARCHAR(30) NOT NULL, start_date DATETIME DEFAULT NULL, is_started BOOLEAN NOT NULL, closed_on DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4EE4697161220EA6 ON sprints (creator_id)');
        $this->addSql('CREATE TABLE backlog_sub_items (id INTEGER NOT NULL, creator_id INTEGER DEFAULT NULL, sprint_id INTEGER DEFAULT NULL, parent_item_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, status VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7D95E6C461220EA6 ON backlog_sub_items (creator_id)');
        $this->addSql('CREATE INDEX IDX_7D95E6C48C24077B ON backlog_sub_items (sprint_id)');
        $this->addSql('CREATE INDEX IDX_7D95E6C460272618 ON backlog_sub_items (parent_item_id)');
        $this->addSql('CREATE TABLE fos_user (id INTEGER NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles CLOB NOT NULL --(DC2Type:array)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A647992FC23A8 ON fos_user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479A0D96FBF ON fos_user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479C05FB297 ON fos_user (confirmation_token)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE backlog_items');
        $this->addSql('DROP TABLE sprints');
        $this->addSql('DROP TABLE backlog_sub_items');
        $this->addSql('DROP TABLE fos_user');
    }
}
