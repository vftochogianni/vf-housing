<?php

namespace VFHousing\UserBundle\Infrastructure\Controller;

use DomainException;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use VFHousing\Core\Identity;
use VFHousing\UserBundle\Application\Commands\ActivateUser;
use VFHousing\UserBundle\Application\Commands\DeactivateUser;
use VFHousing\UserBundle\Application\Commands\RegisterUser;
use VFHousing\UserBundle\Application\Commands\UpdateUserCredentials;
use VFHousing\UserBundle\Application\Commands\UpdateUserDetails;
use VFHousing\UserBundle\Domain\User\Email;
use VFHousing\UserBundle\Domain\UserProjection;
use VFHousing\UserBundle\Domain\UserRepository;

class DefaultController extends Controller
{
    /**
     * @Route("/user/register")
     */
    public function registerAction()
    {
        $registerUser = new RegisterUser(
            'userId',
            'username',
            'PassWord1!',
            'email2@email.com',
            'name',
            'lastname',
            '+30',
            '123456789',
            'question',
            'answer',
            false
        );

        /** @var UserRepository $repository */
        $repository = $this->container->get('user_repository.doctrine');

        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get('tactician.commandbus');

        try {
            $commandBus->handle($registerUser);
        } catch (DomainException $exception){
            return new JsonResponse($exception->getMessage(), 400);
        }

        $data = $repository->findByEmail(Email::set('email2@email.com'));

        return new JsonResponse($data->serialize(), 202);
    }

    /**
     * @Route("/user/update/details")
     */
    public function updateDetailsAction()
    {
        $updateUserDetails = new UpdateUserDetails(
            'userId',
            'email@email.com',
            'name',
            'lastName',
            '+30',
            '123456789',
            'question',
            'answer'
        );

        /** @var UserRepository $repository */
        $repository = $this->container->get('user_repository.doctrine');

        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get('tactician.commandbus');

        try {
            $commandBus->handle($updateUserDetails);
        } catch (DomainException $exception){
            return new JsonResponse($exception->getMessage(), 400);
        }

        $data = $repository->findByEmail(Email::set('email@email.com'));

        return new JsonResponse($data->serialize(), 200);
    }

    /**
     * @Route("/user/update/credentials")
     */
    public function updateCredentialsAction()
    {
        $registerUser = new UpdateUserCredentials('userId', 'username1', 'pAssword!1@2');

        /** @var UserRepository $repository */
        $repository = $this->container->get('user_repository.doctrine');

        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get('tactician.commandbus');

        try {
            $commandBus->handle($registerUser);
        } catch (DomainException $exception){
            return new JsonResponse($exception->getMessage(), 400);
        }

        $data = $repository->findByEmail(Email::set('email@email.com'));

        return new JsonResponse($data->serialize(), 200);
    }

    /**
     * @Route("/user/activate")
     */
    public function activateAction()
    {
        $registerUser = new ActivateUser('userId');

        /** @var UserRepository $repository */
        $repository = $this->container->get('user_repository.doctrine');

        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get('tactician.commandbus');

        try {
            $commandBus->handle($registerUser);
        } catch (DomainException $exception){
            return new JsonResponse($exception->getMessage(), 400);
        }

        $data = $repository->findByEmail(Email::set('email@email.com'));

        return new JsonResponse($data->serialize(), 200);
    }

    /**
     * @Route("/user/deactivate")
     */
    public function deactivateAction()
    {
        $registerUser = new DeactivateUser('userId');

        /** @var UserRepository $repository */
        $repository = $this->container->get('user_repository.doctrine');

        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get('tactician.commandbus');

        try {
            $commandBus->handle($registerUser);
        } catch (DomainException $exception){
            return new JsonResponse($exception->getMessage(), 400);
        }

        $data = $repository->findByEmail(Email::set('email@email.com'));

        return new JsonResponse($data->serialize(), 200);
    }
}
