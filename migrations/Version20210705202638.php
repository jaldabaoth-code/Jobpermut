<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210705202638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registered_user ADD rome_id INT DEFAULT NULL, DROP rome');
        $this->addSql('ALTER TABLE registered_user ADD CONSTRAINT FK_8B903F56EB630B1C FOREIGN KEY (rome_id) REFERENCES rome (id)');
        $this->addSql('CREATE INDEX IDX_8B903F56EB630B1C ON registered_user (rome_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registered_user DROP FOREIGN KEY FK_8B903F56EB630B1C');
        $this->addSql('DROP INDEX IDX_8B903F56EB630B1C ON registered_user');
        $this->addSql('ALTER TABLE registered_user ADD rome VARCHAR(5) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP rome_id');
    }
}
