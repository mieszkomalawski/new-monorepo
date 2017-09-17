<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170917194612 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE item_blocked_by (item_id INTEGER NOT NULL, blocking_item_id INTEGER NOT NULL, PRIMARY KEY(item_id, blocking_item_id))');
        $this->addSql('CREATE INDEX IDX_1CBCCC2E126F525E ON item_blocked_by (item_id)');
        $this->addSql('CREATE INDEX IDX_1CBCCC2EBA9156A6 ON item_blocked_by (blocking_item_id)');
        $this->addSql('DROP INDEX IDX_157410F08C24077B');
        $this->addSql('DROP INDEX IDX_157410F061220EA6');
        $this->addSql('DROP INDEX IDX_157410F06B71E00E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__backlog_items AS SELECT id, creator_id, sprint_id, epic_id, name, created_at, estimate, priority, status, image_name FROM backlog_items');
        $this->addSql('DROP TABLE backlog_items');
        $this->addSql('CREATE TABLE backlog_items (id INTEGER NOT NULL, creator_id INTEGER DEFAULT NULL, sprint_id INTEGER DEFAULT NULL, epic_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL, estimate SMALLINT DEFAULT NULL, priority SMALLINT DEFAULT NULL, status VARCHAR(20) DEFAULT NULL COLLATE BINARY, image_name VARCHAR(255) DEFAULT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_157410F061220EA6 FOREIGN KEY (creator_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_157410F08C24077B FOREIGN KEY (sprint_id) REFERENCES sprints (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_157410F06B71E00E FOREIGN KEY (epic_id) REFERENCES epics (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO backlog_items (id, creator_id, sprint_id, epic_id, name, created_at, estimate, priority, status, image_name) SELECT id, creator_id, sprint_id, epic_id, name, created_at, estimate, priority, status, image_name FROM __temp__backlog_items');
        $this->addSql('DROP TABLE __temp__backlog_items');
        $this->addSql('CREATE INDEX IDX_157410F08C24077B ON backlog_items (sprint_id)');
        $this->addSql('CREATE INDEX IDX_157410F061220EA6 ON backlog_items (creator_id)');
        $this->addSql('CREATE INDEX IDX_157410F06B71E00E ON backlog_items (epic_id)');
        $this->addSql('DROP INDEX IDX_332877DE33B92F39');
        $this->addSql('DROP INDEX IDX_332877DE126F525E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__item_labels AS SELECT item_id, label_id FROM item_labels');
        $this->addSql('DROP TABLE item_labels');
        $this->addSql('CREATE TABLE item_labels (item_id INTEGER NOT NULL, label_id INTEGER NOT NULL, PRIMARY KEY(item_id, label_id), CONSTRAINT FK_332877DE126F525E FOREIGN KEY (item_id) REFERENCES backlog_items (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_332877DE33B92F39 FOREIGN KEY (label_id) REFERENCES labels (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO item_labels (item_id, label_id) SELECT item_id, label_id FROM __temp__item_labels');
        $this->addSql('DROP TABLE __temp__item_labels');
        $this->addSql('CREATE INDEX IDX_332877DE33B92F39 ON item_labels (label_id)');
        $this->addSql('CREATE INDEX IDX_332877DE126F525E ON item_labels (item_id)');
        $this->addSql('DROP INDEX IDX_3C1547F161220EA6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__epics AS SELECT id, creator_id, name, color FROM epics');
        $this->addSql('DROP TABLE epics');
        $this->addSql('CREATE TABLE epics (id INTEGER NOT NULL, creator_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, color VARCHAR(255) DEFAULT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_3C1547F161220EA6 FOREIGN KEY (creator_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO epics (id, creator_id, name, color) SELECT id, creator_id, name, color FROM __temp__epics');
        $this->addSql('DROP TABLE __temp__epics');
        $this->addSql('CREATE INDEX IDX_3C1547F161220EA6 ON epics (creator_id)');
        $this->addSql('DROP INDEX IDX_4EE4697161220EA6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sprints AS SELECT id, creator_id, duration, start_date, is_started, closed_on FROM sprints');
        $this->addSql('DROP TABLE sprints');
        $this->addSql('CREATE TABLE sprints (id INTEGER NOT NULL, creator_id INTEGER DEFAULT NULL, duration VARCHAR(30) NOT NULL COLLATE BINARY, start_date DATETIME DEFAULT NULL, is_started BOOLEAN NOT NULL, closed_on DATETIME DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_4EE4697161220EA6 FOREIGN KEY (creator_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sprints (id, creator_id, duration, start_date, is_started, closed_on) SELECT id, creator_id, duration, start_date, is_started, closed_on FROM __temp__sprints');
        $this->addSql('DROP TABLE __temp__sprints');
        $this->addSql('CREATE INDEX IDX_4EE4697161220EA6 ON sprints (creator_id)');
        $this->addSql('DROP INDEX IDX_7D95E6C460272618');
        $this->addSql('DROP INDEX IDX_7D95E6C461220EA6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__backlog_sub_items AS SELECT id, creator_id, parent_item_id, name, created_at, status FROM backlog_sub_items');
        $this->addSql('DROP TABLE backlog_sub_items');
        $this->addSql('CREATE TABLE backlog_sub_items (id INTEGER NOT NULL, creator_id INTEGER DEFAULT NULL, parent_item_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL, status VARCHAR(20) DEFAULT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_7D95E6C461220EA6 FOREIGN KEY (creator_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_7D95E6C460272618 FOREIGN KEY (parent_item_id) REFERENCES backlog_items (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO backlog_sub_items (id, creator_id, parent_item_id, name, created_at, status) SELECT id, creator_id, parent_item_id, name, created_at, status FROM __temp__backlog_sub_items');
        $this->addSql('DROP TABLE __temp__backlog_sub_items');
        $this->addSql('CREATE INDEX IDX_7D95E6C460272618 ON backlog_sub_items (parent_item_id)');
        $this->addSql('CREATE INDEX IDX_7D95E6C461220EA6 ON backlog_sub_items (creator_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE item_blocked_by');
        $this->addSql('DROP INDEX IDX_157410F061220EA6');
        $this->addSql('DROP INDEX IDX_157410F08C24077B');
        $this->addSql('DROP INDEX IDX_157410F06B71E00E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__backlog_items AS SELECT id, creator_id, sprint_id, epic_id, name, created_at, estimate, priority, status, image_name FROM backlog_items');
        $this->addSql('DROP TABLE backlog_items');
        $this->addSql('CREATE TABLE backlog_items (id INTEGER NOT NULL, creator_id INTEGER DEFAULT NULL, sprint_id INTEGER DEFAULT NULL, epic_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, estimate SMALLINT DEFAULT NULL, priority SMALLINT DEFAULT NULL, status VARCHAR(20) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO backlog_items (id, creator_id, sprint_id, epic_id, name, created_at, estimate, priority, status, image_name) SELECT id, creator_id, sprint_id, epic_id, name, created_at, estimate, priority, status, image_name FROM __temp__backlog_items');
        $this->addSql('DROP TABLE __temp__backlog_items');
        $this->addSql('CREATE INDEX IDX_157410F061220EA6 ON backlog_items (creator_id)');
        $this->addSql('CREATE INDEX IDX_157410F08C24077B ON backlog_items (sprint_id)');
        $this->addSql('CREATE INDEX IDX_157410F06B71E00E ON backlog_items (epic_id)');
        $this->addSql('DROP INDEX IDX_7D95E6C461220EA6');
        $this->addSql('DROP INDEX IDX_7D95E6C460272618');
        $this->addSql('CREATE TEMPORARY TABLE __temp__backlog_sub_items AS SELECT id, creator_id, parent_item_id, name, created_at, status FROM backlog_sub_items');
        $this->addSql('DROP TABLE backlog_sub_items');
        $this->addSql('CREATE TABLE backlog_sub_items (id INTEGER NOT NULL, creator_id INTEGER DEFAULT NULL, parent_item_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, status VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO backlog_sub_items (id, creator_id, parent_item_id, name, created_at, status) SELECT id, creator_id, parent_item_id, name, created_at, status FROM __temp__backlog_sub_items');
        $this->addSql('DROP TABLE __temp__backlog_sub_items');
        $this->addSql('CREATE INDEX IDX_7D95E6C461220EA6 ON backlog_sub_items (creator_id)');
        $this->addSql('CREATE INDEX IDX_7D95E6C460272618 ON backlog_sub_items (parent_item_id)');
        $this->addSql('DROP INDEX IDX_3C1547F161220EA6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__epics AS SELECT id, creator_id, name, color FROM epics');
        $this->addSql('DROP TABLE epics');
        $this->addSql('CREATE TABLE epics (id INTEGER NOT NULL, creator_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO epics (id, creator_id, name, color) SELECT id, creator_id, name, color FROM __temp__epics');
        $this->addSql('DROP TABLE __temp__epics');
        $this->addSql('CREATE INDEX IDX_3C1547F161220EA6 ON epics (creator_id)');
        $this->addSql('DROP INDEX IDX_332877DE126F525E');
        $this->addSql('DROP INDEX IDX_332877DE33B92F39');
        $this->addSql('CREATE TEMPORARY TABLE __temp__item_labels AS SELECT item_id, label_id FROM item_labels');
        $this->addSql('DROP TABLE item_labels');
        $this->addSql('CREATE TABLE item_labels (item_id INTEGER NOT NULL, label_id INTEGER NOT NULL, PRIMARY KEY(item_id, label_id))');
        $this->addSql('INSERT INTO item_labels (item_id, label_id) SELECT item_id, label_id FROM __temp__item_labels');
        $this->addSql('DROP TABLE __temp__item_labels');
        $this->addSql('CREATE INDEX IDX_332877DE126F525E ON item_labels (item_id)');
        $this->addSql('CREATE INDEX IDX_332877DE33B92F39 ON item_labels (label_id)');
        $this->addSql('DROP INDEX IDX_4EE4697161220EA6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sprints AS SELECT id, creator_id, duration, start_date, is_started, closed_on FROM sprints');
        $this->addSql('DROP TABLE sprints');
        $this->addSql('CREATE TABLE sprints (id INTEGER NOT NULL, creator_id INTEGER DEFAULT NULL, duration VARCHAR(30) NOT NULL, start_date DATETIME DEFAULT NULL, is_started BOOLEAN NOT NULL, closed_on DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO sprints (id, creator_id, duration, start_date, is_started, closed_on) SELECT id, creator_id, duration, start_date, is_started, closed_on FROM __temp__sprints');
        $this->addSql('DROP TABLE __temp__sprints');
        $this->addSql('CREATE INDEX IDX_4EE4697161220EA6 ON sprints (creator_id)');
    }
}
