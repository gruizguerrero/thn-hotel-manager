<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Message;

use App\Shared\Domain\Message\Message;
use App\Shared\Domain\ValueObject\Uuid;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

final class MessageTest extends TestCase
{
    public function test_it_should_throw_invalid_argument_exception_when_payload_has_no_scalar_values(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new FakeMessage(['aKey' => new stdClass()]);
    }

    public function test_it_should_throw_invalid_argument_exception_when_key_does_not_exists_in_the_payload(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $message = new FakeMessage(['aKey' => 'someScalar']);

        $message->aMethodWhichTryToAccessANotExistingKey();
    }

    public function test_it_should_return_the_proper_payload_values(): void
    {
        $someScalar = 'someScalar';
        $message = FakeMessage::valid($someScalar);

        $this->assertEquals($message->aField(), $someScalar);
    }

    public function test_it_should_return_the_same_payload(): void
    {
        $payload = [FakeMessage::A_KEY => 'someScalar'];
        $message = new FakeMessage($payload);

        $this->assertSame($payload, $message->payload());
    }

    public function test_it_should_be_created_with_expected_metadata(): void
    {
        $payload = [FakeMessage::A_KEY => 'someScalar'];
        $message = new FakeMessage($payload);
        $expectedMetadata = [
            Message::MESSAGE_NAME => (string) FakeMessage::messageName(),
            Message::MESSAGE_VERSION => FakeMessage::VERSION,
        ];
        $this->assertSame(
            $expectedMetadata,
            $message->metadata()
        );
    }

    public function test_it_should_create_a_new_message_with_added_metadata(): void
    {
        $message = new FakeMessage([FakeMessage::A_KEY => 'someScalar']);
        $addedMetadata = ['correlation_id' => Uuid::generate()->value()];

        $expectedMetadata = [
                Message::MESSAGE_NAME => (string) FakeMessage::messageName(),
                Message::MESSAGE_VERSION => FakeMessage::VERSION,
            ] + $addedMetadata;

        $messageWithAddedMetadata = $message->withAddedMetadata($addedMetadata);
        $this->assertSame($expectedMetadata, $messageWithAddedMetadata->metadata());
    }

    public function test_it_should_not_modify_the_message_when_adding_metadata(): void
    {
        $message = new FakeMessage([FakeMessage::A_KEY => 'someScalar']);
        $addedMetadata = ['correlation_id' => Uuid::generate()->value()];

        $originalMetadata = [
            Message::MESSAGE_NAME => (string) FakeMessage::messageName(),
            Message::MESSAGE_VERSION => FakeMessage::VERSION,
        ];

        $messageWithAddedMetadata = $message->withAddedMetadata($addedMetadata);

        $this->assertSame($originalMetadata, $message->metadata());
        $this->assertNotSame($message, $messageWithAddedMetadata);
    }

    public function test_metadata_version_matches_with_declared_returns_true_when_matching(): void
    {
        $message = new FakeMessage([FakeMessage::A_KEY => 'someScalar']);

        $this->assertTrue($message->metadataVersionMatches());
    }

    public function test_metadata_version_matches_with_declared_returns_false_when_not_matching(): void
    {
        $message = new FakeMessage([FakeMessage::A_KEY => 'someScalar']);
        $versionDiffers = $message->withAddedMetadata([FakeMessage::MESSAGE_VERSION => '23']);

        $this->assertFalse($versionDiffers->metadataVersionMatches());
    }

    public function test_it_should_be_created_from_constructor_adding_new_metadata(): void
    {
        $aPayload = [FakeMessage::A_KEY => 'someScalar'];
        $correlationId = Uuid::generate()->value();
        $addedMetadata = ['correlation_id' => $correlationId];

        $message = new FakeMessage($aPayload, $addedMetadata);

        $expectedMetadata = [
            Message::MESSAGE_NAME => (string) FakeMessage::messageName(),
            Message::MESSAGE_VERSION => FakeMessage::VERSION,
            'correlation_id' => $correlationId,
        ];

        $this->assertSame($expectedMetadata, $message->metadata());
    }

    public function test_it_should_be_created_from_constructor_updating_default_metadata_values(): void
    {
        $aPayload = [FakeMessage::A_KEY => 'someScalar'];

        $newMessageVersion = '23';
        $metadataUpdatingDefaultMessageVersion = [Message::MESSAGE_VERSION => $newMessageVersion];

        $message = new FakeMessage($aPayload, $metadataUpdatingDefaultMessageVersion);

        $expectedMetadata = [
            Message::MESSAGE_NAME => (string) FakeMessage::messageName(),
            Message::MESSAGE_VERSION => $newMessageVersion,
        ];

        $this->assertSame($expectedMetadata, $message->metadata());
    }

    public function test_it_is_created_from_payload(): void
    {
        $aPayload = [FakeMessage::A_KEY => 'someScalar'];

        $message = FakeMessage::fromPayload($aPayload);

        $this->assertEquals($aPayload, $message->payload());
    }

    public function test_it_is_created_from_payload_and_metadata(): void
    {
        $aPayload = [FakeMessage::A_KEY => 'someScalar'];
        $correlationId = Uuid::generate()->value();
        $aMetadata = [
            'correlation_id' => $correlationId
        ];

        $message = FakeMessage::fromPayloadAndMetadata($aPayload, $aMetadata);

        $this->assertEquals($aPayload, $message->payload());
        $this->assertEquals($correlationId, $message->metadata()['correlation_id']);
    }
}