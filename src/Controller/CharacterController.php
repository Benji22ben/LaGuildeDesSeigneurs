<?php

namespace App\Controller;

use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CharacterServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class CharacterController extends AbstractController
{
    private $characterService;

    public function __construct(CharacterServiceInterface $characterService)
    {
        $this->characterService = $characterService;
    }

    #[Route('/character/index', name: 'character_index', methods:['GET','HEAD'])]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(ref=@Model(type=Character::class))
     *     )
     * )
     * @OA\Response(
     *     response=403,
     *     description="Access denied",
     * )
     * @OA\Tag(name="Character")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('characterIndex', null);
        $characters = $this->characterService->getAll();
        
        return JsonResponse::fromJsonString($this->characterService->serializeJson($characters));
    }

    #[Route('/character/intelligence/{level}', name: 'character_intelligence', requirements:["intelligence" => "\d+"], methods:['GET','HEAD'])]
    /**
     * Displays only the characters who are greater than
     *
     * @OA\Parameter(
     *     name="intelligence",
     *     in="path",
     *     description="Intelligence that we want to be lower than the characters shown (has to be an integer)",
     *     required=true,
     * )
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @Model(type=Character::class)
     * )
     * @OA\Response(
     *     response=403,
     *     description="Access denied",
     * )
     * @OA\Response(
     *     response=404,
     *     description="Not Found",
     * )
     * @OA\Tag(name="Character")
     */
    public function indexIntelligenceLevel(int $level): Response
    {
        // $this->denyAccessUnlessGranted('characterIndex', null);

        $characters = $this->characterService->getIntelligenceIsGreaterThanOrEqual($level);
        
        return JsonResponse::fromJsonString($this->characterService->serializeJson($characters));
    }

    #[Route('/character/display/{identifier}', name: 'character_display', requirements:["identifier" => "^([a-z0-9]{40})$"], methods:['GET','HEAD'])]
    #[Entity('character', expr:"repository.findOneByIdentifier(identifier)")]
    /**
     * Displays the Character
     *
     * @OA\Parameter(
     *     name="identifier",
     *     in="path",
     *     description="identifier for the Character",
     *     required=true,
     * )
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @Model(type=Character::class)
     * )
     * @OA\Response(
     *     response=403,
     *     description="Access denied",
     * )
     * @OA\Response(
     *     response=404,
     *     description="Not Found",
     * )
     * @OA\Tag(name="Character")
     */
    public function display(Character $character): Response
    {
        // $character = New Character();
        // dump($character);
        // dd($character->toArray());
        $this->denyAccessUnlessGranted("characterDisplay", $character);

        return JsonResponse::fromJsonString($this->characterService->serializeJson($character));
    }

    #[Route('/character/create', name: 'characterCreate', methods:['POST','HEAD'])]
    /**
     * 
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @Model(type=Character::class)
     * )
     * @OA\Response(
     *     response=403,
     *     description="Access denied",
     * )
     * @OA\RequestBody(
     *     request="Character",
     *     description="Data for the Character",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/Character")
     *     )
     * )
     * @OA\Tag(name="Character")
     */
    public function create(Request $request): Response
    {
        $character = $this->characterService->create($request->getContent());
        return JsonResponse::fromJsonString($this->characterService->serializeJson($character));
    }

    #[Route('/character', name: 'character_redirect_index', methods:['GET','HEAD'])]
    /**
    * @OA\Response(
    *     response=302,
    *     description="Redirect",
    * )
    * @OA\Tag(name="Character")
    */
    public function redirectIndex(): Response
    {
        return $this->redirectToRoute('character_index');
    }

    #[Route('/character/modify/{identifier}', name: 'characterModify', requirements:["identifier" => "^([a-z0-9]{40})$"], methods:['PUT','HEAD'])]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @Model(type=Character::class)
     * )
     * @OA\Response(
     *     response=403,
     *     description="Access denied",
     * )* @OA\Parameter(
     *     name="identifier",
     *     in="path",
     *     description="identifier for the Character",
     *     required=true
     * )
     * @OA\RequestBody(
     *     request="Character",
     *     description="Data for the Character",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/Character")
     *     )
     * )
     *  @OA\Tag(name="Character")
     */
    public function modify(Request $request, Character $character)
    {
        $this->denyAccessUnlessGranted('characterModify', $character);
        $character = $this->characterService->modify($character, $request->getContent());

        return JsonResponse::fromJsonString($this->characterService->serializeJson($character));
    }

    #[Route('/character/delete/{identifier}', name: 'characterDelete', requirements:["identifier" => "^([a-z0-9]{40})$"], methods:['DELETE','HEAD'])]
    /** 
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\Schema(
     *         @OA\Property(property="delete", type="boolean"),
     *     )
     * )
     * @OA\Response(
     *     response=403,
     *     description="Access denied",
     * )
     * @OA\Parameter(
     *     name="identifier",
     *     in="path",
     *     description="identifier for the Character",
     *     required=true
     * )
     * @OA\Tag(name="Character")
     */
    public function delete(Character $character)
    {
        $this->denyAccessUnlessGranted('characterDelete', $character);
        $response = $this->characterService->delete($character);

        return new JsonResponse(array('delete' => $response));
    }

    #[Route('/character/images/{number}', name: 'characterImages', requirements:["identifier" => "^([0-9]{1,2})$"], methods:['GET','HEAD'])]
    public function images(int $number)
    {
        $this->denyAccessUnlessGranted('characterIndex', null);

        return new JsonResponse($this->characterService->getImages($number));
    }

    #[Route('/character/images/{kind}/{number}', name: 'characterImagesKind', requirements:["identifier" => "^([0-9]{1,2})$", "kind" => "^(dames|seigneurs|enemis|enemies)$"], methods:['GET','HEAD'])]
    public function imagesKind(string $kind, int $number)
    {
        $this->denyAccessUnlessGranted('characterIndex', null);

        return new JsonResponse($this->characterService->getImagesKind($kind, $number));
    }
}
