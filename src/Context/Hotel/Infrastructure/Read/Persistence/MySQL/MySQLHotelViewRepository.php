<?php

declare(strict_types=1);

namespace App\Context\Hotel\Infrastructure\Read\Persistence\MySQL;

use App\Context\Hotel\Domain\Read\Entity\HotelView;
use App\Context\Hotel\Domain\Read\Repository\HotelViewRepository;
use App\Shared\Domain\Exception\ViewNotFoundException;
use App\Shared\Domain\Service\BinToString;
use App\Shared\Domain\Service\StringToBin;
use App\Shared\Infrastructure\Persistence\MySQL\ViewRepository;

final class MySQLHotelViewRepository extends ViewRepository implements HotelViewRepository
{
    public function find(string $hotelId): HotelView
    {
        $query = <<<SQL
SELECT
    h.id,
    h.name,
    h.country,
    h.city,
    h.number_of_rooms
FROM
    hotels h
WHERE
    h.id = :id
SQL;

        $statement = $this->entityManager->getConnection()->prepare($query);
        $statement->bindValue('id', StringToBin::transformUuid($hotelId));
        $result = $statement->executeQuery();

        $data = $result->fetchAssociative();

        if (!$data) {
            throw ViewNotFoundException::forViewFQCNAndId(HotelView::class, $hotelId);
        }

        return $this->hotelViewMapper($data);
    }

    private function hotelViewMapper(array $data): HotelView
    {
        return new HotelView(
            BinToString::transformUuid($data['id']),
            $data['name'],
            $data['country'],
            $data['city'],
            $data['number_of_rooms'],
        );
    }
}
