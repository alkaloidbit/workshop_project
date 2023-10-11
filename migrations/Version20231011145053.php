<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231011145053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, situation_id INTEGER NOT NULL, content CLOB NOT NULL, valid BOOLEAN NOT NULL, CONSTRAINT FK_DADD4A253408E8AF FOREIGN KEY (situation_id) REFERENCES situation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_DADD4A253408E8AF ON answer (situation_id)');
        $this->addSql('CREATE TABLE proposition (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, situation_id INTEGER NOT NULL, answer_id INTEGER NOT NULL, content CLOB NOT NULL, CONSTRAINT FK_C7CDC3533408E8AF FOREIGN KEY (situation_id) REFERENCES situation (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C7CDC353AA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C7CDC3533408E8AF ON proposition (situation_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7CDC353AA334807 ON proposition (answer_id)');
        $this->addSql('CREATE TABLE situation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, context CLOB NOT NULL, question CLOB NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE proposition');
        $this->addSql('DROP TABLE situation');
    }
}
