<?php
return [
    'paths' => [
        MODX_BASE_PATH . 'views/'
    ],
    'compiled' => EVO_STORAGE_PATH . 'blade',
    'directive' => [
        'evoParser' => [EvolutionCMS\Support\BladeDirective::class, 'evoParser'],
        'evoLang' => [EvolutionCMS\Support\BladeDirective::class, 'evoLang'],
        'evoStyle' => [EvolutionCMS\Support\BladeDirective::class, 'evoStyle'],
        'evoAdminLang' => [EvolutionCMS\Support\BladeDirective::class, 'evoAdminLang'],
        'evoCharset' => [EvolutionCMS\Support\BladeDirective::class, 'evoCharset'],
        'evoAdminThemeUrl' => [EvolutionCMS\Support\BladeDirective::class, 'evoAdminThemeUrl'],
        'evoAdminThemeName' => [EvolutionCMS\Support\BladeDirective::class, 'evoAdminThemeName'],
        'makeUrl' => [EvolutionCMS\Support\BladeDirective::class, 'makeUrl'],
        'csrf' => [EvolutionCMS\Support\BladeDirective::class, 'csrf'],
        'evoRole' => [EvolutionCMS\Support\BladeDirective::class, 'evoRole'],
        'evoElseRole' => [EvolutionCMS\Support\BladeDirective::class, 'evoElseRole'],
        'evoEndRole' => [EvolutionCMS\Support\BladeDirective::class, 'evoEndRole'],
    ]
];
