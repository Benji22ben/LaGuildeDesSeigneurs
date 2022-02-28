<?php

namespace App\Security\Voter;

use App\Entity\Player;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PlayerVoter extends Voter
{
    public const PLAYER_DISPLAY = 'playerDisplay';
    public const PLAYER_CREATE = 'playerCreate';
    public const PLAYER_INDEX = 'playerIndex';
    public const PLAYER_MODIFY = 'playerModify';
    public const PLAYER_DELETE = 'playerDelete';


    private const ATTRIBUTES = array(
        self::PLAYER_CREATE,
        self::PLAYER_DISPLAY,
        self::PLAYER_INDEX,
        self::PLAYER_MODIFY,
        self::PLAYER_DELETE,
    );

    protected function supports(string $attribute, $subject): bool
    {
        if (null !== $subject) {
            return $subject instanceof Player && in_array($attribute, self::ATTRIBUTES);
        }

        return in_array($attribute, self::ATTRIBUTES);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        //Define access rigts
        // dd($subject);
        switch ($attribute) {
            case self::PLAYER_DISPLAY:
            case self::PLAYER_INDEX:
                //Peut envoyer $token et $subject pour tester des conditions
                return $this->canDisplay();
                break;
            case self::PLAYER_CREATE:
                return $this->canCreate();
                break;
            case self::PLAYER_MODIFY:
                return $this->canModify();
                break;
            case self::PLAYER_DELETE:
                return $this->canDelete();
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

    // Check if is allowed to delete
    private function canDelete()
    {
        return true;
    }

    /*** Checks if is allowed to modify*/
    private function canModify()
    {
        return true;
    }
}
