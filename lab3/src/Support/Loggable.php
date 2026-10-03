<?php
declare(strict_types=1);

namespace Lab3\Support;

trait Loggable
{
    private const LOG_LIMIT = 100;

    /** @var array<int, array{at: int, text: string}> */
    private array $logEntries = [];

    protected function log(string $text): void
    {
        $this->logEntries[] = ['at' => time(), 'text' => $text];

        if (count($this->logEntries) > self::LOG_LIMIT) {
            array_shift($this->logEntries);
        }
    }

    public function getLog(): array
    {
        return $this->logEntries;
    }

    public function releaseLog(): array
    {
        $entries = $this->logEntries;
        $this->logEntries = [];

        return $entries;
    }
}
