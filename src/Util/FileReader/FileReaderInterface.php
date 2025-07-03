<?php

declare(strict_types=1);

namespace App\Util\FileReader;

interface FileReaderInterface {
    /**
     * @return array<mixed>
     */
    public function read(string $filepath): array;
}