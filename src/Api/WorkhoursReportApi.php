<?php

declare(strict_types=1);

namespace App\Api;

use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;

class WorkhoursReportApi
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $reportApiUrl;

    public function __construct(string $reportApiUrl, ?Client $httpClient = null)
    {
        $this->httpClient = $httpClient ?? new Client();
        $this->reportApiUrl = $reportApiUrl;
    }

    public function getReportStream(\DateTimeInterface $from, \DateTimeInterface $to): StreamInterface
    {
        $response = $this->httpClient->get($this->reportApiUrl, [
            'query' => [
                'start' => $from->format('Y-m-d'),
                'end' => $to->format('Y-m-d'),
            ]
        ]);

        return $response->getBody();
    }
}