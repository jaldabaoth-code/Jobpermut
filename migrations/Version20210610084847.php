<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210610084847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE registered_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, subscription_id INT DEFAULT NULL, pseudo VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone INT DEFAULT NULL, address VARCHAR(255) NOT NULL, job_address VARCHAR(255) NOT NULL, ogr INT NOT NULL, UNIQUE INDEX UNIQ_8B903F56A76ED395 (user_id), UNIQUE INDEX UNIQ_8B903F569A1887DC (subscription_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, subscription_at DATETIME NOT NULL, curriculum VARCHAR(255) NOT NULL, job_description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE registered_user ADD CONSTRAINT FK_8B903F56A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE registered_user ADD CONSTRAINT FK_8B903F569A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registered_user DROP FOREIGN KEY FK_8B903F569A1887DC');
        $this->addSql('DROP TABLE registered_user');
        $this->addSql('DROP TABLE subscription');
    }
}
