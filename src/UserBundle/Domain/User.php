<?php

namespace VFHousing\UserBundle\Domain;

use VFHousing\Core\Identity;
use VFHousing\UserBundle\Domain\Events\UserActivated;
use VFHousing\UserBundle\Domain\Events\UserCredentialsUpdated;
use VFHousing\UserBundle\Domain\Events\UserDeactivated;
use VFHousing\UserBundle\Domain\Events\UserRegistered;
use VFHousing\UserBundle\Domain\Events\UserDetailsUpdated;
use VFHousing\UserBundle\Domain\User\Email;
use VFHousing\UserBundle\Domain\User\Name;
use VFHousing\UserBundle\Domain\User\Password;
use VFHousing\UserBundle\Domain\User\SecurityQuestion;
use VFHousing\UserBundle\Domain\User\TelephoneNumber;
use VFHousing\UserBundle\Domain\User\Username;

final class User
{
    /** @var DomainEvent[] */
    private $recordedEvents;

    /** @var Identity */
    private $identity;

    /** @var Username */
    private $username;

    /** @var Password */
    private $password;

    /** @var Email */
    private $email;

    /** @var Name */
    private $name;

    /** @var TelephoneNumber */
    private $telephoneNumber;

    /** @var SecurityQuestion */
    private $securityQuestion;

    /** @var bool */
    private $isEnabled;

    public function getRecordedEvents(): array
    {
        return $this->recordedEvents;
    }

    public function clearRecordedEvents()
    {
        $this->recordedEvents = [];
    }

    public function register(
        Identity $userIdentity,
        Username $username,
        Password $password,
        Email $email,
        Name $name,
        TelephoneNumber $telephoneNumber,
        SecurityQuestion $securityQuestion
    ) {
        $this->applyAndRecordThat(
            new UserRegistered(
                Identity::generate(),
                $userIdentity,
                $username,
                $password,
                $email,
                $name,
                $telephoneNumber,
                $securityQuestion
            )
        );
    }

    public function updateDetails(
        Identity $userIdentity,
        Email $email,
        Name $name,
        TelephoneNumber $telephoneNumber,
        SecurityQuestion $securityQuestion
    ) {
        $this->applyAndRecordThat(
            new UserDetailsUpdated(
                Identity::generate(),
                $userIdentity,
                $email,
                $name,
                $telephoneNumber,
                $securityQuestion
            )
        );
    }

    public function updateCredentials(
        Identity $userIdentity,
        Username $username,
        Password $password
    ) {
        $this->applyAndRecordThat(
            new UserCredentialsUpdated(
                Identity::generate(),
                $userIdentity,
                $username,
                $password
            )
        );
    }

    public function deactivate(Identity $userIdentity)
    {
        $this->applyAndRecordThat(new UserDeactivated(Identity::generate(), $userIdentity));
    }

    public function activate(Identity $userIdentity)
    {
        $this->applyAndRecordThat(new UserActivated(Identity::generate(), $userIdentity));
    }

    private function applyAndRecordThat(DomainEvent $aDomainEvent)
    {
        $this->recordThat($aDomainEvent);
        $this->apply($aDomainEvent);
    }

    private function recordThat(DomainEvent $aDomainEvent)
    {
        $this->recordedEvents[] = $aDomainEvent;
    }

    private function apply(DomainEvent $anEvent)
    {
        $method = 'apply' . $anEvent->getEventName();
        $this->$method($anEvent);
    }

    private function applyUserRegistered(UserRegistered $event)
    {
        $this->identity = $event->getUserIdentity();
        $this->username = $event->getUsername();
        $this->password = $event->getPassword();
        $this->email = $event->getEmail();
        $this->name = $event->getName();
        $this->telephoneNumber = $event->getTelephoneNumber();
        $this->securityQuestion = $event->getSecurityQuestion();
        $this->isEnabled = $event->isEnabled();
    }

    private function applyUserDetailsUpdated(UserDetailsUpdated $event)
    {
        $this->identity = $event->getUserIdentity();
        $this->email = $event->getEmail();
        $this->name = $event->getName();
        $this->telephoneNumber = $event->getTelephoneNumber();
        $this->securityQuestion = $event->getSecurityQuestion();
    }

    private function applyUserCredentialsUpdated(UserCredentialsUpdated $event)
    {
        $this->identity = $event->getUserIdentity();
        $this->password = $event->getPassword();
        $this->username = $event->getUsername();
    }

    private function applyUserDeactivated(UserDeactivated $event)
    {
        $this->isEnabled = false;
    }

    private function applyUserActivated(UserActivated $event)
    {
        $this->isEnabled = true;
    }
}
