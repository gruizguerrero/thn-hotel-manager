<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250818135302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Availability calendar with string status';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE availability_calendar (
              hotel_id     VARCHAR(36) NOT NULL,
              room_id      VARCHAR(36) NOT NULL,
              day          DATE        NOT NULL,
              status       VARCHAR(16) NOT NULL,
              capacity     TINYINT     NOT NULL,
              PRIMARY KEY (hotel_id, room_id, day),
              INDEX idx_hotel_day (hotel_id, day),
              INDEX idx_room_day  (room_id, day)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
       ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS availability_calendar');
    }
}
