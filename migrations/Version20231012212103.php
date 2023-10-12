<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231012212103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__answer AS SELECT id, content, valid FROM answer');
        $this->addSql('DROP TABLE answer');
        $this->addSql('CREATE TABLE answer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, situation_id INTEGER DEFAULT NULL, content CLOB NOT NULL, valid BOOLEAN NOT NULL, CONSTRAINT FK_DADD4A253408E8AF FOREIGN KEY (situation_id) REFERENCES situation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO answer (id, content, valid) SELECT id, content, valid FROM __temp__answer');
        $this->addSql('DROP TABLE __temp__answer');
        $this->addSql('CREATE INDEX IDX_DADD4A253408E8AF ON answer (situation_id)');
        $this->addSql('ALTER TABLE situation ADD COLUMN explanation CLOB NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__answer AS SELECT id, content, valid FROM answer');
        $this->addSql('DROP TABLE answer');
        $this->addSql('CREATE TABLE answer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, content CLOB NOT NULL, valid BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO answer (id, content, valid) SELECT id, content, valid FROM __temp__answer');
        $this->addSql('DROP TABLE __temp__answer');
        $this->addSql('CREATE TEMPORARY TABLE __temp__situation AS SELECT id, question FROM situation');
        $this->addSql('DROP TABLE situation');
        $this->addSql('CREATE TABLE situation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, question CLOB NOT NULL)');
        $this->addSql('INSERT INTO situation (id, question) SELECT id, question FROM __temp__situation');
        $this->addSql('DROP TABLE __temp__situation');
    }
}
