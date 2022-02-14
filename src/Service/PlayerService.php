<?php

namespace App\Service;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PlayerRepository;

class PlayerService implements PlayerServiceInterface
{

    public function __construct(
        PlayerRepository $playerRepository,
        EntityManagerInterface $em)
    {
        $this->playerRepository = $playerRepository;
        $this->em = $em;
    }

        /**
     * {@inheritdoc}
     */
    public function create() {
        $player = New Player();
        $player 
            ->setFirstname('Benjamin')
            ->setLastname('MARQUES')
            ->setEmail('moi@email.com')
            ->setMirian(0)
            ->setIdentifier(hash('sha1', uniqid()))
            ->setCreation(new \DateTime())
            ->setModification(new \DateTime())
        ;


        $this->em->persist($player);
        $this->em->flush();

        return $player;
    }

     /**
    * {@inheritdoc}
    */
    public function delete(Player $player) {
        $this->em->remove($player);
        $this->em->flush();

        return true;
    }

    /**
    * {@inheritdoc}
    * */
    public function modify(Player $player)
    {
        $player
            ->setFirstname('Ben')
            ->setLastname('MARQUES BALULA')
            ->setEmail('ben@gmail.com')
            ->setMirian(10100)
            ->setModification(new \DateTime())
        ;
            
        $this->em->persist($player);
        $this->em->flush();

        return $player;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $playersFinals = array();

        $players = $this->playerRepository->findAll();
        foreach ($players as $player) {
            $playersFinals[] = $player->toArray();
        }
        return $playersFinals;
    }
}
