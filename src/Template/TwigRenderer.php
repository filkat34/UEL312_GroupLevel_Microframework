<?php

declare(strict_types=1);

namespace Framework312\Template;

class TwigRenderer implements Renderer
{
    private \Twig\Environment $twig; // Instance de l'environnement Twig

    /**
     * Constructeur qui initialise Twig avec le répertoire des templates.
     * @param string $templateDir Le répertoire où se trouvent les templates Twig.
     */
    public function __construct(string $templateDir)
    {
        $loader = new \Twig\Loader\FilesystemLoader($templateDir);
        $this->twig = new \Twig\Environment($loader);
    }

    /**
     * Rendu du template avec les données fournies.
     * @param mixed $data Les données à passer au template.
     * @param string $template Le nom du template à rendre.
     * @return string Le contenu rendu du template.
     */
    public function render(mixed $data, string $template): string
    {
        // TO DO
    }

    /**
     * Enregistre un tag pour un sous-dossier de templates.
     * @param string $tag Le nom du tag/sous-dossier à enregistrer.
     */
    public function register(string $tag)
    {
        // TO DO
    }
}