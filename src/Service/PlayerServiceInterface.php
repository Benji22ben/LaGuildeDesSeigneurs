<?php

namespace App\Service;

use App\Entity\Player;

interface PlayerServiceInterface
{
    /**
     * Creates The Character
     */
    public function create(string $data);

    /*** Checks if the entity has been well filled*/
    public function isEntityFilled(Player $player);

    /*** Submits the data to hydrate the object*/
    public function submit(Player $player, $formName, $data);

    /**
     * Modify the character
     */
    public function modify(Player $player, string $data);

    /**
     * Gets all the player
     */
    public function getAll();

    /***
    * Serialize the object(s)
    */
    public function serializeJson($data);
}
