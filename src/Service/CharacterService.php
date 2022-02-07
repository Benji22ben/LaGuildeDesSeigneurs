<?php

namespace App\Service;

use DateTime;
use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CharacterRepository;

class CharacterService implements CharacterServiceInterface
{

    public function __construct(
        CharacterRepository $characterRepository,
        EntityManagerInterface $em)
    {
        $this->characterRepository = $characterRepository;
        $this->em = $em;
    }
    /**
     * {@inheritdoc}
     */
    public function create() {
        $character = New Character();
        $character
            ->setKind('Dame')
            ->setName('Eldalotë')
            ->setSurname('Fleur elfique')
            ->setCaste('Elfe')
            ->setKnowledge('Arts')
            ->setIntelligence('120')
            ->setLife('12')
            ->setImage('/images/eldalote.jpg')
            ->setCreation(new \DateTime())
            ->setIdentifier(hash('sha1', uniqid()))
            ;

            $this->em->persist($character);
            $this->em->flush();

        return $character;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $characterFinals = array();
        $characters = $this->characterRepository->findAll();
        foreach ($characters as $character) {
            $charactersFinal[] = $character->toArray();
        }
        return $charactersFinal;
    }
}
