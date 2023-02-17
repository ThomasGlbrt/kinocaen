<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230217093429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B091BCF5E72D');
        $this->addSql('ALTER TABLE inscrit_metier DROP FOREIGN KEY FK_825EB43C6DCD4FEE');
        $this->addSql('ALTER TABLE inscrit_metier DROP FOREIGN KEY FK_825EB43CED16FA20');
        $this->addSql('DROP TABLE metier');
        $this->addSql('DROP TABLE inscrit_metier');
        $this->addSql('ALTER TABLE inscrit DROP FOREIGN KEY FK_927FA3652A4C4478');
        $this->addSql('DROP INDEX IDX_927FA3652A4C4478 ON inscrit');
        $this->addSql('ALTER TABLE inscrit DROP paiement_id, DROP poste, DROP talent, DROP chose_plus');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE metier (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE inscrit_metier (inscrit_id INT NOT NULL, metier_id INT NOT NULL, INDEX IDX_825EB43C6DCD4FEE (inscrit_id), INDEX IDX_825EB43CED16FA20 (metier_id), PRIMARY KEY(inscrit_id, metier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE inscrit_metier ADD CONSTRAINT FK_825EB43C6DCD4FEE FOREIGN KEY (inscrit_id) REFERENCES inscrit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscrit_metier ADD CONSTRAINT FK_825EB43CED16FA20 FOREIGN KEY (metier_id) REFERENCES metier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscrit ADD paiement_id INT DEFAULT NULL, ADD poste VARCHAR(40) DEFAULT NULL, ADD talent VARCHAR(100) DEFAULT NULL, ADD chose_plus LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE inscrit ADD CONSTRAINT FK_927FA3652A4C4478 FOREIGN KEY (paiement_id) REFERENCES type_paiement (id)');
        $this->addSql('CREATE INDEX IDX_927FA3652A4C4478 ON inscrit (paiement_id)');
        $this->addSql('ALTER TABLE inscrit_session ADD PorteurProjet VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE materiel ADD categorie_id INT NOT NULL, ADD image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B091BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_18D2B091BCF5E72D ON materiel (categorie_id)');
        $this->addSql('ALTER TABLE inscrit_type_competences ADD essai VARCHAR(3) DEFAULT NULL');
    }
}
