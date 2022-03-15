<?php

namespace App\Service;

use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CharacterRepository;
use DateTime;
use Symfony\Component\Finder\Finder;
use LogicException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Form\CharacterType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Event\CharacterEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CharacterService implements CharacterServiceInterface
{
    private $characterRepository;
    private $formFactory;
    private $em;
    private $validator;
    private $dispatcher;

    public function __construct(
        CharacterRepository $characterRepository,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        ValidatorInterface $validator,
        EventDispatcherInterface $dispatcher,
    ) {
        $this->characterRepository = $characterRepository;
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->validator = $validator;
        $this->dispatcher = $dispatcher;
    }


    /**
     * {@inheritdoc}
     */
    public function create(string $data)
    {
        $character = new Character();
        // $character
        //     ->setIdentifier(hash('sha1', uniqid()))
        //     ->setCreation(new DateTime())
        //     ->setModification(new DateTime())
        // ;
        $this->submit($character, CharacterType::class, $data);

        // $event = new CharacterEvent($character);
        // $this->dispatcher->dispatch($event, CharacterEvent::CHARACTER_CREATED);

        // $this->isEntityFilled($character);

        // $this->em->persist($character);
        // $this->em->flush();

        // return $character;
        return $this->createFromHtml($character);
    }

    public function createFromHtml(Character $character)
    {
        $character
            ->setIdentifier(hash('sha1', uniqid()))
            ->setCreation(new DateTime())
            ->setModification(new DateTime())
        ;
        $event = new CharacterEvent($character);
        $this->dispatcher->dispatch($event, CharacterEvent::CHARACTER_CREATED);

        $this->isEntityFilled($character);

        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }

    /***
    * {@inheritdoc}
    */
    public function serializeJson($data)
    {
        $encoders = new JsonEncoder();

        $defaultContext = [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($data) {
                    return $data->getIdentifier();
                },
            ];
        // $normalizers = new ObjectNormalizer();
        $normalizers = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizers], [$encoders]);

        return $serializer->serialize($data, 'json');
    }


    /**
     * {@inheritdoc}
     */
    public function isEntityFilled(Character $character)
    {
        $errors = $this->validator->validate($character);
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException((string) $errors . ' Missing data for Entity -> ' . $this->serializeJson($character));
        }
    }

    /**
    * {@inheritdoc}
    */
    public function submit(Character $character, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);

        //Bad array
        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
        }

        //Submits form
        $form = $this->formFactory->create($formName, $character, ['csrf_protection' => false]);
        $form->submit($dataArray, false);//With false, only submitted fields are validated

        //Gets errors
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            throw new LogicException('Error ' . get_class($error->getCause()) . ' --> ' . $error->getMessageTemplate() . ' ' . json_encode($error->getMessageParameters()));
        }
    }

    /**
    * {@inheritdoc}
    */
    public function delete(Character $character)
    {
        $this->em->remove($character);
        $this->em->flush();

        return true;
    }

    /**
    * {@inheritdoc}
    * */
    public function modify(Character $character, string $data)
    {
        $this->submit($character, CharacterType::class, $data);
        // $this->isEntityFilled($character);
        // $character
        //     ->setModification(new DateTime())
        // ;

        // $this->em->persist($character);
        // $this->em->flush();

        // return $character;
        return $this->modifyFromHtml($character);
    }

    public function modifyFromHtml($character)
    {
        $this->isEntityFilled($character);
        $character
            ->setModification(new DateTime())
        ;

        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getImages(int $number)
    {
        $folder = __DIR__ . '/../../public/images/';

        $finder = new Finder();
        $finder
            ->files()
            ->in($folder)
            ->notPath('/cartes/')
            ->sortByName()
        ;
        $images = array();
        foreach ($finder as $file) {
            $images[] = '/images/' . $file->getPathname();
        }
        shuffle($images);

        return array_slice($images, 0, $number, true);
    }

    /**
     * {@inheritdoc}
     */
    public function getImagesKind(string $kind, int $number)
    {
        $folder = __DIR__ . '/../../public/images/';

        $finder = new Finder();
        $finder
            ->files()
            ->name($kind . '-*.jpg')
            ->in($folder)
            ->notPath('/cartes/')
            ->sortByName()
        ;
        $images = array();
        foreach ($finder as $file) {
            $images[] = '/images/' . $file->getPathname();
        }
        shuffle($images);

        return array_slice($images, 0, $number, true);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        return $this->characterRepository->findAll();
        // $characterFinals = array();
        // $characters = $this->characterRepository->findAll();
        // foreach ($characters as $character) {
        //     $characterFinals[] = $character->toArray();
        // }
        // return $characterFinals;
    }
}
