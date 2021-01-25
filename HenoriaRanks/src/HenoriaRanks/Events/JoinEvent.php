<?php

namespace HenoriaRanks\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinEvent implements Listener{

    public $plugin;

    public function __construct( Main $plugin ){
        $this->plugin = $plugin;
    }


    public function onJoin( PlayerJoinEvent $event) : void {

        $player = $event->getPlayer();



    }


}