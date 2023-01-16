<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230116135041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emprunt (id INT AUTO_INCREMENT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emprunt_inscrit (emprunt_id INT NOT NULL, inscrit_id INT NOT NULL, INDEX IDX_81C9A1C8AE7FEF94 (emprunt_id), INDEX IDX_81C9A1C86DCD4FEE (inscrit_id), PRIMARY KEY(emprunt_id, inscrit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscrit_materiel (inscrit_id INT NOT NULL, materiel_id INT NOT NULL, INDEX IDX_4EAAC6FF6DCD4FEE (inscrit_id), INDEX IDX_4EAAC6FF16880AAF (materiel_id), PRIMARY KEY(inscrit_id, materiel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE emprunt_inscrit ADD CONSTRAINT FK_81C9A1C8AE7FEF94 FOREIGN KEY (emprunt_id) REFERENCES emprunt (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE emprunt_inscrit ADD CONSTRAINT FK_81C9A1C86DCD4FEE FOREIGN KEY (inscrit_id) REFERENCES inscrit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscrit_materiel ADD CONSTRAINT FK_4EAAC6FF6DCD4FEE FOREIGN KEY (inscrit_id) REFERENCES inscrit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscrit_materiel ADD CONSTRAINT FK_4EAAC6FF16880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscrit_metier DROP FOREIGN KEY FK_825EB43C6DCD4FEE');
        $this->addSql('ALTER TABLE inscrit_metier DROP FOREIGN KEY FK_825EB43CED16FA20');
        $this->addSql('DROP TABLE emprunter');
        $this->addSql('DROP TABLE inscrit_metier');
        $this->addSql('ALTER TABLE inscrit DROP poste, DROP num_tel, DROP talent, DROP chose_plus, DROP image');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emprunter (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE inscrit_metier (inscrit_id INT NOT NULL, metier_id INT NOT NULL, INDEX IDX_825EB43C6DCD4FEE (inscrit_id), INDEX IDX_825EB43CED16FA20 (metier_id), PRIMARY KEY(inscrit_id, metier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE inscrit_metier ADD CONSTRAINT FK_825EB43C6DCD4FEE FOREIGN KEY (inscrit_id) REFERENCES inscrit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscrit_metier ADD CONSTRAINT FK_825EB43CED16FA20 FOREIGN KEY (metier_id) REFERENCES metier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE emprunt_inscrit DROP FOREIGN KEY FK_81C9A1C8AE7FEF94');
        $this->addSql('ALTER TABLE emprunt_inscrit DROP FOREIGN KEY FK_81C9A1C86DCD4FEE');
        $this->addSql('ALTER TABLE inscrit_materiel DROP FOREIGN KEY FK_4EAAC6FF6DCD4FEE');
        $this->addSql('ALTER TABLE inscrit_materiel DROP FOREIGN KEY FK_4EAAC6FF16880AAF');
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('DROP TABLE emprunt_inscrit');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE inscrit_materiel');
        $this->addSql('ALTER TABLE inscrit ADD poste VARCHAR(40) DEFAULT NULL, ADD num_tel INT DEFAULT NULL, ADD talent VARCHAR(100) DEFAULT NULL, ADD chose_plus LONGTEXT DEFAULT NULL, ADD image VARCHAR(250) DEFAULT NULL');
    }
}
