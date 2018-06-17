<?php

namespace VFHousing\UserBundle\Infrastructure\Controller;

use DomainException;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use VFHousing\UserBundle\Application\Commands\ActivateUser;
use VFHousing\UserBundle\Application\Commands\DeactivateUser;
use VFHousing\UserBundle\Application\Commands\RegisterUser;
use VFHousing\UserBundle\Application\Commands\UpdateUserDetails;
use VFHousing\UserBundle\Domain\UserProjection;
use VFHousing\UserBundle\Domain\UserRepository;

class DefaultController extends Controller
{
    /**
     * @Route("/user")
     */
    public function indexAction()
    {
        $registerUser = new RegisterUser(
            'userId',
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
            'userId',
            'email2@email.com',
            'name',
            'lastName',
            '+30',
            '987654321',
            'question',
            'answer',
            true
        );

        $deactivateUser = new DeactivateUser('userId');
        $activateUser = new ActivateUser('userId');

        /** @var UserRepository $repository */
        $repository = $this->container->get('user_repository.in_memory');

        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get('tactician.commandbus');

        try {
            $commandBus->handle($registerUser);
            $commandBus->handle($updateUser);
        } catch (DomainException $exception){
            return new JsonResponse($exception->getMessage(), 400);
        }

        var_dump($repository->findAll());


        $commandBus->handle($updateUser);
        $commandBus->handle($deactivateUser);
        $commandBus->handle($activateUser);
        /** @var UserProjection $data */
        $data = $repository->findById('userId');

        return new JsonResponse($data->serialize(), 202);
    }
}
