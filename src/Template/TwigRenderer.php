<?php

declare(strict_types=1);

namespace Framework312\Template;

class TwigRenderer implements Renderer
{
    private \Twig\Environment $twig; // Instance de l'environnement Twig
    private string $templateDir;

    /**
     * Constructeur qui initialise Twig avec le répertoire des templates.
     * @param string $templateDir Le répertoire où se trouvent les templates Twig.
     */
    public function __construct(string $templateDir)
    {
        $this->templateDir = $templateDir;
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
        // On s'assure que les données sont un tableau
        $context = is_array($data) ? $data : (array)$data;

        // Si le template n'a pas d'extension .twig, on l'ajoute (convention)
        if (!str_ends_with($template, '.twig')) {
            $template .= '.twig';
        }

        return $this->twig->render($template, $context);
    }

    /**
     * Enregistre un tag pour un sous-dossier de templates.
     * @param string $tag Le nom du tag/sous-dossier à enregistrer.
     */
    public function register(string $tag)
    {
        /** @var \Twig\Loader\FilesystemLoader $loader */
        $loader = $this->twig->getLoader();

        // Extrait le nom court de la classe depuis le nom complet avec namespace
        // Exemple : "App\Controller\UserController" devient "UserController"
        $folderName = (false !== $lastSlashPos = strrpos($tag, '\\')) ? substr($tag, $lastSlashPos + 1) : $tag;

        // Construit le chemin complet vers le sous-dossier des templates
        $path = rtrim($this->templateDir, '/') . '/' . $folderName;

        // Vérifie que le dossier existe avant de l'ajouter au loader
        if (is_dir($path)) {
            // Ajoute le chemin au loader pour que Twig puisse trouver les templates dans ce dossier
            // Les templates de ce dossier seront alors accessibles directement par leur nom
            $loader->addPath($path);
        }
    }
}