<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210206103826 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD work_id INT NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D43132BB3453DB FOREIGN KEY (work_id) REFERENCES work (id)');
        $this->addSql('CREATE INDEX IDX_14D43132BB3453DB ON media_object (work_id)');
        $this->addSql('ALTER TABLE work CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE media_object DROP FOREIGN KEY FK_14D43132BB3453DB');
        $this->addSql('DROP INDEX IDX_14D43132BB3453DB ON media_object');
        $this->addSql('ALTER TABLE media_object DROP work_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE work CHANGE updated_at updated_at DATETIME NOT NULL');
    }
}
