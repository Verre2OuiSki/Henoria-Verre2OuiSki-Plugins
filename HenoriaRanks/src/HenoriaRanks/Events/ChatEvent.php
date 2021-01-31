<?php

namespace HenoriaRanks\Events;

use HenoriaRanks\Main;
use HenoriaRanks\Tools\RankManager\PlayerRank;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class ChatEvent implements Listener{

    public $plugin;

    public function __construct( Main $plugin ){
        $this->plugin = $plugin;
    }


    public function onMessage( PlayerChatEvent $event) : void {

        $player = $event->getPlayer();
        $message = $event->getMessage();
        $xuid = $player->getXuid();


        $playerRank = PlayerRank::getPlayerRank( $xuid );
        $event->setFormat(
            str_replace(
                ["{rank}", "{player}", "{message}"],
                [$playerRank["rankDisplayName"], $player->getName(), $message],
                $playerRank["playerChat"]
            )
        );

    }


}