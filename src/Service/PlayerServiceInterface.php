<?php

namespace App\Service;

interface PlayerServiceInterface
{
    /**
     * Creates The Player
     */
    public function create();

    /**
     * Gets all the player
     */
    public function getAll();
}