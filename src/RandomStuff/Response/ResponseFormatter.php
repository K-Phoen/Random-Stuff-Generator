<?php

namespace RandomStuff\Response;

use Negotiation\FormatNegotiator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseFormatter
{
    protected $serializer, $formatNegociator, $allowedFormats;

    public function __construct($serializer, FormatNegotiator $formatNegociator, $allowedFormats = array('json', 'xml'))
    {
        $this->serializer = $serializer;
        $this->formatNegotiator = $formatNegociator;
        $this->allowedFormats = $allowedFormats;
    }

    public function format(Request $request, array $data)
    {
        $acceptData = $request->headers->get('accept', 'json');
        $format = $this->formatNegotiator->getBestFormat($acceptData, $this->allowedFormats);

        return new Response($this->serializer->serialize($data, $format), 200, array(
            'Content-Type' => $request->getMimeType($format)
        ));
    }
}
