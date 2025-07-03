<?php

declare(strict_types=1);

namespace App\Util\FileReader;

class FileReaderFactory {
    /**
     * @throws \InvalidArgumentException
     */
    public static function create(string $type): FileReaderInterface {
        return match (strtolower($type)) {
            'json' => new JsonReader(),
            'csv' => new CsvReader(),
            default => throw new \InvalidArgumentException("Unsupported file type: $type"),
        };
    }
}