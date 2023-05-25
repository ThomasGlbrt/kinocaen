<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230525065813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE benevole (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE essai (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscrit_essai (inscrit_id INT NOT NULL, essai_id INT NOT NULL, INDEX IDX_D8081E266DCD4FEE (inscrit_id), INDEX IDX_D8081E26D6633E39 (essai_id), PRIMARY KEY(inscrit_id, essai_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pole (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inscrit_essai ADD CONSTRAINT FK_D8081E266DCD4FEE FOREIGN KEY (inscrit_id) REFERENCES inscrit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscrit_essai ADD CONSTRAINT FK_D8081E26D6633E39 FOREIGN KEY (essai_id) REFERENCES essai (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscrit_essai DROP FOREIGN KEY FK_D8081E266DCD4FEE');
        $this->addSql('ALTER TABLE inscrit_essai DROP FOREIGN KEY FK_D8081E26D6633E39');
        $this->addSql('DROP TABLE benevole');
        $this->addSql('DROP TABLE essai');
        $this->addSql('DROP TABLE inscrit_essai');
        $this->addSql('DROP TABLE pole');
    }
}
