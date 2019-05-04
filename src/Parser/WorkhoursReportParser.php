<?php

declare(strict_types=1);

namespace App\Parser;

use App\Model\WorkhourLog;
use GuzzleHttp\Psr7\StreamWrapper;
use JMS\Serializer\SerializerInterface;
use JsonCollectionParser\Parser;
use Psr\Http\Message\StreamInterface;

class WorkhoursReportParser
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(Parser $parser, SerializerInterface $serializer)
    {
        $this->parser = $parser;
        $this->serializer = $serializer;
    }

    public function parseApiResponse(StreamInterface $apiResponse, callable $callback): void
    {
        $resource = StreamWrapper::getResource($apiResponse);

        $this->parser->parse($resource, function (array $item) use ($callback) {
            $workLog = $this->serializer->fromArray($item, WorkhourLog::class);
            $callback($workLog);
        });
    }
}