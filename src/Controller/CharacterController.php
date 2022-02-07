<?php

namespace App\Controller;

use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CharacterServiceInterface;

class CharacterController extends AbstractController
{
    public function __construct(CharacterServiceInterface $characterService)
    {
        $this->characterService = $characterService;
    }

    #[Route('/character', name: 'character')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CharacterController.php',
        ]);
    }

    #[Route('/character/display', name: 'display',  methods:['GET','HEAD'])]
    public function display(): Response
    {
        $character = New Character();
        // dump($character);
        // dd($character->toArray());
        return new JsonResponse($character->toArray());
    }

    #[Route('/character/create', name: 'character_create',  methods:['POST','HEAD'])]
    public function create(): Response
    {
        $character = $this->characterService->create();
        return new JsonResponse($character->toArray());
    }
}
