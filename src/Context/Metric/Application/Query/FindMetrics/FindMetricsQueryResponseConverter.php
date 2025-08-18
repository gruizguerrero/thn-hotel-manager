<?php

declare(strict_types=1);

namespace App\Context\Metric\Application\Query\FindMetrics;

final class FindMetricsQueryResponseConverter
{
    public function __invoke(array $rows): FindMetricsQueryResponse
    {
        $data = [];

        foreach ($rows as $row) {
            $id    = $row['hotel_id'];
            $users = $row['unique_users'];

            $data[] = [
                'id'    => $id,
                'users' => (string) $users
            ];
        }

        return new FindMetricsQueryResponse($data);
    }
}
