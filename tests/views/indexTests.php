<?php

declare(strict_types=1);

use Framework312\Router\View\TemplateView;
use Framework312\Router\Request;

class indexTests extends TemplateView
{
    protected function get(Request $request): mixed
    {
        $groupe = 'LEVEL';
        $cards = [
            ['title' => 'HTML View', 'url' => '/html'],
            ['title' => 'JSON View', 'url' => '/json'],
            ['title' => 'Template View + routage dynamique (1)', 'url' => '/book/1'],
            ['title' => 'Template View + routage dynamique (2)', 'url' => '/book/2'],
            ['title' => 'Template View + routage dynamique (3)', 'url' => '/book/3'],

            // Ajoute d'autres cartes ici
        ];

        // Just return the data array, no echo, no $twig->render
        return ['cards' => $cards];
    }
}
