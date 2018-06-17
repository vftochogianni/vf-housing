<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Infrastructure\Controller;

use DomainException;
use League\Tactician\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use VFHousing\Core\Identity;
use VFHousing\UserBundle\Application\Commands\ActivateUser;
use VFHousing\UserBundle\Application\Commands\DeactivateUser;
use VFHousing\UserBundle\Application\Commands\RegisterUser;
use VFHousing\UserBundle\Application\Commands\UpdateUserCredentials;
use VFHousing\UserBundle\Application\Commands\UpdateUserDetails;
use VFHousing\UserBundle\Domain\UserRepository;

final class UserController extends FOSRestController
{
    const TYPE_CREDENTIALS = 'credentials';
    const TYPE_DETAILS = 'details';
    const TYPE_ACTIVATE = 'activate';
    const TYPE_DEACTIVATE = 'deactivate';
    const TYPE_CREATE = 'create';

    /**
     * @Rest\Get("/users")
     */
    public function getUsers(): View
    {
        $registerUser = new RegisterUser(
            Identity::generate()->getIdentity(),
            'username',
            'PassWord1!',
            'email@email.com',
            'name',
            'lastname',
            '+30',
            '123456789',
            'question',
            'answer',
            false
        );

        /** @var UserRepository $repository */
        $repository = $this->container->get('user_repository.in_memory');

        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get('tactician.commandbus');

        try {
            $commandBus->handle($registerUser);
        } catch (DomainException $exception){
            return new View($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $users = $repository->findAll();
            return new View(!empty($users) ? $users : 'No user found!', Response::HTTP_OK);
        } catch (DomainException $exception) {
            return new View($exception->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\Get("/users/{userIdentity}")
     */
    public function getUserById(string $userIdentity): View
    {
        $registerUser = new RegisterUser(
            $userIdentity,
            'username',
            'PassWord1!',
            'email@email.com',
            'name',
            'lastname',
            '+30',
            '123456789',
            'question',
            'answer',
            false
        );

        /** @var UserRepository $repository */
        $repository = $this->container->get('user_repository.in_memory');

        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get('tactician.commandbus');

        try {
            $commandBus->handle($registerUser);
        } catch (DomainException $exception){
            return new View($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        try {
            return new View($repository->findById($userIdentity), Response::HTTP_OK);
        } catch (DomainException $exception) {
            return new View($exception->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }


    /**
     * @Rest\Post("/users")
     */
    public function postUser(Request $request): View
    {
        $body = json_decode($request->getContent(), true);

        if (!$this->validateRequest($body, self::TYPE_CREATE)) {
            return new View('Invalid request data', Response::HTTP_BAD_REQUEST);
        }

        $userIdentity = Identity::generate();
        $registerUser = new RegisterUser(
            $userIdentity->getIdentity(),
            $body['username'],
            $body['password'],
            $body['email'],
            $body['name'],
            $body['lastName'],
            $body['countryCode'],
            $body['telephoneNumber'],
            $body['securityQuestion'],
            $body['securityAnswer']
        );

        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get('tactician.commandbus');

        try {
            $commandBus->handle($registerUser);
        } catch (DomainException $exception){
            return new View($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new View("User '$userIdentity' has been created", Response::HTTP_CREATED);
    }

    /**
     * @Rest\Put("/users/{userIdentity}/credentials")
     */
    public function putUserCredentials(Request $request, string $userIdentity): View
    {
        $body = json_decode($request->getContent(), true);

        if (!$this->validateRequest($body, self::TYPE_CREDENTIALS)) {
            return new View('Invalid request data', Response::HTTP_BAD_REQUEST);
        }

        $userIdentity = Identity::generate($userIdentity);
        $registerUser = new RegisterUser(
            $userIdentity->getIdentity(),
            'username',
            'PassWord1!',
            'email@email.com',
            'name',
            'lastname',
            '+30',
            '123456789',
            'question',
            'answer',
            false
        );
        $updateUser = new UpdateUserCredentials(
            $userIdentity->getIdentity(),
            $body['username'],
            $body['password']
        );

        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get('tactician.commandbus');

        try {
            $commandBus->handle($registerUser);
            $commandBus->handle($updateUser);
        } catch (DomainException $exception){
            return new View($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new View("User '$userIdentity' has been updated", Response::HTTP_ACCEPTED);
    }

    /**
     * @Rest\Put("/users/{userIdentity}/details")
     */
    public function putUserDetails(Request $request, string $userIdentity): View
    {
        $body = json_decode($request->getContent(), true);

        if (!$this->validateRequest($body, self::TYPE_DETAILS)) {
            return new View('Invalid request data', Response::HTTP_BAD_REQUEST);
        }

        $userIdentity = Identity::generate($userIdentity);
        $registerUser = new RegisterUser(
            $userIdentity->getIdentity(),
            'username',
            'PassWord1!',
            'email@email.com',
            'name',
            'lastname',
            '+30',
            '123456789',
            'question',
            'answer',
            false
        );
        $updateUser = new UpdateUserDetails(
            $userIdentity->getIdentity(),
            $body['email'],
            $body['name'],
            $body['lastName'],
            $body['countryCode'],
            $body['telephoneNumber'],
            $body['securityQuestion'],
            $body['securityAnswer']
        );

        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get('tactician.commandbus');

        try {
            $commandBus->handle($registerUser);
            $commandBus->handle($updateUser);
        } catch (DomainException $exception){
            return new View($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new View("User '$userIdentity' has been updated", Response::HTTP_ACCEPTED);
    }

    /**
     * @Rest\Put("/users/{userIdentity}/activate")
     */
    public function activateUser(Request $request, string $userIdentity): View
    {
        if (!$this->validateRequest(json_decode($request->getContent(), true), self::TYPE_ACTIVATE)){
            return new View('Invalid request data', Response::HTTP_BAD_REQUEST);
        }

        $userIdentity = Identity::generate($userIdentity);
        $registerUser = new RegisterUser(
            $userIdentity->getIdentity(),
            'username',
            'PassWord1!',
            'email@email.com',
            'name',
            'lastname',
            '+30',
            '123456789',
            'question',
            'answer',
            false
        );
        $activateUser = new ActivateUser($userIdentity->getIdentity());

        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get('tactician.commandbus');

        try {
            $commandBus->handle($registerUser);
            $commandBus->handle($activateUser);
        } catch (DomainException $exception){
            return new View($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new View("User '$userIdentity' has been activated", Response::HTTP_ACCEPTED);
    }

    /**
     * @Rest\Put("/users/{userIdentity}/deactivate")
     */
    public function deactivateUser(Request $request, string $userIdentity): View
    {
        if (!$this->validateRequest(json_decode($request->getContent(), true), self::TYPE_DEACTIVATE)){
            return new View('Invalid request data', Response::HTTP_BAD_REQUEST);
        }

        $userIdentity = Identity::generate($userIdentity);
        $registerUser = new RegisterUser(
            $userIdentity->getIdentity(),
            'username',
            'PassWord1!',
            'email@email.com',
            'name',
            'lastname',
            '+30',
            '123456789',
            'question',
            'answer',
            false
        );
        $deactivateUser = new DeactivateUser($userIdentity->getIdentity());

        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get('tactician.commandbus');

        try {
            $commandBus->handle($registerUser);
            $commandBus->handle($deactivateUser);
        } catch (DomainException $exception){
            return new View($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new View("User '$userIdentity' has been deactivated", Response::HTTP_ACCEPTED);
    }

    private function validateRequest(array $body, string $type): bool
    {
        if ($type === self::TYPE_ACTIVATE && !empty($body)) {
            return false;
        }

        if ($type === self::TYPE_DEACTIVATE && !empty($body)) {
            return false;
        }

        if (($type === self::TYPE_CREATE || $type === self::TYPE_CREDENTIALS) && !isset($body['username'])) {
            return false;
        }

        if (($type === self::TYPE_CREATE || $type === self::TYPE_CREDENTIALS) && !isset($body['password'])) {
            return false;
        }

        if (($type === self::TYPE_CREATE || $type === self::TYPE_DETAILS) && !isset($body['email'])) {
            return false;
        }

        if (($type === self::TYPE_CREATE || $type === self::TYPE_DETAILS) && !isset($body['name'])) {
            return false;
        }

        if (($type === self::TYPE_CREATE || $type === self::TYPE_DETAILS) && !isset($body['lastName'])) {
            return false;
        }

        if (($type === self::TYPE_CREATE || $type === self::TYPE_DETAILS) && !isset($body['countryCode'])) {
            return false;
        }

        if (($type === self::TYPE_CREATE || $type === self::TYPE_DETAILS) && !isset($body['telephoneNumber'])) {
            return false;
        }

        if (($type === self::TYPE_CREATE || $type === self::TYPE_DETAILS) && !isset($body['securityQuestion'])) {
            return false;
        }

        if (($type === self::TYPE_CREATE || $type === self::TYPE_DETAILS) && !isset($body['securityAnswer'])) {
            return false;
        }

        return true;
    }
}
