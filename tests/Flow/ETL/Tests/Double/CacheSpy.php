<?php

declare(strict_types=1);

namespace Flow\ETL\Tests\Double;

use Flow\ETL\Cache;
use Flow\ETL\Rows;

final class CacheSpy implements Cache
{
    private Cache $cache;

    /**
     * @var array<string, int>
     */
    private array $clears = [];

    /**
     * @var array<string, int>
     */
    private array $reads = [];

    /**
     * @var array<string, int>
     */
    private array $writes = [];

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function add(string $id, Rows $rows) : void
    {
        if (!\array_key_exists($id, $this->writes)) {
            $this->writes[$id] = 1;
        } else {
            $this->writes[$id] += 1;
        }

        $this->cache->add($id, $rows);
    }

    public function clear(string $id) : void
    {
        if (!\array_key_exists($id, $this->clears)) {
            $this->clears[$id] = 1;
        } else {
            $this->clears[$id] += 1;
        }

        $this->cache->clear($id);
    }

    public function clears() : int
    {
        $total = 0;

        foreach ($this->clears as $clears) {
            $total += $clears;
        }

        return $total;
    }

    public function read(string $id) : \Generator
    {
        if (!\array_key_exists($id, $this->reads)) {
            $this->reads[$id] = 1;
        } else {
            $this->reads[$id] += 1;
        }

        return $this->cache->read($id);
    }

    public function reads() : int
    {
        $total = 0;

        foreach ($this->reads as $reads) {
            $total += $reads;
        }

        return $total;
    }

    public function writes() : int
    {
        $total = 0;

        foreach ($this->writes as $writes) {
            $total += $writes;
        }

        return $total;
    }
}
