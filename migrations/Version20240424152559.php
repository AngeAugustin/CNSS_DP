<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424152559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acteur DROP FOREIGN KEY FK_Id_agence');
        $this->addSql('DROP INDEX FK_Id_agence ON acteur');
        $this->addSql('ALTER TABLE dossier ADD type_dossier VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE vie_dossier DROP FOREIGN KEY FK_Reference_dossier');
        $this->addSql('ALTER TABLE vie_dossier DROP FOREIGN KEY FK_Id_acteur');
        $this->addSql('ALTER TABLE vie_dossier DROP FOREIGN KEY FK_Id_niveau');
        $this->addSql('DROP INDEX FK_Id_niveau ON vie_dossier');
        $this->addSql('DROP INDEX FK_Id_acteur ON vie_dossier');
        $this->addSql('DROP INDEX FK_Reference_dossier ON vie_dossier');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acteur ADD CONSTRAINT FK_Id_agence FOREIGN KEY (id_agence) REFERENCES agence (id_agence)');
        $this->addSql('CREATE INDEX FK_Id_agence ON acteur (id_agence)');
        $this->addSql('ALTER TABLE dossier DROP type_dossier');
        $this->addSql('ALTER TABLE vie_dossier ADD CONSTRAINT FK_Reference_dossier FOREIGN KEY (reference_dossier) REFERENCES dossier (reference_dossier)');
        $this->addSql('ALTER TABLE vie_dossier ADD CONSTRAINT FK_Id_acteur FOREIGN KEY (id_acteur) REFERENCES acteur (id_acteur)');
        $this->addSql('ALTER TABLE vie_dossier ADD CONSTRAINT FK_Id_niveau FOREIGN KEY (id_niveau) REFERENCES niveau (id_niveau)');
        $this->addSql('CREATE INDEX FK_Id_niveau ON vie_dossier (id_niveau)');
        $this->addSql('CREATE INDEX FK_Id_acteur ON vie_dossier (id_acteur)');
        $this->addSql('CREATE INDEX FK_Reference_dossier ON vie_dossier (reference_dossier)');
    }
}
