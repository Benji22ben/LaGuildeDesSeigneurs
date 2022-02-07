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

    private const ATTRIBUTES = array(
        self::CHARACTER_DISPLAY,
    );

    protected function supports(string $attribute, $subject): bool
    {
        if(null !== $subject){
            return $subject instanceof Character && in_array($attribute, self::ATTRIBUTES);
        }

        return in_array($attribute, self::ATTRIBUTES)
            && $subject instanceof \App\Entity\Character;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        //Define access rigts
        switch($attribute){
            case self::CHARACTER_DISPLAY:
                //Peut envoyer $token et $subject pour tester des conditions
                return $this->canDisplay();
                break;
        }
        // $user = $token->getUser();
        // // if the user is anonymous, do not grant access
        // if (!$user instanceof UserInterface) {
        //     return false;
        // }
        throw new LogicException('Invalid attribute: ' . $attribute);
    }
    
    /**
     * Check if is allowed to display
     */
    private function canDisplay()
    {
        return true;
    }
}
