<?php

declare(strict_types=1);

use Framework312\Router\View\TemplateView;
use Framework312\Router\Request;

class Homepage extends TemplateView
{
    protected function get(Request $request): mixed
    {
        $groupe = 'Groupe : LEVEL';
        $ue = 'UE L312 - Microframework PHP';
        $cards = [
            ['title' => 'HTML View', 'url' => '/html'],
            ['title' => 'JSON View', 'url' => '/json'],
            ['title' => 'Template View + routage dynamique (1)', 'url' => '/book/1'],
            ['title' => 'Template View + routage dynamique (2)', 'url' => '/book/2'],
            ['title' => 'Template View + routage dynamique (3)', 'url' => '/book/3'],
        ];

        return ['cards' => $cards, 'groupe' => $groupe, 'ue' => $ue];
    }
}
