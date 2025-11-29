<?php

declare(strict_types=1);

use Framework312\Router\View\HTMLView;
use Framework312\Router\Request;

class TestHTMLView extends HTMLView
{
    protected function get(Request $request): mixed
    {
        return '<html>
        <body>
        <h3>Groupe LEVEL :</h1>
        <ul>
            <li>Filippos</li>
            <li>Kamo</li>
            <li>Mathieu</li>
            <li>Mathilde</li>
        </ul>
        </body>
        </html>';
    }
}
