<?php

declare(strict_types=1);

namespace App\Submission\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SubmissionFormFactory
 * @package App\Submission\Presentation
 */
final class SubmissionFormFactory
{
    /** @var StoredTokenValidator */
    private $storedTokenValidator;

    /**
     * SubmissionFormFactory constructor.
     * @param StoredTokenValidator $storedTokenValidator
     */
    public function __construct(StoredTokenValidator $storedTokenValidator)
    {
        $this->storedTokenValidator = $storedTokenValidator;
    }

    /**
     * @param Request $request
     * @return SubmissionForm
     */
    public function createFromRequest(Request $request): SubmissionForm
    {
        return new SubmissionForm(
            $this->storedTokenValidator,
            (string)$request->get('token'),
            (string)$request->get('title'),
            (string)$request->get('url')
        );
    }
}
