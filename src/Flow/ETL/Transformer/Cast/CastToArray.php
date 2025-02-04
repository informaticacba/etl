<?php

declare(strict_types=1);

namespace Flow\ETL\Transformer\Cast;

use Flow\ETL\Transformer\Cast\EntryCaster\AnyToArrayEntryCaster;

/**
 * @psalm-immutable
 */
final class CastToArray extends CastEntries
{
    /**
     * @param array<string> $entryNames
     * @param bool $nullable
     */
    public function __construct(array $entryNames, bool $nullable = false)
    {
        parent::__construct($entryNames, new AnyToArrayEntryCaster(), $nullable);
    }

    /**
     * @param array<string> $entryNames
     */
    public static function nullable(array $entryNames) : self
    {
        return new self($entryNames, true);
    }
}
