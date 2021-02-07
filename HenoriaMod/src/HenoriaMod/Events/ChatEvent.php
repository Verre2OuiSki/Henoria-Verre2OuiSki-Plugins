<?php

namespace HenoriaMod\Events;

use HenoriaMod\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class ChatEvent implements Listener{

    public $plugin;

    public function __construct( Main $plugin ){
        $this->plugin = $plugin;
    }


    public function onMessage( PlayerChatEvent $event) : void {



    }


}