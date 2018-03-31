<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180331112434 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE partie (id INT AUTO_INCREMENT NOT NULL, j1_id INT DEFAULT NULL, j2_id INT DEFAULT NULL, pioche LONGTEXT NOT NULL, main_j1 LONGTEXT NOT NULL, main_j2 LONGTEXT NOT NULL, tour VARCHAR(255) NOT NULL, carte_ecartee INT NOT NULL, manche VARCHAR(255) NOT NULL, score_j1 INT NOT NULL, score_j2 INT NOT NULL, objectif_attribution LONGTEXT NOT NULL, action_j1 LONGTEXT NOT NULL, action_j2 LONGTEXT NOT NULL, INDEX IDX_59B1F3DC4371B17 (j1_id), INDEX IDX_59B1F3DD682B4F9 (j2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3DC4371B17 FOREIGN KEY (j1_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3DD682B4F9 FOREIGN KEY (j2_id) REFERENCES user (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE partie');
    }
}
