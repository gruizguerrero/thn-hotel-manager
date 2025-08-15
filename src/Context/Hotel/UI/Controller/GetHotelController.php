<?php

namespace App\Context\Hotel\UI\Controller;

use App\Context\Campaign\Application\Command\CreateCampaign\CreateCampaignCommand;
use App\Shared\Application\Bus\Command\CommandBusInterface;
use App\Shared\Application\Bus\Event\EventBusInterface;
use App\Shared\UI\Controller\ApiController;
use App\Shared\UI\Response\ApiHttpResponse;
use App\Shared\UI\Response\HttpResponseCode;
use Symfony\Component\HttpFoundation\Request;

final class GetHotelController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        EventBusInterface $eventBus
    ){
        parent::__construct($commandBus, $eventBus);
    }

    public function __invoke(Request $request, string $hotelId): ApiHttpResponse
    {
        return new ApiHttpResponse([
            'data' => [
                'id' => 'f4a8f92c-5208-4568-869c-3bc50bb28350',
                'name' => 'NH Collection',
                'city' => 'Madrid',
                'country' => 'ES',
                'available_rooms' => [
                    ['number' => '101', 'type' => 'single'],
                    ['number' => '102', 'type' => 'double'],
                ],
            ],
            'metadata' => [],
        ], HttpResponseCode::HTTP_OK);

        $data = json_decode($request->getContent(), true);

        $id = $data['uuid'];
        $name = $data['name'];

        try {
            $this->dispatchCommand(CreateCampaignCommand::create($id, $name));
        } catch (\Throwable) {
            return new ApiHttpResponse([], HttpResponseCode::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new ApiHttpResponse([], HttpResponseCode::HTTP_CREATED);
    }
}