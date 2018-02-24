<?php

declare(strict_types=1);

namespace App\FrontPage\Presentation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FrontPageController
 * @package App\src\FrontPage\Presentation
 */
final class FrontPageController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        $content = 'Hello ' . $request->get('name', 'default');

        return new Response($content);
    }
}
