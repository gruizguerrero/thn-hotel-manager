<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250817110751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Create tables bookings, booking_room y booking_rooms_assignment with proper UUID type and charset";
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE bookings (
                id BINARY(16) NOT NULL PRIMARY KEY,
                hotel_id BINARY(16) NOT NULL,
                user_id BINARY(16) NOT NULL,
                check_in_date DATETIME NOT NULL,
                check_out_date DATETIME NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME DEFAULT NULL
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE booking_room (
                id BINARY(16) NOT NULL PRIMARY KEY,
                room_id BINARY(16) NOT NULL
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE booking_rooms_assignment (
                booking_id BINARY(16) NOT NULL,
                room_id BINARY(16) NOT NULL,
                PRIMARY KEY(booking_id, room_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB
        ');

        $this->addSql('
            ALTER TABLE booking_rooms_assignment
            ADD CONSTRAINT fk_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
        ');

        $this->addSql('
            ALTER TABLE booking_rooms_assignment
            ADD CONSTRAINT fk_room FOREIGN KEY (room_id) REFERENCES booking_room(id) ON DELETE CASCADE
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS booking_rooms_assignment');
        $this->addSql('DROP TABLE IF EXISTS booking_room');
        $this->addSql('DROP TABLE IF EXISTS bookings');
    }
}
