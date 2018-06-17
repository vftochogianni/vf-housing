<?php

namespace VFHousing\Tests\Unit\UserBundle\Domain\User;

use DomainException;
use PHPUnit\Framework\TestCase;
use VFHousing\UserBundle\Domain\User\SecurityQuestion;

class SecurityQuestionTest extends TestCase
{
    public function testSet_ShouldReturnSecurityQuestion()
    {
        $securityQuestion = SecurityQuestion::set('question', 'answer');

        $this->assertEquals('question', $securityQuestion->getQuestion());
        $this->assertEquals('answer', $securityQuestion->getAnswer());
    }

    public function testSet_ShouldThrowException_WhenQuestionIsNotSet()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Question should not be empty');

        SecurityQuestion::set('', 'answer');
    }

    public function testSet_ShouldThrowException_WhenAnswerIsNotSet()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Answer should not be empty');

        SecurityQuestion::set('question', '');
    }
}
