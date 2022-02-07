<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{
    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testindex()
    {
        $this->client->request('GET', '/character');

        $this->assertJsonResponse($this->client->getResponse());
    }

    public function testDisplay()
    {
        $this->client->request('GET', '/character/display/32cdbd3d7e47d102000c5ee9ca9812e18f52da7e');

        $this->assertJsonResponse($this->client->getResponse());
    }

    public function assertJsonResponse($response)
    {
        $response = $this->client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }

    public function testRedirectIndex()
    {
        $this->client->request('GET', '/character');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testBadIdentifier()
    {
        $this->client->request('GET', '/character/display/badIdentifier');
        $this->assertError404($this->client->getResponse()->getStatusCode());
    }

    /**
     * Assert that Response returns 404
     */
    public function assertError404($statusCode)
    {
        $this->assertEquals(404, $statusCode);
    }

    /**
     * Tests Inexisting Identifier
     */
    public function testInexistingIdentifier()
    {
        $this->client->request('GET', '/character/display/32cdbd3d7e47d102000c5ee9ca9812e18f52da7eerror');
        $this->assertError404($this->client->getResponse()->getStatusCode());
    }
}
