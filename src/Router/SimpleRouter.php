<?php

declare(strict_types=1);

namespace Framework312\Router;

use Framework312\Router\Exception as RouterException;
use Framework312\Template\Renderer;
use Symfony\Component\HttpFoundation\Response;

class Route
{
    private const VIEW_CLASS = 'Framework312\Router\View\BaseView';
    private const VIEW_USE_TEMPLATE_FUNC = 'use_template';
    private const VIEW_RENDER_FUNC = 'render';

    private string $view;

    public function __construct(string|object $class_or_view)
    {
        $reflect = new \ReflectionClass($class_or_view);
        $view = $reflect->getName();
        if (!$reflect->isSubclassOf(self::VIEW_CLASS)) {
            throw new RouterException\InvalidViewImplementation($view);
        }
        $this->view = $view;
    }

    public function call(Request $request, ?Renderer $engine): Response
    {
        $viewClass = $this->view;

        // Est-ce que cette View utilise un template Twig ?
        $usesTemplate = $viewClass::use_template();

        // Création de la vue la vue
        // Si elle utilise un template, on lui donnee le Renderer (Twig)
        if ($usesTemplate) {
            if ($engine === null) {
                throw new \RuntimeException("Cette vue utilise un template, mais aucun Renderer n'a été fourni.");
            }
            $view = new $viewClass($engine);
        } else {
            $view = new $viewClass();
        }

        // Appel la fonction render() de la vue
        $response = $view->render($request);

        // Retour de la Response
        return $response;
    }
}

class SimpleRouter implements Router
{
    private Renderer $engine;

    public function __construct(Renderer $engine)
    {
        $this->engine = $engine;
        // TODO
    }

    public function register(string $path, string|object $class_or_view)
    {
        // TODO
    }

    public function serve(mixed ...$args): void
    {
        // TODO
    }
}
