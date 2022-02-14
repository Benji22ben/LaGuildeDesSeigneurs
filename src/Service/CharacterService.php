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

    /*** 
     * {@inheritdoc}
     * */
    public function modify(Character $character)
    {
        $character
            ->setKind('Seigneur')
            ->setName('Gorthol')
            ->setSurname('Haume de terreur')
            ->setCaste('Chevalier')
            ->setKnowledge('Diplomatie')
            ->setIntelligence(110)
            ->setLife(13)->setImage('/images/gorthol.jpg')
        ;
            
        $this->em->persist($character);
        $this->em->flush();

        return $character;}


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
