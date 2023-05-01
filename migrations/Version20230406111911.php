<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230406111911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE likes_count (id INT AUTO_INCREMENT NOT NULL, song_id INT NOT NULL, liked_by_id INT NOT NULL, INDEX IDX_3925E8E4A0BDB2F3 (song_id), INDEX IDX_3925E8E4B4622EC2 (liked_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE likes_count ADD CONSTRAINT FK_3925E8E4A0BDB2F3 FOREIGN KEY (song_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE likes_count ADD CONSTRAINT FK_3925E8E4B4622EC2 FOREIGN KEY (liked_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user CHANGE profile_pic profile_pic VARCHAR(300) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE likes_count DROP FOREIGN KEY FK_3925E8E4A0BDB2F3');
        $this->addSql('ALTER TABLE likes_count DROP FOREIGN KEY FK_3925E8E4B4622EC2');
        $this->addSql('DROP TABLE likes_count');
        $this->addSql('ALTER TABLE user CHANGE profile_pic profile_pic VARCHAR(300) DEFAULT \'profilePic/default.png\'');
    }
}
