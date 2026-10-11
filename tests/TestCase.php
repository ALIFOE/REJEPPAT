<?php

namespace Tests;

use App\Models\Media;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Les vidéos du disque privé (non versionnées) ne doivent pas influencer les tests
        Storage::fake(Media::DISQUE_VIDEOS);
    }
}
