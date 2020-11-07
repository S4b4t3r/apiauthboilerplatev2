<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201107154932 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE work ADD assessment_id INT NOT NULL');
        $this->addSql('ALTER TABLE work ADD CONSTRAINT FK_534E6880DD3DD5F1 FOREIGN KEY (assessment_id) REFERENCES assessment (id)');
        $this->addSql('CREATE INDEX IDX_534E6880DD3DD5F1 ON work (assessment_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE work DROP FOREIGN KEY FK_534E6880DD3DD5F1');
        $this->addSql('DROP INDEX IDX_534E6880DD3DD5F1 ON work');
        $this->addSql('ALTER TABLE work DROP assessment_id');
    }
}
