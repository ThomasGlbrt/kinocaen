<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230112145412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emprunt (id INT AUTO_INCREMENT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscrit_materiel (inscrit_id INT NOT NULL, materiel_id INT NOT NULL, INDEX IDX_4EAAC6FF6DCD4FEE (inscrit_id), INDEX IDX_4EAAC6FF16880AAF (materiel_id), PRIMARY KEY(inscrit_id, materiel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inscrit_materiel ADD CONSTRAINT FK_4EAAC6FF6DCD4FEE FOREIGN KEY (inscrit_id) REFERENCES inscrit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscrit_materiel ADD CONSTRAINT FK_4EAAC6FF16880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscrit_materiel DROP FOREIGN KEY FK_4EAAC6FF6DCD4FEE');
        $this->addSql('ALTER TABLE inscrit_materiel DROP FOREIGN KEY FK_4EAAC6FF16880AAF');
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('DROP TABLE inscrit_materiel');
    }
}
