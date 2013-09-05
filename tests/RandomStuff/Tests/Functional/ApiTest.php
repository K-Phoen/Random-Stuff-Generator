<?php

namespace RandomStuff\Tests\Functional;

class ApiTest extends WebTestCase
{
    /**
     * @dataProvider apiEndpointsProvider
     */
    public function testEndpointsAreAccessibleAndCanReturnXml($endpoint, array $headers)
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
}
