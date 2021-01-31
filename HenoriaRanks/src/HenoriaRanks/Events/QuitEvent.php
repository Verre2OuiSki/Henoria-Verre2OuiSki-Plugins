<?php

namespace HenoriaRanks\Events;

use HenoriaRanks\Main;
use HenoriaRanks\Tools\RankManager\PlayerRank;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class QuitEvent implements Listener{

    public $plugin;

    public function __construct( Main $plugin ){
        $this->plugin = $plugin;
    }


    public function onQuit( PlayerQuitEvent $event) : void {

        $player = $event->getPlayer();
        $xuid = $player->getXuid();

        PlayerRank::removePlayerAtt( $xuid );
    }


}