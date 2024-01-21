<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240121200636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commandes ADD adr_facturation_cmd_id INT DEFAULT NULL, ADD adr_livraison_cmd_id INT DEFAULT NULL, ADD livreur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C20BEE75B FOREIGN KEY (adr_facturation_cmd_id) REFERENCES adr_facturation_cmd (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C31E1ABFD FOREIGN KEY (adr_livraison_cmd_id) REFERENCES adr_livraison_cmd (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CF8646701 FOREIGN KEY (livreur_id) REFERENCES livreur (id)');
        $this->addSql('CREATE INDEX IDX_35D4282C20BEE75B ON commandes (adr_facturation_cmd_id)');
        $this->addSql('CREATE INDEX IDX_35D4282C31E1ABFD ON commandes (adr_livraison_cmd_id)');
        $this->addSql('CREATE INDEX IDX_35D4282CF8646701 ON commandes (livreur_id)');
        $this->addSql('ALTER TABLE details_commandes ADD CONSTRAINT FK_4FD424F78BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id)');
        $this->addSql('CREATE INDEX IDX_4FD424F78BF5C2E6 ON details_commandes (commandes_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE details_commandes DROP FOREIGN KEY FK_4FD424F78BF5C2E6');
        $this->addSql('DROP INDEX IDX_4FD424F78BF5C2E6 ON details_commandes');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C20BEE75B');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C31E1ABFD');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CF8646701');
        $this->addSql('DROP INDEX IDX_35D4282C20BEE75B ON commandes');
        $this->addSql('DROP INDEX IDX_35D4282C31E1ABFD ON commandes');
        $this->addSql('DROP INDEX IDX_35D4282CF8646701 ON commandes');
        $this->addSql('ALTER TABLE commandes DROP adr_facturation_cmd_id, DROP adr_livraison_cmd_id, DROP livreur_id');
    }
}
