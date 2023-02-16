<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213134446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inscrit_type_competences (inscrit_id INT NOT NULL, type_competences_id INT NOT NULL, INDEX IDX_8AC242006DCD4FEE (inscrit_id), INDEX IDX_8AC24200F0A398CC (type_competences_id), PRIMARY KEY(inscrit_id, type_competences_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscrit_session (inscrit_id INT NOT NULL, session_id INT NOT NULL, INDEX IDX_1BF698E16DCD4FEE (inscrit_id), INDEX IDX_1BF698E1613FECDF (session_id), PRIMARY KEY(inscrit_id, session_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inscrit_type_competences ADD CONSTRAINT FK_8AC242006DCD4FEE FOREIGN KEY (inscrit_id) REFERENCES inscrit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscrit_type_competences ADD CONSTRAINT FK_8AC24200F0A398CC FOREIGN KEY (type_competences_id) REFERENCES type_competences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscrit_session ADD CONSTRAINT FK_1BF698E16DCD4FEE FOREIGN KEY (inscrit_id) REFERENCES inscrit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscrit_session ADD CONSTRAINT FK_1BF698E1613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscrit_session ADD PorteurProjet VARCHAR(3) NULL');
        $this->addSql('ALTER TABLE inscrit_type_competences ADD essai VARCHAR(3) NULL');
    }   

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscrit_type_competences DROP FOREIGN KEY FK_8AC242006DCD4FEE');
        $this->addSql('ALTER TABLE inscrit_type_competences DROP FOREIGN KEY FK_8AC24200F0A398CC');
        $this->addSql('ALTER TABLE inscrit_session DROP FOREIGN KEY FK_1BF698E16DCD4FEE');
        $this->addSql('ALTER TABLE inscrit_session DROP FOREIGN KEY FK_1BF698E1613FECDF');
        $this->addSql('DROP TABLE inscrit_type_competences');
        $this->addSql('DROP TABLE inscrit_session');
    }
}
