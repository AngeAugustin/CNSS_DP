<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424142040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dossier ADD acte_deces LONGBLOB DEFAULT NULL, ADD acte_mariage LONGBLOB DEFAULT NULL, ADD acte_naissance_survivant1 LONGBLOB DEFAULT NULL, ADD acte_naissance_survivant2 LONGBLOB DEFAULT NULL, ADD acte_naissance_survivant3 LONGBLOB DEFAULT NULL, ADD acte_naissance_survivant4 LONGBLOB DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dossier DROP acte_deces, DROP acte_mariage, DROP acte_naissance_survivant1, DROP acte_naissance_survivant2, DROP acte_naissance_survivant3, DROP acte_naissance_survivant4');
    }
}
