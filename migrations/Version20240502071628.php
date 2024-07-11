<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240502071628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bordereau ADD id_agence_expedition INT NOT NULL, ADD id_agence_destination INT NOT NULL');
        $this->addSql('ALTER TABLE vie_dossier DROP FOREIGN KEY FK_Reference_dossier');
        $this->addSql('DROP INDEX FK_Reference_dossier ON vie_dossier');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bordereau DROP id_agence_expedition, DROP id_agence_destination');
        $this->addSql('ALTER TABLE vie_dossier ADD CONSTRAINT FK_Reference_dossier FOREIGN KEY (reference_dossier) REFERENCES dossier (reference_dossier) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX FK_Reference_dossier ON vie_dossier (reference_dossier)');
    }
}
