<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250815072943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create hotels table with id, name, city and country fields';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE hotels (
                id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                name VARCHAR(255) NOT NULL,
                city VARCHAR(255) NOT NULL,
                country VARCHAR(2) NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NULL DEFAULT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE hotel_rooms (
                id BINARY(16) NOT NULL PRIMARY KEY,
                number VARCHAR(10) NOT NULL,
                category VARCHAR(50) NOT NULL
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE hotel_rooms_assignment (
                hotel_id BINARY(16) NOT NULL,
                room_id BINARY(16) NOT NULL,
                PRIMARY KEY(hotel_id, room_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB
        ');

        $this->addSql('
            ALTER TABLE hotel_rooms_assignment
            ADD CONSTRAINT fk_hotel FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
        ');

        $this->addSql('
            ALTER TABLE hotel_rooms_assignment
            ADD CONSTRAINT fk_hotel_room FOREIGN KEY (room_id) REFERENCES hotel_rooms(id) ON DELETE CASCADE
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE hotels');
        $this->addSql('DROP TABLE hotel_rooms');
        $this->addSql('DROP TABLE hotel_rooms_assignment');
    }
}
