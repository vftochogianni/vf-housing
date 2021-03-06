<?php

namespace VFHousing\UserBundle\Infrastructure\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Exception;
use VFHousing\UserBundle\Domain\Exceptions\UserDoesNotExist;
use VFHousing\UserBundle\Domain\Exceptions\UserExists;
use VFHousing\UserBundle\Domain\User\Email;
use VFHousing\Core\Identity;
use VFHousing\UserBundle\Domain\User\Name;
use VFHousing\UserBundle\Domain\User\SecurityQuestion;
use VFHousing\UserBundle\Domain\User\TelephoneNumber;
use VFHousing\UserBundle\Domain\User\Username;
use VFHousing\UserBundle\Domain\Exceptions\UserException;
use VFHousing\UserBundle\Domain\UserProjection;
use VFHousing\UserBundle\Domain\UserRepository;

final class DoctrineUserRepository extends EntityRepository implements UserRepository
{
    /** @var EntityManager */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(UserProjection::class));
        $this->entityManager = $entityManager;
    }

    public function findById(Identity $userId): UserProjection
    {
        $result = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(UserProjection::class, 'u')
            ->where('u.identity = :identity')
            ->setParameter('identity', $userId->getIdentity())
            ->getQuery()
            ->getOneOrNullResult();

        if (empty($result)) {
            throw UserDoesNotExist::with('identity', $userId->getIdentity());
        }

        return $result;
    }

    public function findByEmail(Email $userEmail): UserProjection
    {
        $result = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(UserProjection::class, 'u')
            ->where('u.email = :email')
            ->setParameter('email', $userEmail->getEmail())
            ->getQuery()
            ->getOneOrNullResult();

        if (empty($result)) {
            throw UserDoesNotExist::with('email', $userEmail->getEmail());
        }
        return $result;
    }

    public function findByUsername(Username $username): UserProjection
    {
        $result = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(UserProjection::class, 'u')
            ->where('u.username = :username')
            ->setParameter('username', $username->getUsername())
            ->getQuery()
            ->getOneOrNullResult();

        if (empty($result)) {
            throw UserDoesNotExist::with('username', $username->getUsername());
        }

        return $result;
    }

    public function findAll(): array
    {
         return $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(UserProjection::class, 'u')
            ->getQuery()
            ->getArrayResult();
    }

    public function add(UserProjection $user)
    {
        $this->entityManager->beginTransaction();

        try {
            $this->checkAvailability($user);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $exception) {
            $this->entityManager->rollback();

            throw UserException::causedBy('An error occurred while registering user,', $exception);
        }
    }

    public function update(Identity $userId, UserProjection $updatedUser)
    {
        $this->entityManager->beginTransaction();

        try {
            /** @var UserProjection $user */
            $user = $this->findById(Identity::generate($updatedUser->getIdentity()));

            if ($user->getEmail() !== $updatedUser->getEmail()) {
                $this->checkAvailabilityByEmail($updatedUser);
            }

            if ($user->getTelephoneNumber() !== $updatedUser->getTelephoneNumber()) {
                $this->checkAvailabilityByTelephoneNumber($updatedUser);
            }

            if ($user->getUsername() !== $updatedUser->getUsername()) {
                $this->checkAvailabilityByTelephoneNumber($updatedUser);
            }

            $this->entityManager->merge($updatedUser);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $exception) {
            $this->entityManager->rollback();

            throw UserException::causedBy("An error occurred while updating user '{$userId}',", $exception);
        }
    }

    public function findByTelephoneNumber(TelephoneNumber $telephoneNumber): UserProjection
    {
        $result = $this->entityManager->createQueryBuilder()
                                      ->select('u')
                                      ->from(UserProjection::class, 'u')
                                      ->where('u.telephoneNumber = :telephoneNumber')
                                      ->andWhere('u.isEnabled = :isEnabled')
                                      ->setParameter('telephoneNumber', $telephoneNumber->getTelephoneNumber())
                                      ->setParameter('isEnabled', 1)
                                      ->getQuery()
                                      ->getOneOrNullResult();

        if (empty($result)) {
            throw UserDoesNotExist::with('telephone number', $telephoneNumber->getTelephoneNumber());
        }

        return $result;
    }

    public function checkAvailability(UserProjection $user)
    {
        $this->checkAvailabilityByEmail($user);
        $this->checkAvailabilityByUsername($user);
        $this->checkAvailabilityByTelephoneNumber($user);
    }

    private function checkAvailabilityByEmail(UserProjection $user): void
    {
        try {
            $this->findByEmail(Email::set($user->getEmail()));

            throw UserExists::with($user->getEmail());
        } catch (UserDoesNotExist $exception) {
            // user is available
        }
    }

    private function checkAvailabilityByUsername(UserProjection $user): void
    {
        try {
            $this->findByUsername(Username::set($user->getUsername()));

            throw UserExists::with($user->getUsername());
        } catch (UserDoesNotExist$exception) {
            // user is available
        }
    }

    private function checkAvailabilityByTelephoneNumber(UserProjection $user): void
    {
        try {
            $this->findByTelephoneNumber(TelephoneNumber::deserialize($user->getTelephoneNumber()));

            throw UserExists::with($user->getTelephoneNumber());
        } catch (UserDoesNotExist $exception) {
            // user is available
        }
    }
}
