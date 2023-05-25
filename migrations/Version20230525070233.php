<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230525070233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE benevole_pole (benevole_id INT NOT NULL, pole_id INT NOT NULL, INDEX IDX_1C7C3552E77B7C09 (benevole_id), INDEX IDX_1C7C3552419C3385 (pole_id), PRIMARY KEY(benevole_id, pole_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE benevole_pole ADD CONSTRAINT FK_1C7C3552E77B7C09 FOREIGN KEY (benevole_id) REFERENCES benevole (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE benevole_pole ADD CONSTRAINT FK_1C7C3552419C3385 FOREIGN KEY (pole_id) REFERENCES pole (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE benevole_pole DROP FOREIGN KEY FK_1C7C3552E77B7C09');
        $this->addSql('ALTER TABLE benevole_pole DROP FOREIGN KEY FK_1C7C3552419C3385');
        $this->addSql('DROP TABLE benevole_pole');
    }
}
