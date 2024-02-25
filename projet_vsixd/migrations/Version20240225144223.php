<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240225144223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE temoignage (id INT AUTO_INCREMENT NOT NULL, employe_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, commentaire LONGTEXT NOT NULL, note INT NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_BDADBC461B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE temoignage ADD CONSTRAINT FK_BDADBC461B65292 FOREIGN KEY (employe_id) REFERENCES temoignage (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE temoignage DROP FOREIGN KEY FK_BDADBC461B65292');
        $this->addSql('DROP TABLE temoignage');
    }
}
