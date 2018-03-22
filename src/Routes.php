<?php

declare(strict_types=1);

use App\FrontPage\Presentation\FrontPageController;
use App\Submission\Presentation\SubmissionController;
use App\User\Presentation\RegistrationController;

return [
    [
        'GET',
        '/',
        FrontPageController::class . '#show',
    ],
    [
        'GET',
        '/submit',
        SubmissionController::class . '#show',
    ],
    [
        'POST',
        '/submit',
        SubmissionController::class . '#submit',
    ],
    [
        'GET',
        '/register',
        RegistrationController::class . '#show',
    ],
    [
        'POST',
        '/register',
        RegistrationController::class . '#register',
    ],
];
