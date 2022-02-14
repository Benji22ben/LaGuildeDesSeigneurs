<?php

namespace App\Security\Voter;

use App\Entity\Character;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterVoter extends Voter
{
    public const CHARACTER_DISPLAY = 'characterDisplay';
    public const CHARACTER_CREATE = 'characterCreate';
    public const CHARACTER_INDEX = 'characterIndex';
    public const CHARACTER_MODIFY = 'characterModify';


    private const ATTRIBUTES = array(
        self::CHARACTER_CREATE,
        self::CHARACTER_DISPLAY,
        self::CHARACTER_INDEX,
        SELF::CHARACTER_MODIFY
    );

    protected function supports(string $attribute, $subject): bool
    {
        if(null !== $subject){
            return $subject instanceof Character && in_array($attribute, self::ATTRIBUTES);
        }

        return in_array($attribute, self::ATTRIBUTES)
            && $subject instanceof Character;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        //Define access rigts
        dd($subject);
        switch($attribute){
            case self::CHARACTER_DISPLAY:
            case self::CHARACTER_INDEX:
                //Peut envoyer $token et $subject pour tester des conditions
                return $this->canDisplay();
                break;
            case self::CHARACTER_CREATE:
                return $this->canCreate();
                break;
            case self::CHARACTER_MODIFY:
                return $this->canModify();
                break;
        }
        throw new LogicException('Invalid attribute: ' . $attribute);
    }
    
    /**
     * Check if is allowed to display
     */
    private function canDisplay()
    {
        return true;
    }
    
    private function canCreate()
    {
        return true;
    }

    /*** Checks if is allowed to modify*/
    private function canModify()
    {
        return true;
    }

}
