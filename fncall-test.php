<?php

require __DIR__ . '/vendor/autoload.php';

// ⚠️ On ajoute cette ligne pour charger la classe Route
require __DIR__ . '/src/Router/SimpleRouter.php';

use Framework312\Router\Route;
use Framework312\Router\Request;
use Framework312\Router\View\BaseView;
use Framework312\Template\Renderer;
use Symfony\Component\HttpFoundation\Response;

// Renderer factice, au cas où (mais on ne va pas l'utiliser ici)
class FakeRenderer implements Renderer
{
    public function render(mixed $data, string $template): string
    {
        return '';
    }
    public function register(string $tag) {}
}

// Une petite vue de test
class TestView extends BaseView
{
    public static function use_template(): bool
    {
        return false; // très important : comme ça, Route::call() n'a pas besoin de Renderer
    }

    public function render(Request $request): Response
    {
        return new Response("✅ TestView::render() a été appelée via Route::call()");
    }
}

// 1. On crée la Request
$request = new Request();

// 2. On crée la Route qui pointe vers TestView
$route = new Route(TestView::class);

// 3. On appelle fncall (call()) avec un Renderer null (puisque use_template() = false)
$response = $route->call($request, null);

// 4. On envoie la réponse
$response->send();
