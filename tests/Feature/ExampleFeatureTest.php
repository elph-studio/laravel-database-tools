<?php

declare(strict_types=1);

namespace Test\Feature;

use Elph\LaravelTesting\Test\TestCase\FeatureTestCase;

class ExampleFeatureTest extends FeatureTestCase
{
    /**
     * A basic test example.
     */
    public function testApplicationReturnsSuccessfulResponse(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertContent('ok');
    }
}
