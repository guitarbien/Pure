<?php

declare(strict_types=1);

namespace App\Submission\Presentation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SubmissionController
 * @package App\Submission\Presentation
 */
final class SubmissionController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        $content = 'Submission Controller';

        return new Response($content);
    }
}
