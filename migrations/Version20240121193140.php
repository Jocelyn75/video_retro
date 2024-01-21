<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240121193140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adr_facturation_user DROP FOREIGN KEY FK_836269AFA76ED395');
        $this->addSql('DROP INDEX IDX_836269AFA76ED395 ON adr_facturation_user');
        $this->addSql('ALTER TABLE adr_facturation_user DROP user_id');
        $this->addSql('ALTER TABLE adr_livraison_user DROP FOREIGN KEY FK_1A61BCD7A76ED395');
        $this->addSql('DROP INDEX IDX_1A61BCD7A76ED395 ON adr_livraison_user');
        $this->addSql('ALTER TABLE adr_livraison_user DROP user_id');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CA76ED395');
        $this->addSql('DROP INDEX IDX_35D4282CA76ED395 ON commandes');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adr_facturation_user ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adr_facturation_user ADD CONSTRAINT FK_836269AFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_836269AFA76ED395 ON adr_facturation_user (user_id)');
        $this->addSql('ALTER TABLE adr_livraison_user ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adr_livraison_user ADD CONSTRAINT FK_1A61BCD7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1A61BCD7A76ED395 ON adr_livraison_user (user_id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_35D4282CA76ED395 ON commandes (user_id)');
    }
}
