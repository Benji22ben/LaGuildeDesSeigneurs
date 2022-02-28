<?php

namespace App\Service;
use App\Entity\Character;

interface CharacterServiceInterface
{
    /**
     * Creates The Character
     */
    public function create(string $data);

    /*** Checks if the entity has been well filled*/
    public function isEntityFilled(Character $character);

    /*** Submits the data to hydrate the object*/
    public function submit(Character $character, $formName, $data);


    /**
     * Gets all the characters
     */
    public function getAll();

    /**
     * Get images Randomly
     */
    public function getImages(int $number);

    /**
     * Gets Images randomly using kind
     */
    public function getImagesKind(string $kind, int $number);

    /**
     * Modify the character
     */
    public function modify(Character $character, string $data);
}