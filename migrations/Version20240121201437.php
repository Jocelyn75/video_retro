<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240121201437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE details_commandes_stock (details_commandes_id INT NOT NULL, stock_id INT NOT NULL, INDEX IDX_2F28FEAAA69C5741 (details_commandes_id), INDEX IDX_2F28FEAADCD6110 (stock_id), PRIMARY KEY(details_commandes_id, stock_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE films_formats (films_id INT NOT NULL, formats_id INT NOT NULL, INDEX IDX_BFB6E59F939610EE (films_id), INDEX IDX_BFB6E59F97CD605C (formats_id), PRIMARY KEY(films_id, formats_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE details_commandes_stock ADD CONSTRAINT FK_2F28FEAAA69C5741 FOREIGN KEY (details_commandes_id) REFERENCES details_commandes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE details_commandes_stock ADD CONSTRAINT FK_2F28FEAADCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE films_formats ADD CONSTRAINT FK_BFB6E59F939610EE FOREIGN KEY (films_id) REFERENCES films (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE films_formats ADD CONSTRAINT FK_BFB6E59F97CD605C FOREIGN KEY (formats_id) REFERENCES formats (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660939610EE FOREIGN KEY (films_id) REFERENCES films (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B36566097CD605C FOREIGN KEY (formats_id) REFERENCES formats (id)');
        $this->addSql('CREATE INDEX IDX_4B365660939610EE ON stock (films_id)');
        $this->addSql('CREATE INDEX IDX_4B36566097CD605C ON stock (formats_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE details_commandes_stock DROP FOREIGN KEY FK_2F28FEAAA69C5741');
        $this->addSql('ALTER TABLE details_commandes_stock DROP FOREIGN KEY FK_2F28FEAADCD6110');
        $this->addSql('ALTER TABLE films_formats DROP FOREIGN KEY FK_BFB6E59F939610EE');
        $this->addSql('ALTER TABLE films_formats DROP FOREIGN KEY FK_BFB6E59F97CD605C');
        $this->addSql('DROP TABLE details_commandes_stock');
        $this->addSql('DROP TABLE films_formats');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660939610EE');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B36566097CD605C');
        $this->addSql('DROP INDEX IDX_4B365660939610EE ON stock');
        $this->addSql('DROP INDEX IDX_4B36566097CD605C ON stock');
    }
}
