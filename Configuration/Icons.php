<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'action-folder' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:fal_securedownload/Resources/Public/Icons/folder.svg',
    ],
    'overlay-inherited-permissions' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:fal_securedownload/Resources/Public/Icons/overlay-inherited-permissions.svg',
    ],
];
