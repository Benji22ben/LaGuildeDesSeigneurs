<?php
namespace App\Listener;
use App\Event\PlayerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use DateTime;

class PlayerListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(PlayerEvent::PLAYER_MODIFIED => 'playerModified',);
    }
    public function playerModified($event)
    {
        $player = $event->getplayer();
        $player->setMirian($player->getMirian() - 10);
    }
}
