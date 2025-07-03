<?php

declare(strict_types=1);

namespace App\Util\FileReader;

class JsonReader implements FileReaderInterface {
    /**
     * @throws \RuntimeException|\JsonException
     * @return array<mixed>
     */
    public function read(string $filepath): array {
        if (! file_exists($filepath)) {
            throw new \RuntimeException('File is not exist');
        }

        $extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));

        if ($extension !== 'json') {
            throw new \RuntimeException("Expected a .json file");
        }

        $mime = mime_content_type($filepath);
        if ($mime !== 'application/json' && $mime !== 'text/plain') {
            throw new \RuntimeException("Invalid MIME type for JSON: $mime");
        }

        $data = json_decode(file_get_contents($filepath), true, JSON_THROW_ON_ERROR);

        return $data;
    }
}