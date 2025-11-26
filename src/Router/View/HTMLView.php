<?php

declare(strict_types=1);

namespace Framework312\Router\View;

use Framework312\Router\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Vue pour renvoyer du HTML brut sans moteur de template.
 */
abstract class HTMLView extends BaseView
{
    /**
     * Indique que cette vue n'utilise pas de moteur de template.
     * @return bool false car on génère du HTML directement dans le code.
     */
    public static function use_template(): bool
    {
        return false;
    }

    /**
     * Génère la réponse HTML en appelant la méthode HTTP appropriée.
     * 
     * @param Request $request La requête HTTP reçue.
     * @return Response La réponse HTTP avec le contenu HTML.
     */
    public function render(Request $request): Response
    {
        // Récupère la méthode HTTP en minuscule (get, post, put, etc.)
        $method = strtolower($request->getMethod());
    
        // Appelle dynamiquement la méthode correspondante (ex: $this->get($request))
        $content = $this->$method($request);

        // Retourne une réponse HTTP 200 avec le type de contenu HTML
        return new Response((string)$content, 200, ['Content-Type' => 'text/html']);
    }
}
