<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117092800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emprunt ADD inscrit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D76DCD4FEE FOREIGN KEY (inscrit_id) REFERENCES inscrit (id)');
        $this->addSql('CREATE INDEX IDX_364071D76DCD4FEE ON emprunt (inscrit_id)');
        $this->addSql('ALTER TABLE inscrit DROP emprunt_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscrit ADD emprunt_id INT NOT NULL');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D76DCD4FEE');
        $this->addSql('DROP INDEX IDX_364071D76DCD4FEE ON emprunt');
        $this->addSql('ALTER TABLE emprunt DROP inscrit_id');
    }
}
