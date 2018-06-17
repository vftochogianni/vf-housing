<?php

namespace VFHousing\UserBundle\Domain\User;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use DomainException;

final class SecurityQuestion
{
    /** @var string */
    private $answer;

    /** @var string */
    private $question;

    private function __construct(string $question, string $answer)
    {
        $this->question = $question;
        $this->answer = $answer;
    }

    public static function set(string $question, string $answer): self
    {
        try {
            self::guard($question, $answer);
        } catch (InvalidArgumentException $exception) {
            throw new DomainException($exception->getMessage());
        }

        return new self($question, $answer);
    }

    private static function guard(string $question, string $answer)
    {
        Assertion::notEmpty($question, 'Question should not be empty');

        Assertion::notEmpty($answer, 'Answer should not be empty');
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }

    public function getQuestion(): string
    {
        return $this->question;
    }


}