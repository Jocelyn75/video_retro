<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513112047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_adr_livraison_user DROP FOREIGN KEY FK_B6255A2C9C4256E5');
        $this->addSql('ALTER TABLE user_adr_livraison_user DROP FOREIGN KEY FK_B6255A2CA76ED395');
        $this->addSql('DROP TABLE user_adr_livraison_user');
        $this->addSql('ALTER TABLE adr_facturation_user ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adr_livraison_user ADD CONSTRAINT FK_1A61BCD7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1A61BCD7A76ED395 ON adr_livraison_user (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_adr_livraison_user (user_id INT NOT NULL, adr_livraison_user_id INT NOT NULL, INDEX IDX_B6255A2C9C4256E5 (adr_livraison_user_id), INDEX IDX_B6255A2CA76ED395 (user_id), PRIMARY KEY(user_id, adr_livraison_user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_adr_livraison_user ADD CONSTRAINT FK_B6255A2C9C4256E5 FOREIGN KEY (adr_livraison_user_id) REFERENCES adr_livraison_user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_adr_livraison_user ADD CONSTRAINT FK_B6255A2CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adr_livraison_user DROP FOREIGN KEY FK_1A61BCD7A76ED395');
        $this->addSql('DROP INDEX IDX_1A61BCD7A76ED395 ON adr_livraison_user');
        $this->addSql('ALTER TABLE adr_facturation_user DROP user_id');
    }
}
