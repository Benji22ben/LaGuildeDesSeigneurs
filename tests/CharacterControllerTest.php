<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{
    public function testindex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/character');

        $this->assertJsonResponse($client->getResponse());
    }

    public function assertJsonResponse($response)
    {
        $this->assertResponseIsSuccessful();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }
}
