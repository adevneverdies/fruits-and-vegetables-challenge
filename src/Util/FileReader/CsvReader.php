<?php

declare(strict_types=1);

namespace App\Util\FileReader;

class CsvReader implements FileReaderInterface {
    /**
     * @throws \RuntimeException
     * @return array<mixed>
     */
    public function read(string $filepath): array {
        throw new \RuntimeException('not implemented for csv');
    }
}