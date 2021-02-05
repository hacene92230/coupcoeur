<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210129165235 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5C54C8C93');
        $this->addSql('CREATE TABLE type_annonce (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE annonce_type');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5C54C8C93');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5C54C8C93 FOREIGN KEY (type_id) REFERENCES type_annonce (id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5C54C8C93');
        $this->addSql('CREATE TABLE annonce_type (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE type_annonce');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5C54C8C93');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5C54C8C93 FOREIGN KEY (type_id) REFERENCES annonce_type (id)');
        $this->addSql('ALTER TABLE `user` CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
