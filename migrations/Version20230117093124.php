<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117093124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emprunt ADD materiel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D716880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id)');
        $this->addSql('CREATE INDEX IDX_364071D716880AAF ON emprunt (materiel_id)');
        $this->addSql('ALTER TABLE materiel DROP description');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materiel ADD description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D716880AAF');
        $this->addSql('DROP INDEX IDX_364071D716880AAF ON emprunt');
        $this->addSql('ALTER TABLE emprunt DROP materiel_id');
    }
}
