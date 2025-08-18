<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250818164349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create metric projections tables for hotel unique users';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE metric_hotel_users_detail (
              hotel_id VARCHAR(36) NOT NULL,
              user_id  VARCHAR(36) NOT NULL,
              PRIMARY KEY (hotel_id, user_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE metric_hotel_users (
              hotel_id     VARCHAR(36) NOT NULL PRIMARY KEY,
              unique_users INT NOT NULL DEFAULT 0
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE metric_hotel_users_detail');
        $this->addSql('DROP TABLE metric_hotel_users');
    }
}
