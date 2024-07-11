<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240425115535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE affectation_dossier (id_affectation INT AUTO_INCREMENT NOT NULL, reference_dossier VARCHAR(200) NOT NULL, id_affecteur INT NOT NULL, id_affecte INT NOT NULL, date_affectation DATETIME DEFAULT NULL, date_traitement DATETIME DEFAULT NULL, statut_traitement VARCHAR(200) DEFAULT NULL, statut_affectation VARCHAR(200) DEFAULT NULL, date_retrait DATETIME DEFAULT NULL, id_niveau INT NOT NULL, PRIMARY KEY(id_affectation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE affectation_dossier');
    }
}
