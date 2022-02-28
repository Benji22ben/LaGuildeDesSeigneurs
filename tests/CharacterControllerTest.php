<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{

    private $content;
    private static $identifier;
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * Tests create
     */
    public function testCreate()
    {
        $this->client->request('POST','/character/create',
        array(),//parameters
        array(),//files
        array('CONTENT_TYPE' => 'application/json'),//server
        '{"kind":"Dame", "name":"Eldalótë", "surname":"Fleur elfique", "caste":"Elfe", "knowledge":"Arts", "intelligence":120, "life":12, "image":"/images/eldalote.jpg"}'
        );
        $this->assertJsonResponse();
        $this->defineIdentifier();
        $this->assertIdentifier();
    }

    /**
     * Tests redirect index
     */
    public function testRedirectIndex()
    {
        $this->client->request('GET', '/character');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

    }

    /**
     * Tests index
     */
    public function testIndex()
    {
        $this->client->request('GET', '/character/index');
        $this->assertJsonResponse();
    }

    /**
     * Tests display
     */
    public function testDisplay()
    {
        $this->client->request('GET', '/character/display/'. self::$identifier);

        $this->assertJsonResponse();
    }


    /**
     * Tests inexisting identifier
     */
    public function testInexistingIdentifier()
    {
        $this->client->request('GET', '/character/display/bbc451fc6e23c6a53180581d422cbf7975086c49error');

        $this->assertError404($this->client->getResponse()->getStatusCode());
    }

    /**
     * Tests modify
     */
    public function testModify()
    {
        //Tests with partial data array
        $this->client->request(
            'PUT',
            '/character/modify/' . self::$identifier,
            array(),//parameters
            array(),//files
            array('CONTENT_TYPE' => 'application/json'),//server
            '{"kind":"Seigneur", "name":"Gorthol"}'
        );
        $this->assertJsonResponse();
        $this->assertIdentifier();
            
        //Tests with whole content
        $this->client->request(
            'PUT',
            '/character/modify/' . self::$identifier,
            array(),//parameters
            array(),//files
            array('CONTENT_TYPE' => 'application/json'),//server
            '{"kind":"Seigneur", "name":"Gorthol", "surname":"Heaume de terreur", "caste":"Chevalier", "knowledge":"Diplomatie", "intelligence":110, "life":13, "image":"/images/gorthol.jpg"}'
        );
            $this->assertJsonResponse();
            $this->assertIdentifier();
    }

    /**
     * Tests delete
     */
    public function testDelete()
    {
        $this->client->request('DELETE', '/character/delete/' . self::$identifier);
        $this->assertJsonResponse();
    }



    /*
    * Asserts that a Response is in json
    */
    public function assertJsonResponse()
    {
        $response = $this->client->getResponse();
        $this->content = json_decode($response->getContent(), true, 50);
        $this->assertResponseIsSuccessful();
        $this->assertTrue($response->headers->contains('Content-type', 'application/json'), $response->headers);
    }

    /*
    * Asserts Error 404
    */
    public function assertError404()
    {
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
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
}