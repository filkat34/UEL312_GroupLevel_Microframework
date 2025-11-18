<?php

declare(strict_types=1);

use Framework312\Router\View\TemplateView;
use Framework312\Router\Request;

class Book extends TemplateView
{
    protected function get(Request $request): mixed
    {
        $id = $request->attributes->get('id', '1');

        $books = [
            '1' => [
                'titre' => 'Harry Potter et la Pierre Philosophale',
                'auteur' => 'J.K. Rowling',
                'année' => 1997,
                'description' => 'Un jeune sorcier découvre ses pouvoirs et entre à l\'école de sorcellerie de Poudlard.'
            ],
            '2' => [
                'titre' => 'Le Hobbit',
                'auteur' => 'J.R.R. Tolkien',
                'année' => 1937,
                'description' => 'Bilbo Baggins, un hobbit tranquille, se lance dans une aventure épique avec des nains pour récupérer un trésor volé.'
            ],
            '3' => [
                'titre' => 'Le Sorceleur',
                'auteur' => 'Andrzej Sapkowski',
                'année' => 1993,
                'description' => 'Geralt de Riv, un chasseur de monstres, navigue dans un monde rempli de magie, de conflits politiques et de créatures dangereuses.'
            ],
        ];

        $book = $books[$id] ?? [
            'titre' => 'Livre Inconnu',
            'auteur' => 'Inconnu',
            'année' => '',
            'description' => 'Aucune description disponible.'
        ];

        return ['id' => $id] + $book;
    }
}
