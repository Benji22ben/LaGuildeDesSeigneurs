<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayerControllerTest extends WebTestCase
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
        $this->client->request('POST','/player/create',
        array(),//parameters
        array(),//files
        array('CONTENT_TYPE' => 'application/json'),//server
        '{"firstname" : "Benjamin", "lastname" : "MARQUES", "email" : "benji22ben@gmail.com", "mirian" : "0"}'
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
        $this->client->request('GET', '/player');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Tests index
     */
    public function testIndex()
    {
        $this->client->request('GET', '/player/index');
        $this->assertJsonResponse();
    }

    /**
     * Tests display
     */
    public function testDisplay()
    {
        $this->client->request('GET', '/player/display/'. self::$identifier);

        $this->assertJsonResponse();
    }


    /**
     * Tests inexisting identifier
     */
    public function testInexistingIdentifier()
    {
        $this->client->request('GET', '/player/display/bbc451fc6e23c6a53180581d422cbf7975086c49error');

        $this->assertError404();
    }

    /**
     * Tests modify
     */
    public function testModify()
    {
        //Tests with partial data array
        $this->client->request(
            'PUT',
            '/player/modify/' . self::$identifier,
            array(),//parameters
            array(),//files
            array('CONTENT_TYPE' => 'application/json'),//server
            '{"firstname" : "Ben", "email" : "benbeny@gmail.com", "mirian" : "125"}'
        );
        $this->assertJsonResponse();
        $this->assertIdentifier();
            
        //Tests with whole content
        $this->client->request(
            'PUT',
            '/player/modify/' . self::$identifier,
            array(),//parameters
            array(),//files
            array('CONTENT_TYPE' => 'application/json'),//server
            '{"firstname" : "Trecy", "lastname" : "LAUWERS", "email" : "lauwers.trecy@gmail.com", "mirian" : "25"}'
        );
            $this->assertJsonResponse();
            $this->assertIdentifier();
    }

    /**
     * Tests delete
     */
    public function testDelete()
    {
        $this->client->request('DELETE', '/player/delete/' . self::$identifier);
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
