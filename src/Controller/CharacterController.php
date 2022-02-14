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

    #[Route('/character/display/{identifier}', name: 'character_display', requirements:["identifier" => "^([a-z0-9]{40})$"], methods:['GET','HEAD'])]
    public function display(Character $character): Response
    {
        // $character = New Character();
        // dump($character);
        // dd($character->toArray());
        $this->denyAccessUnlessGranted("characterDisplay", $character);

        return new JsonResponse($character->toArray());
    }

    #[Route('/character/create', name: 'characterCreate',  methods:['POST','HEAD'])]
    public function create(): Response
    {
        $character = $this->characterService->create();
        return new JsonResponse($character->toArray());
    }

    #[Route('/character', name: 'character_redirect_index',  methods:['GET','HEAD'])]
    public function redirectIndex(): Response
    {
        return $this->redirectToRoute('characterIndex');
    }

    #[Route('/character/index', name: 'characterIndex',  methods:['GET','HEAD'])]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('characterIndex', null);
        $characters = $this->characterService->getAll();
        return new JsonResponse($characters);
    }

    #[Route('/character/modify/{identifier}', name: 'characterModify', requirements:["identifier" => "^([a-z0-9]{40})$"],  methods:['PUT','HEAD'])]
    public function modify(Character $character){
        
        $this->denyAccessUnlessGranted('characterModify', $character);
        $character = $this->characterService->modify($character);
        
        return new JsonResponse($character->toArray());
    }

    #[Route('/character/delete/{identifier}', name: 'characterDelete', requirements:["identifier" => "^([a-z0-9]{40})$"],  methods:['DELETE','HEAD'])]
    public function delete(Character $character){
        
        $this->denyAccessUnlessGranted('characterDelete', $character);
        $response = $this->characterService->delete($character);
        
        return new JsonResponse(array('delete' => $response));
    }
}
