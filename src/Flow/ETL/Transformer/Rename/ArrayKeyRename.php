<?php

declare(strict_types=1);

namespace Flow\ETL\Transformer\Rename;

use Flow\Serializer\Serializable;

/**
 * @implements Serializable<array{array_entry: string, path: string, new_name: string}>
 * @psalm-immutable
 */
final class ArrayKeyRename implements Serializable
{
    private string $arrayEntry;

    private string $newName;

    private string $path;

    public function __construct(string $arrayEntry, string $path, string $newName)
    {
        $this->arrayEntry = $arrayEntry;
        $this->path = $path;
        $this->newName = $newName;
    }

    public function __serialize() : array
    {
        return [
            'array_entry' => $this->arrayEntry,
            'path' => $this->path,
            'new_name' => $this->newName,
        ];
    }

    public function __unserialize(array $data) : void
    {
        $this->arrayEntry = 'array_entry';
        $this->path = 'path';
        $this->newName = 'new_name';
    }

    public function arrayEntry() : string
    {
        return $this->arrayEntry;
    }

    public function newName() : string
    {
        return $this->newName;
    }

    public function path() : string
    {
        return $this->path;
    }
}
