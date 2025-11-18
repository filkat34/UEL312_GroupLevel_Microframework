<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Framework312\Router\SimpleRouter;
use Framework312\Template\TwigRenderer;

// Charger les vues de test
require_once __DIR__ . '/tests/views/TestHTMLView.php';
require_once __DIR__ . '/tests/views/TestJSONView.php';
require_once __DIR__ . '/tests/views/Book.php';

// Créer le moteur de rendu des templates
$engine = new TwigRenderer(__DIR__ . '/templates/');

// Créer le routeur
$router = new SimpleRouter($engine);

// Enregistrer les routes
$router->register('/html', 'TestHTMLView');
$router->register('/json', 'TestJSONView');
$router->register('/book/:id', 'Book');

// Servir la requête
$router->serve();
