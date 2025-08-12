<?php

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\Service\ReflectionManager;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Messenger\Stamp\StampInterface;
use Symfony\Component\Messenger\Stamp\ErrorDetailsStamp;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ProblemNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final class StampSerializer
{
    private SerializerInterface $errorSerializer;

    public function __construct(private readonly SerializerInterface $serializer)
    {
        $this->errorSerializer = new Serializer([new ObjectNormalizer(), new ProblemNormalizer(true)], [new JsonEncoder()]);
    }

    public function deserialize(array $stamp): StampInterface
    {
        if ($stamp['class_name'] !== 'Symfony\Component\Messenger\Stamp\ErrorDetailsStamp') {
            return $this->serializer->deserialize($stamp['fields'], $stamp['class_name'], JsonEncoder::FORMAT);
        }

        $fields = json_decode($stamp['fields'], true);

        return new ErrorDetailsStamp(
            $fields['exceptionClass'],
            $fields['exceptionCode'],
            $fields['exceptionMessage'],
            $this->recreateFlattenExceptionFromArray($fields['flattenException'])
        );
    }

    public function serialize(StampInterface $stamp): array
    {
        $serializedStamp =  [
            'class_name' => get_class($stamp),
        ];

        if (!$stamp instanceof ErrorDetailsStamp) {
            $serializedStamp['fields'] = $this->serializer->serialize($stamp, JsonEncoder::FORMAT);
        } else {
            $errorStamp = new ErrorDetailsStamp(
                $stamp->getExceptionClass(),
                $stamp->getExceptionCode(),
                $stamp->getExceptionMessage(),
                $this->sanitizeFlattenException($stamp->getFlattenException())
            );
            $serializedStamp['fields'] = $this->errorSerializer->serialize($errorStamp, JsonEncoder::FORMAT);
        }

        return $serializedStamp;
    }

    private function recreateFlattenExceptionFromArray(array $flattenException): FlattenException
    {
        $rm = ReflectionManager::create();

        return $rm->buildObject(
            FlattenException::class,
            [
                'message' => $flattenException['message'],
                'code' => $flattenException['code'],
                'previous' => $flattenException['previous'] ?? null,
                'trace' => $flattenException['trace'] ?? [],
                'traceAsString' => '',
                'class' => $flattenException['class'] ?? '',
                'statusCode' => $flattenException['statusCode'],
                'statusText' => $flattenException['statusText'],
                'headers' => $flattenException['headers'] ?? [],
                'file' => $flattenException['file'],
                'line' => $flattenException['line'],
                'asString' => null
            ]
        );
    }

    private function sanitizeFlattenException(FlattenException $flattenException) : FlattenException
    {
        $rm = ReflectionManager::create();

        return $rm->buildObject(
            FlattenException::class,
            [
                'message' => $flattenException->getMessage(),
                'code' => $flattenException->getCode(),
                'previous' => null,
                'trace' => array_slice($flattenException->getTrace(), 0, min(count($flattenException->getTrace()), 5)),
                'traceAsString' => '',
                'class' => $flattenException->getClass(),
                'statusCode' => $flattenException->getStatusCode(),
                'statusText' => $flattenException->getStatusText(),
                'headers' => $flattenException->getHeaders(),
                'file' => $flattenException->getFile(),
                'line' => $flattenException->getLine(),
                'asString' => null
            ]
        );
    }
}