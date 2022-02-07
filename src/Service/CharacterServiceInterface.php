<?php

namespace App\Service;

interface CharacterServiceInterface
{
    /**
     * Creates The Character
     */
    public function create();

    /**
     * Gets all the characters
     */
    public function getAll();
}