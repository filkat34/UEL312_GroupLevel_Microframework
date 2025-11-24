<?php

declare(strict_types=1);

namespace Framework312\Router\View;

use Framework312\Router\Request;
use Framework312\Template\Renderer;
use Symfony\Component\HttpFoundation\Response;

abstract class TemplateView extends BaseView
{
    /**
     * Instance du moteur de rendu de templates.
     * @var Renderer
     */
    protected Renderer $renderer;

    /**
     * Initialise la vue avec le moteur de template.
     * Enregistre automatiquement le dossier de templates basé sur le nom de la classe.
     *
     * @param Renderer $renderer Le moteur de rendu à utiliser.
     */
    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
        // Enregistre le dossier de templates correspondant à cette vue
        $this->renderer->register(static::class);
    }

    /**
     * Indique que cette vue utilise un moteur de template.
     * @return bool true car on utilise Twig pour générer le HTML.
     */
    public static function use_template(): bool
    {
        return true;
    }

    /**
     * Génère la réponse HTML en utilisant le moteur de template.
     *
     * @param Request $request La requête HTTP reçue.
     * @return Response La réponse HTTP avec le contenu HTML généré.
     */
    public function render(Request $request): Response
    {
        // Récupère la méthode HTTP en minuscule (get, post, put, etc.)
        $method = strtolower($request->getMethod());

        // Appelle dynamiquement la méthode correspondante qui retourne les données
        $data = $this->$method($request);

        // Utilise le nom court de la classe comme nom de template (ex: Book pour BookView)
        $reflect = new \ReflectionClass($this);
        $templateName = $reflect->getShortName();

        // Génère le HTML en passant les données au template
        $content = $this->renderer->render($data, $templateName);

        // Retourne une réponse HTTP 200 avec le contenu généré
        return new Response($content);
    }
}
