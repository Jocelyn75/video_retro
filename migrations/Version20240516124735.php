<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516124735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adr_facturation_cmd ADD commandes_id INT DEFAULT NULL, ADD nom VARCHAR(255) DEFAULT NULL, ADD prenom VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE adr_facturation_cmd ADD CONSTRAINT FK_56BC8C88BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_56BC8C88BF5C2E6 ON adr_facturation_cmd (commandes_id)');
        $this->addSql('ALTER TABLE adr_facturation_user ADD nom VARCHAR(255) DEFAULT NULL, ADD prenom VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE adr_livraison_cmd ADD commandes_id INT DEFAULT NULL, ADD nom VARCHAR(255) DEFAULT NULL, ADD prenom VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE adr_livraison_cmd ADD CONSTRAINT FK_FEF0ACB8BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEF0ACB8BF5C2E6 ON adr_livraison_cmd (commandes_id)');
        $this->addSql('ALTER TABLE adr_livraison_user ADD nom VARCHAR(255) DEFAULT NULL, ADD prenom VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C20BEE75B');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C31E1ABFD');
        $this->addSql('DROP INDEX IDX_35D4282C20BEE75B ON commandes');
        $this->addSql('DROP INDEX IDX_35D4282C31E1ABFD ON commandes');
        $this->addSql('ALTER TABLE commandes DROP adr_facturation_cmd_id, DROP adr_livraison_cmd_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adr_facturation_cmd DROP FOREIGN KEY FK_56BC8C88BF5C2E6');
        $this->addSql('DROP INDEX UNIQ_56BC8C88BF5C2E6 ON adr_facturation_cmd');
        $this->addSql('ALTER TABLE adr_facturation_cmd DROP commandes_id, DROP nom, DROP prenom');
        $this->addSql('ALTER TABLE adr_facturation_user DROP nom, DROP prenom');
        $this->addSql('ALTER TABLE adr_livraison_cmd DROP FOREIGN KEY FK_FEF0ACB8BF5C2E6');
        $this->addSql('DROP INDEX UNIQ_FEF0ACB8BF5C2E6 ON adr_livraison_cmd');
        $this->addSql('ALTER TABLE adr_livraison_cmd DROP commandes_id, DROP nom, DROP prenom');
        $this->addSql('ALTER TABLE adr_livraison_user DROP nom, DROP prenom');
        $this->addSql('ALTER TABLE commandes ADD adr_facturation_cmd_id INT DEFAULT NULL, ADD adr_livraison_cmd_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C20BEE75B FOREIGN KEY (adr_facturation_cmd_id) REFERENCES adr_facturation_cmd (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C31E1ABFD FOREIGN KEY (adr_livraison_cmd_id) REFERENCES adr_livraison_cmd (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_35D4282C20BEE75B ON commandes (adr_facturation_cmd_id)');
        $this->addSql('CREATE INDEX IDX_35D4282C31E1ABFD ON commandes (adr_livraison_cmd_id)');
    }
}
