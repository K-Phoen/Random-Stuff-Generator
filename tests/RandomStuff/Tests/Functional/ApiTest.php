<?php

namespace RandomStuff\Tests\Functional;

class ApiTest extends WebTestCase
{
    /**
     * @dataProvider apiEndpointsProvider
     */
    public function testEndpointsAreAccessible($endpoint, array $headers)
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', $endpoint, $parameters = array(), $files = array(), $headers['request']);

        $this->assertTrue($client->getResponse()->isOk());

        foreach ($headers['response'] as $header => $expectedValue) {
            $this->assertSame($expectedValue, $client->getResponse()->headers->get($header));
        }
    }

    public function apiEndpointsProvider()
    {
        $json_headers = array(
            'request'   => array(
                'HTTP_ACCEPT' => 'text/html,application/json,application/xml'
            ),
            'response'  => array(
                'Content-Type' => 'application/json',
            ),
        );
        $xml_headers = array(
            'request'   => array(
                'HTTP_ACCEPT' => 'text/html,application/xml,application/json'
            ),
            'response'  => array(
                'Content-Type' => 'text/xml; charset=UTF-8',
            ),
        );

        return array(
            array('/api/users', $xml_headers),
            array('/api/users/single', $xml_headers),

            array('/api/locations', $xml_headers),
            array('/api/locations/single', $xml_headers),

            array('/api/events', $xml_headers),
            array('/api/events/single', $xml_headers),

            array('/api/users', $json_headers),
            array('/api/users/single', $json_headers),

            array('/api/locations', $json_headers),
            array('/api/locations/single', $json_headers),

            array('/api/events', $json_headers),
            array('/api/events/single', $json_headers),
        );
    }

    /**
     * @dataProvider resultsSizeProvider
     */
    public function testLimitingResults($size, $expectedSize = null)
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/users', $parameters = array('size' => $size), $files = array(), $server = array(
            'HTTP_ACCEPT' => 'text/html,application/json,application/xml'
        ));

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('results', $responseData);
        $this->assertCount($expectedSize !== null ? $expectedSize : $size, $responseData['results']);
    }

    public function resultsSizeProvider()
    {
        return array(
            array(1),
            array(2),
            array(10),
            array(20),

            array('lala', 10),
            array('', 10),
            array(null, 10),
            array(0, 10),
            array(-4, 10),
        );
    }

    public function testOverridingSimpleValue()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/users/single', $parameters = array('email' => 'foo@bar.baz'), $files = array(), $server = array(
            'HTTP_ACCEPT' => 'text/html,application/json,application/xml'
        ));

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('results', $responseData);
        $this->assertSame($responseData['results']['email'], 'foo@bar.baz');
    }

    public function testOverridingArrayValue()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/users/single', $parameters = array('name:first' => 'foo'), $files = array(), $server = array(
            'HTTP_ACCEPT' => 'text/html,application/json,application/xml'
        ));

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('results', $responseData);
        $this->assertArrayHasKey('name', $responseData['results']);
        $this->assertSame($responseData['results']['name']['first'], 'foo');
    }
}
