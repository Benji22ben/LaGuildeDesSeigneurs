<?php

namespace App\Controller;

use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CharacterServiceInterface;
use Symfony\Component\HttpFoundation\Request;

class CharacterController extends AbstractController
{
    public function __construct(CharacterServiceInterface $characterService)
    {
        $this->characterService = $characterService;
    }

    #[Route('/character/index', name: 'character_index',  methods:['GET','HEAD'])]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('characterIndex', null);
        $characters = $this->characterService->getAll();
        return new JsonResponse($characters);
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
    public function create(Request $request): Response
    {
        $character = $this->characterService->create($request->getContent());
        return new JsonResponse($character->toArray());
    }

    #[Route('/character', name: 'character_redirect_index',  methods:['GET','HEAD'])]
    public function redirectIndex(): Response
    {

        return $this->redirectToRoute('character_index');
    }

    #[Route('/character/modify/{identifier}', name: 'characterModify', requirements:["identifier" => "^([a-z0-9]{40})$"],  methods:['PUT','HEAD'])]
    public function modify(Request $request, Character $character){
        
        $this->denyAccessUnlessGranted('characterModify', $character);
        $character = $this->characterService->modify($character, $request->getContent());
        
        return new JsonResponse($character->toArray());
    }

    #[Route('/character/delete/{identifier}', name: 'characterDelete', requirements:["identifier" => "^([a-z0-9]{40})$"],  methods:['DELETE','HEAD'])]
    public function delete(Character $character){
        
        $this->denyAccessUnlessGranted('characterDelete', $character);
        $response = $this->characterService->delete($character);
        
        return new JsonResponse(array('delete' => $response));
    }

    #[Route('/character/images/{number}', name: 'characterImages', requirements:["identifier" => "^([0-9]{1,2})$"],  methods:['GET','HEAD'])]
    public function images(int $number){
        
        $this->denyAccessUnlessGranted('characterIndex', null);
        
        return new JsonResponse($this->characterService->getImages($number));
    }

    #[Route('/character/images/{kind}/{number}', name: 'characterImagesKind', requirements:["identifier" => "^([0-9]{1,2})$", "kind" => "^(dames|seigneurs|enemis|enemies)$"],  methods:['GET','HEAD'])]
    public function imagesKind(string $kind, int $number){
        
        $this->denyAccessUnlessGranted('characterIndex', null);
        
        return new JsonResponse($this->characterService->getImagesKind($kind, $number));
    }
}
