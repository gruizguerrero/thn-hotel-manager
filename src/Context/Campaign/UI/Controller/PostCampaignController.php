<?php

namespace App\Context\Campaign\UI\Controller;

use App\Context\Campaign\Application\Command\CreateCampaign\CreateCampaignCommand;
use App\Shared\Application\Bus\Command\CommandBusInterface;
use App\Shared\UI\Controller\ApiController;
use App\Shared\UI\Response\ApiHttpResponse;
use App\Shared\UI\Response\HttpResponseCode;
use Symfony\Component\HttpFoundation\Request;

final class PostCampaignController extends ApiController
{
    public function __construct(CommandBusInterface $commandBus)
    {
        parent::__construct($commandBus);
    }

    public function __invoke(Request $request): ApiHttpResponse
    {
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