<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031000810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE measurement (id INT AUTO_INCREMENT NOT NULL, id_wine INT NOT NULL, id_sensor INT NOT NULL, year VARCHAR(10) NOT NULL, color VARCHAR(50) NOT NULL, temperature VARCHAR(10) NOT NULL, alcohol_content VARCHAR(10) NOT NULL, ph VARCHAR(10) NOT NULL, INDEX IDX_2CE0D811DD2C786A (id_wine), INDEX IDX_2CE0D8119AB1A25D (id_sensor), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sensor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, surname VARCHAR(200) NOT NULL, email VARCHAR(150) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wine (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, year VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE measurement ADD CONSTRAINT FK_2CE0D811DD2C786A FOREIGN KEY (id_wine) REFERENCES wine (id)');
        $this->addSql('ALTER TABLE measurement ADD CONSTRAINT FK_2CE0D8119AB1A25D FOREIGN KEY (id_sensor) REFERENCES sensor (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE measurement DROP FOREIGN KEY FK_2CE0D811DD2C786A');
        $this->addSql('ALTER TABLE measurement DROP FOREIGN KEY FK_2CE0D8119AB1A25D');
        $this->addSql('DROP TABLE measurement');
        $this->addSql('DROP TABLE sensor');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wine');
    }
}
