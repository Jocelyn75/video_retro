<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240511191300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adr_facturation_cmd ADD complement_adr VARCHAR(255) DEFAULT NULL, ADD code_postal INT DEFAULT NULL, ADD ville VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE adr_livraison_cmd ADD complement_adr VARCHAR(255) DEFAULT NULL, ADD code_postal INT DEFAULT NULL, ADD ville VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE adr_livraison_user ADD adresse VARCHAR(255) DEFAULT NULL, ADD complement_adr VARCHAR(255) DEFAULT NULL, ADD code_postal INT DEFAULT NULL, ADD ville VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adr_facturation_cmd DROP complement_adr, DROP code_postal, DROP ville');
        $this->addSql('ALTER TABLE adr_livraison_cmd DROP complement_adr, DROP code_postal, DROP ville');
        $this->addSql('ALTER TABLE adr_livraison_user DROP adresse, DROP complement_adr, DROP code_postal, DROP ville');
    }
}
