<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424162740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vie_dossier DROP FOREIGN KEY FK_Reference_dossier');
        $this->addSql('DROP INDEX FK_Reference_dossier ON vie_dossier');
        $this->addSql('ALTER TABLE vie_dossier ADD motif_rejet VARCHAR(200) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vie_dossier DROP motif_rejet');
        $this->addSql('ALTER TABLE vie_dossier ADD CONSTRAINT FK_Reference_dossier FOREIGN KEY (reference_dossier) REFERENCES dossier (reference_dossier) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX FK_Reference_dossier ON vie_dossier (reference_dossier)');
    }
}
