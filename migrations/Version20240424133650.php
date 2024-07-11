<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424133650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dossier (reference_dossier INT NOT NULL, numero_assure VARCHAR(200) NOT NULL, numero_pensionne VARCHAR(200) NOT NULL, npi VARCHAR(200) NOT NULL, nom_pensionne VARCHAR(200) NOT NULL, prenom_pensionne VARCHAR(200) NOT NULL, id_agence INT NOT NULL, statut_global_dossier VARCHAR(200) NOT NULL, adresse_pensionne VARCHAR(200) NOT NULL, telephone_pensionne VARCHAR(10) NOT NULL, PRIMARY KEY(reference_dossier)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE dossier');
    }
}
