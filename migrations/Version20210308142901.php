<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210308142901 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object DROP FOREIGN KEY FK_14D43132BB3453DB');
        $this->addSql('DROP INDEX IDX_14D43132BB3453DB ON media_object');
        $this->addSql('ALTER TABLE media_object DROP work_id');
        $this->addSql('ALTER TABLE user DROP username, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE work CHANGE updated_at updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object ADD work_id INT NOT NULL');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D43132BB3453DB FOREIGN KEY (work_id) REFERENCES work (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_14D43132BB3453DB ON media_object (work_id)');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE work CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }
}
