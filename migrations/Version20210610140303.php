<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210610140303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3A6A12EC1');
        $this->addSql('DROP INDEX UNIQ_A3C664D3A6A12EC1 ON subscription');
        $this->addSql('ALTER TABLE subscription DROP registered_user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription ADD registered_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A6A12EC1 FOREIGN KEY (registered_user_id) REFERENCES registered_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A3C664D3A6A12EC1 ON subscription (registered_user_id)');
    }
}
