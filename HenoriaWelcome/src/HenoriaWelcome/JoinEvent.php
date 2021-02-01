<?php

namespace HenoriaWelcome;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinEvent implements Listener{

    public $plugin;

    public function __construct( Main $plugin ){
        $this->plugin = $plugin;
    }


    public function onJoin( PlayerJoinEvent $event) : void {

        $player = $event->getPlayer();

        if( !$player->hasPlayedBefore() ){

            $this->plugin->setLastPlayer( $player->getName(), time() );
            $this->plugin->resetWelcomePlayers();

            $this->plugin->getServer()->broadcastMessage("§2§l» §r§a{$player->getName()} se connecte pour la premiere fois sur le serveur !\nSouhaitez lui la bienvenue ! §o(/bienvenue)");

        }

    }


}