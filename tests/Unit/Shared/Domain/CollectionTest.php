<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain;

use App\Shared\Domain\Collection;
use PHPUnit\Framework\TestCase;

final class CollectionTest extends TestCase
{
    private const string CAMPAIGN_EMAIL = 'email_campaign_2025';
    private const string CAMPAIGN_SOCIAL = 'social_media_promo';
    private const string CAMPAIGN_BANNER = 'banner_spring_sale';

    public function test_it_creates_empty_collection(): void
    {
        $collection = Collection::createEmpty();

        $this->assertEmpty($collection);
    }

    public function test_it_extracts_elements_from_collection(): void
    {
        $collection = Collection::create(
            [self::CAMPAIGN_EMAIL, self::CAMPAIGN_SOCIAL, self::CAMPAIGN_BANNER]
        );

        $extract = $collection->extract();

        $this->assertEmpty($collection);
        $this->assertEquals(
            [self::CAMPAIGN_EMAIL, self::CAMPAIGN_SOCIAL, self::CAMPAIGN_BANNER],
            $extract->toArray()
        );
    }
}
