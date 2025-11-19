<?php

declare(strict_types=1);

use Framework312\Router\View\JSONView;
use Framework312\Router\Request;

class TestJSONView extends JSONView
{
    protected function get(Request $request): mixed
    {
        return [
            'Groupe' => 'Level',
            'Membres' => 'Filippos, Kamo, Mathieu, Mathilde',
            'timestamp' => time()
        ];
    }
}
