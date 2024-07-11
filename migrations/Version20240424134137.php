<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424134137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vie_dossier (id_vie_dossier INT AUTO_INCREMENT NOT NULL, reference_dossier VARCHAR(20) NOT NULL, id_niveau INT NOT NULL, id_acteur INT NOT NULL, statut_niveau_dossier VARCHAR(200) NOT NULL, date_enregistrement DATETIME NOT NULL, date_traitement DATETIME DEFAULT NULL, date_entree DATETIME DEFAULT NULL, PRIMARY KEY(id_vie_dossier)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE vie_dossier');
    }
}
