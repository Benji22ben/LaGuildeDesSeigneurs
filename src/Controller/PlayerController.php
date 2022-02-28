<?php

namespace App\Controller;

use App\Entity\Player;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PlayerServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class PlayerController extends AbstractController
{
    private $playerService;

    public function __construct(PlayerServiceInterface $playerService)
    {
        $this->playerService = $playerService;
    }

    #[Route('/player/create', name: 'playerCreate', methods:['POST','HEAD'])]
    public function create(Request $request): Response
    {
        $player = $this->playerService->create($request->getContent());

        return JsonResponse::fromJsonString($this->playerService->serializeJson($player));
        // return new JsonResponse($player->toArray());
    }

    #[Route('/player/display/{identifier}', name: 'player_display', requirements:["identifier" => "^([a-z0-9]{40})$"], methods:['GET','HEAD'])]
    #[Entity('character', expr:"repository.findOneByIdentifier(identifier)")]
    public function display(Player $player): Response
    {
        // $player = New player();
        // dump($player);
        // dd($player->toArray());
        $this->denyAccessUnlessGranted("playerDisplay", $player);

        return JsonResponse::fromJsonString($this->playerService->serializeJson($player));
        // return new JsonResponse($player->toArray());
    }

    #[Route('/player', name: 'player_redirect_index', methods:['GET','HEAD'])]
    public function redirectIndex(): Response
    {
        return $this->redirectToRoute('player_index');
    }

    #[Route('/player/index', name: 'player_index', methods:['GET','HEAD'])]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('playerIndex', null);
        $players = $this->playerService->getAll();
        // return new JsonResponse($players);
        return JsonResponse::fromJsonString($this->playerService->serializeJson($players));
    }

    #[Route('/player/modify/{identifier}', name: 'playerModify', requirements:["identifier" => "^([a-z0-9]{40})$"], methods:['PUT','HEAD'])]
    public function modify(Request $request, Player $player)
    {
        $this->denyAccessUnlessGranted('playerModify', $player);
        $player = $this->playerService->modify($player, $request->getContent());

        return JsonResponse::fromJsonString($this->playerService->serializeJson($player));
        // return new JsonResponse($player->toArray());
    }

    #[Route('/player/delete/{identifier}', name: 'playerDelete', requirements:["identifier" => "^([a-z0-9]{40})$"], methods:['DELETE','HEAD'])]
    public function delete(player $player)
    {
        $this->denyAccessUnlessGranted('playerDelete', $player);
        $response = $this->playerService->delete($player);

        return new JsonResponse(array('delete' => $response));
    }
}
