<?php

namespace App\Tests;

use PhpParser\Node\Expr\Cast\String_;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{
    private $content;
    
    private $client;

    private static $identifier;
    
    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
    * Create
    */
    public function testCreate()
    {
        $this->client->request('POST', '/character/create');

        $this->assertJsonResponse();

        $this->defineIdentifier();
        $this->assertIdentifier();
    }

    public function testindex()
    {
        $this->client->request('GET', '/character/index');

        $this->assertJsonResponse();
    }

    /*** Asserts that 'identifier' is present in the Response*/
    public function assertIdentifier()
    {
        $this->assertArrayHasKey('identifier', $this->content);
    }

    /*** Defines identifier*/
    public function defineIdentifier()
    {
        self::$identifier = $this->content['identifier'];
    }

    public function testDisplay()
    {
        $this->client->request('GET', '/character/display/'  . self::$identifier);

        $this->assertJsonResponse($this->client->getResponse());
        $this->assertIdentifier();
    }

    /**
    * Tests modify
    */
    public function testModify()
    {
        $this->client->request('PUT', '/character/modify/' . self::$identifier);
        
        $this->assertJsonResponse($this->client->getResponse());
        $this->assertIdentifier();
    }

    public function assertJsonResponse()
    {
        $response = $this->client->getResponse();

        $this->content = json_decode($response->getContent(), true, 50);

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
    * Tests Delete
    */
    public function testDelete()
    {
        $this->client->request('DELETE', '/character/delete/'  . self::$identifier);
        
        $this->assertJsonResponse($this->client->getResponse());
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
