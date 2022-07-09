<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210616135313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registered_user ADD city VARCHAR(255) NOT NULL, ADD city_job VARCHAR(255) NOT NULL, ADD street VARCHAR(255) NOT NULL, ADD street_number VARCHAR(255) NOT NULL, ADD zipcode VARCHAR(255) NOT NULL, DROP address, DROP job_address');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registered_user ADD address VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD job_address VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP city, DROP city_job, DROP street, DROP street_number, DROP zipcode');
    }
}
