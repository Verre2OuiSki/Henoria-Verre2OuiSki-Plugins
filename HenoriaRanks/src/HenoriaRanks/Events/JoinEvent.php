<?php

namespace HenoriaRanks\Events;

use HenoriaRanks\Main;
use HenoriaRanks\Tools\RankManager\PlayerRank;
use HenoriaRanks\Tools\RankManager\Rank;
use HenoriaRanks\Tools\RankManager\RankManager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;

class JoinEvent implements Listener{

    public $plugin;

    public function __construct( Main $plugin ){
        $this->plugin = $plugin;
    }


    public function onJoin( PlayerJoinEvent $event) : void {

        $player = $event->getPlayer();
        $xuid = $player->getXuid();

        RankManager::init();
        // Set player rank

        if( !PlayerRank::getPlayerRankId( $xuid ) ){
            PlayerRank::setPlayerRank( $xuid, RankManager::getDefaultRankId() );
        }

        $playerRank = PlayerRank::getPlayerRank( $xuid );
        $player->setNameTag(
            str_replace(
                ["{rank}", "{player}"],
                [$playerRank["rankDisplayName"], $player->getName()],
                $playerRank["playerNametag"]
            )
        );



        // Set player permissions

        $playerPermissions = Rank::getRankPermissions(
            PlayerRank::getPlayerRankId( $xuid )
        );

        PlayerRank::setPlayerAtt( $xuid, $player->addAttachment( $this->plugin ) );

        $playerAtt = PlayerRank::getPlayerAtt( $xuid );

        foreach ( $playerPermissions as $permission ){
            $playerAtt->setPermission( $permission, true );
        }

    }


}