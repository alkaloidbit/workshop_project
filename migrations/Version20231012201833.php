<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231012201833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__answer AS SELECT id, situation_id, content, valid FROM answer');
        $this->addSql('DROP TABLE answer');
        $this->addSql('CREATE TABLE answer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, situation_id INTEGER DEFAULT NULL, content CLOB NOT NULL, valid BOOLEAN NOT NULL, CONSTRAINT FK_DADD4A253408E8AF FOREIGN KEY (situation_id) REFERENCES situation (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO answer (id, situation_id, content, valid) SELECT id, situation_id, content, valid FROM __temp__answer');
        $this->addSql('DROP TABLE __temp__answer');
        $this->addSql('CREATE INDEX IDX_DADD4A253408E8AF ON answer (situation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__answer AS SELECT id, situation_id, content, valid FROM answer');
        $this->addSql('DROP TABLE answer');
        $this->addSql('CREATE TABLE answer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, situation_id INTEGER NOT NULL, content CLOB NOT NULL, valid BOOLEAN NOT NULL, CONSTRAINT FK_DADD4A253408E8AF FOREIGN KEY (situation_id) REFERENCES situation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO answer (id, situation_id, content, valid) SELECT id, situation_id, content, valid FROM __temp__answer');
        $this->addSql('DROP TABLE __temp__answer');
        $this->addSql('CREATE INDEX IDX_DADD4A253408E8AF ON answer (situation_id)');
    }
}
