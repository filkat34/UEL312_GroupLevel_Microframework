<?php declare(strict_types=1);

namespace Framework312\Router;

use Framework312\Router\Exception as RouterException;
use Framework312\Template\Renderer;
use Symfony\Component\HttpFoundation\Response;

class Route {
    private const VIEW_CLASS = 'Framework312\Router\View\BaseView';
    private const VIEW_USE_TEMPLATE_FUNC = 'use_template';
    private const VIEW_RENDER_FUNC = 'render';

    private string $view;

    public function __construct(string|object $class_or_view) {
        $reflect = new \ReflectionClass($class_or_view);
        $view = $reflect->getName();
        if (!$reflect->isSubclassOf(self::VIEW_CLASS)) {
            throw new RouterException\InvalidViewImplementation($view);
        }
        $this->view = $view;
    }

    public function call(Request $request, ?Renderer $engine): Response {
	    // TODO
    }
}

class SimpleRouter implements Router {
    private Renderer $engine;

    // Routes statiques et dynamiques séparées pour optimisation de la recherche
    // Pas de boucle pour routes statiques
    private array $staticRoutes = []; // Chemin => Route
    private array $dynamicRoutes = []; // ['pattern' => string, 'route' => Route][]

    /**
     * Constructeur du routeur
     * Instancie le moteur de rendu des vues.
     * @param Renderer $engine Moteur de rendu des vues
     */
    public function __construct(Renderer $engine) {
        $this->engine = $engine;
    }

    /**
     * Enregistre une route avec un chemin et une classe ou vue associée.
     * @param string $path Chemin de la route (ex: '/book/:id')
     * @param string|object $class_or_view Classe ou vue associée à la route
     */
    public function register(string $path, string|object $class_or_view)
    {
        if (strpos($path, ':') === false) {
            $this->staticRoutes[$path] = new Route($class_or_view);
        } else {
            $pattern = preg_replace('#:([\w]+)#', '(?P<$1>[^/]+)', $path);
            $pattern = '#^' . $pattern . '$#';
            $this->dynamicRoutes[] = [
                'pattern' => $pattern,
                'route' => new Route($class_or_view)
            ];
        }
    }

    public function serve(mixed ...$args): void {
	    // TODO
    }
}

?>
