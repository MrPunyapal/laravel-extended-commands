<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorPest\Set\PestLevelSetList;
use RectorPest\Set\PestSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
        __DIR__.'/config',
        __DIR__.'/tests',
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        privatization: true,
        earlyReturn: true,
    )
    ->withSets([
        PestSetList::PEST_CODE_QUALITY,
        PestLevelSetList::UP_TO_PEST_30,
    ])
    ->withPhpSets()
    ->withImportNames();
