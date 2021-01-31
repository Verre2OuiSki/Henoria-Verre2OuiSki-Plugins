<?php

namespace HenoriaWelcome;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class QuitEvent implements Listener{

    public $plugin;

    public function __construct( Main $plugin ){

        $this->plugin = $plugin;
    }

    public function onQuit( PlayerQuitEvent $event ){

        $player = $event->getPlayer();
        if( !$player->hasPlayedBefore() ) {

            if( $player->getName() === Main::getLastPlayer()[0] ){
                Main::resetLastPlayer();
            }

        }
    }

}