<?php

declare(strict_types=1);

namespace Framework312\Router\View;

use Framework312\Router\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class JSONView extends BaseView
{
    /**
     * Indique que cette vue n'utilise pas de moteur de template.
     * @return bool false car on génère du JSON directement.
     */
    public static function use_template(): bool
    {
        return false;
    }

    /**
     * Génère la réponse JSON en appelant la méthode HTTP appropriée.
     *
     * @param Request $request La requête HTTP reçue.
     * @return Response La réponse HTTP avec le contenu JSON.
     */
    public function render(Request $request): Response
    {
        // Récupère la méthode HTTP en minuscule (get, post, put, etc.)
        $method = strtolower($request->getMethod());

        // Appelle dynamiquement la méthode correspondante qui retourne des données
        $data = $this->$method($request);

        // Encode les données en JSON avec formatage lisible et sans échapper les caractères Unicode
        $content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Retourne une réponse HTTP 200 avec le type de contenu JSON
        return new Response((string)$content, 200, ['Content-Type' => 'application/json']);
    }
}
