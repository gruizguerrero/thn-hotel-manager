<?php

namespace App\Shared\UI\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiHttpResponse extends JsonResponse
{
    protected const array DEFAULT_HEADERS = [
        'Content-Security-Policy' => "default-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline'",
        'X-XSS-Protection' => "1; mode=block",
        'X-Content-Type-Options' => "nosniff",
        'X-Frame-Options' => "Deny",
        'Strict-Transport-Security' => 'max-age=31536000'
    ];

    public function __construct(array $data, int $statusCode = HttpResponseCode::HTTP_OK, array $headers = [])
    {
        parent::__construct($data, $statusCode, array_merge($headers, self::DEFAULT_HEADERS));
    }

    public function data(): array
    {
        return $this->data;
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function headers(): array
    {
        return $this->headers->all();
    }
}