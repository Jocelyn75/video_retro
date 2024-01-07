<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240107174609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(255) DEFAULT NULL, ADD prenom VARCHAR(255) DEFAULT NULL, ADD date_naiss DATETIME DEFAULT NULL, ADD adr_user VARCHAR(255) DEFAULT NULL, ADD complement_adr VARCHAR(255) DEFAULT NULL, ADD code_postal INT DEFAULT NULL, ADD ville VARCHAR(255) DEFAULT NULL, ADD tel_user INT DEFAULT NULL, ADD cagnotte DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP nom, DROP prenom, DROP date_naiss, DROP adr_user, DROP complement_adr, DROP code_postal, DROP ville, DROP tel_user, DROP cagnotte');
    }
}
