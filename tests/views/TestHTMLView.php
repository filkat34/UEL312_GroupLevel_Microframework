<?php

declare(strict_types=1);

use Framework312\Router\View\HTMLView;
use Framework312\Router\Request;

class TestHTMLView extends HTMLView
{
    protected function get(Request $request): mixed
    {
        return '<html><body><h1>Groupe : Level</h1><p>Membres : Filippos, Kamo, Mathieu, Mathilde</p></body></html>';
    }
}
