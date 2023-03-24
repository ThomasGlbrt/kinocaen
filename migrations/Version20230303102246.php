<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230303102246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vehicule (id INT AUTO_INCREMENT NOT NULL, inscrit_id INT DEFAULT NULL, places INT DEFAULT NULL, permis VARCHAR(10) DEFAULT NULL, UNIQUE INDEX UNIQ_292FFF1D6DCD4FEE (inscrit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1D6DCD4FEE FOREIGN KEY (inscrit_id) REFERENCES inscrit (id)');
        $this->addSql('ALTER TABLE inscrit ADD vehicule INT DEFAULT NULL');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B091BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1D6DCD4FEE');
        $this->addSql('DROP TABLE vehicule');
        $this->addSql('ALTER TABLE inscrit DROP vehicule');
        $this->addSql('ALTER TABLE inscrit_session ADD PorteurProjet VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B091BCF5E72D');
        $this->addSql('ALTER TABLE inscrit_type_competences ADD essai VARCHAR(3) DEFAULT NULL');
    }
}
