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

    // Routes statiques et dynamiques séparées pour optimisation de la recherche
    // Pas de boucle pour routes statiques
    private array $staticRoutes = []; // Chemin => Route
    private array $dynamicRoutes = []; // ['pattern' => string, 'route' => Route][]

    /**
     * Constructeur du routeur
     * Instancie le moteur de rendu des vues.
     * @param Renderer $engine Moteur de rendu des vues
     */
    public function __construct(Renderer $engine)
    {
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

    // Création méthode serve() 
    public function serve(mixed ...$args): void
    {
        $request = new Request(...$args); // Création objet Request avec tous les arguments
        $path = $request->getPathInfo(); // Récupération du chemin de la requête
        if ($path === '') { // Si chemin est vide alors on Symfony le défini comme ça ''
            $path = '/'; // Mais on préfère le définir comme ça / (mieux pour URL)
        }

        // Check dans Static Routes
        if (isset($this->staticRoutes[$path])) { // S'il existe une route statique qui correspond au path
            $route = $this->staticRoutes[$path]; // Alors on récupère l'objet Route de cette URL
            $response = $route->call($request, $this->engine); // Appel méthode call() pour générer la Response en créant la View et en appelant render
            $response->send(); // Symfony envoie Response au client navigateur
            return; // Stop exécution méthode serve()
        }

        // Check dans Dynamic Routes (/book/:id)
        foreach ($this->dynamicRoutes as $entry) { // Pour chaque route dynamique en entrée

            // Si regex correspond au path requête 
            if (preg_match($entry['pattern'], $path, $matches)) {

                // Pour chaque valeurs trouvée par regex
                foreach ($matches as $key => $value) {
                    if (!is_string($key)) {
                        continue; // Si la clé n'est pas string (donc numérique) on passe
                    }
                    $request->attributes->set($key, $value); // On met la valeur dans les paramètres de la requête pour que View y accède
                }

                $route = $entry['route']; // Récupération objet Route pour cette entrée/URL
                $response = $route->call($request, $this->engine); // Puis appel méthode call() qui crée View et appelle render() pour générer Response
                $response->send(); // Envoi Response au client navigateur
                return; // Stop exécution méthode serve()
            }
        }

        $response = new Response("404 Not Found", 404); // Si pas de route trouvée pour entrée, alors Response = 404
        $response->send(); // Envoi Response au client navigateur
    }
}
