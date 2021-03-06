<?php

declare(strict_types=1);

namespace App\Submission\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use App\Framework\Csrf\Token;
use App\Framework\RoleBasedAccessControl\AuthenticatedUser;
use App\Submission\Application\SubmitLink;

/**
 * Class SubmissionForm
 * @package App\Submission\Presentation
 */
final class SubmissionForm
{
    /** @var StoredTokenValidator */
    private $storedTokenValidator;

    /** @var string */
    private $token;

    /** @var string */
    private $title;

    /** @var string */
    private $url;

    /**
     * SubmissionForm constructor.
     * @param StoredTokenValidator $storedTokenValidator
     * @param string $token
     * @param string $title
     * @param string $url
     */
    public function __construct(StoredTokenValidator $storedTokenValidator, string $token, string $title, string $url)
    {
        $this->storedTokenValidator = $storedTokenValidator;
        $this->token                = $token;
        $this->title                = $title;
        $this->url                  = $url;
    }

    /**
     * @return string[]
     */
    public function getValidationErrors(): array
    {
        $errors = [];

        if (!$this->storedTokenValidator->validate('submission', new Token($this->token))) {
            $errors[] = 'Invalid token';
        }

        if (strlen($this->title) < 1 || strlen($this->title) > 200) {
            $errors[] = 'Title must be between 1 and 200 characters';
        }

        if (strlen($this->url) < 1 || strlen($this->url) > 200) {
            $errors[] = 'URL must be between 1 and 200 characters';
        }

        return $errors;
    }

    /**
     * @param AuthenticatedUser $author
     * @return SubmitLink
     */
    public function toCommand(AuthenticatedUser $author): SubmitLink
    {
        return new SubmitLink($author->getId(), $this->url, $this->title);
    }
}
