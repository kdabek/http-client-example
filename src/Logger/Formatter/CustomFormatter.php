<?php

namespace Kdabek\HttpClientExample\Logger\Formatter;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Monolog\Formatter\FormatterInterface;

class CustomFormatter implements FormatterInterface
{
    /**
     * @inheritDoc
     */
    public function format(array $record)
    {
        $channel = $record['channel'];
        $message = $record['message'];
        $level = $record['level_name'];
        $date = $record['datetime']->format('Y-m-d H:i:s');
        $header = "[".$date."] ".$channel.".".$level.": ".$message.PHP_EOL;
        /** @var RequestInterface|ResponseInterface $payload */
        $payload = $record['context']['payload'];

        $result = match ($message) {
            'RESPONSE' => $header . $this->formatResponse($payload),
            'REQUEST' => $header . $this->formatRequest($payload),
            default => $header
        };

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function formatBatch(array $records)
    {
        foreach ($records as $key => $record) {
            $records[$key] = $this->format($record);
        }

        return $records;
    }

    private function formatRequest(RequestInterface $request): string
    {
        return "Method: " . $request->getMethod() . PHP_EOL.
        "Url: " . $request->getUri()->__toString() . PHP_EOL.
        "Request data: " . PHP_EOL.
        $request->getBody()->__toString(). PHP_EOL . PHP_EOL;
    }

    private function formatResponse(ResponseInterface $response): string
    {
        $status = $response->getStatusCode();
        $color = $status >= 200 && $status < 300 ? "92m" : "31m";
        return "Status: \033[". $color . $status ."\033[0m". PHP_EOL.
            "Response data: " . PHP_EOL.
            $response->getBody()->__toString() . PHP_EOL . PHP_EOL;
    }
}
