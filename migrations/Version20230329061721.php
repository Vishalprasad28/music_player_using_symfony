<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230329061721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, liked_by_id INT NOT NULL, INDEX IDX_AC6340B3E85F12B8 (post_id), INDEX IDX_AC6340B3B4622EC2 (liked_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3E85F12B8 FOREIGN KEY (post_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3B4622EC2 FOREIGN KEY (liked_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3E85F12B8');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3B4622EC2');
        $this->addSql('DROP TABLE `like`');
    }
}
