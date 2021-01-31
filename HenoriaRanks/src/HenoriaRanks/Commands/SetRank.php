<?php

namespace HenoriaRanks\Commands;

use HenoriaRanks\Main;
use HenoriaRanks\Tools\RankManager\PlayerRank;
use HenoriaRanks\Tools\RankManager\Rank;
use HenoriaRanks\Tools\RankManager\RankManager;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

class SetRank extends PluginCommand{

    public $plugin;

    public function __construct( Main $plugin )
    {
        $this->plugin = $plugin;

        parent::__construct( "setrank" , $plugin);

        $this->setDescription('Permet de définir le grade d\'un joueur');
        $this->setUsage('/setrank <joueur> <grade>');
        $this->setAliases([ "srank" ]);

        $this->setPermission("henoriaranks.command.rankmanager" );
        $this->setPermissionMessage("§cVous n'avez pas la permissions d'utiliser cette commande." );
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){

        if( !$sender->hasPermission( $this->getPermission() ) ){
            $sender->sendMessage( $this->getPermissionMessage() );
            return;
        }

        if ( count($args) >= 2  ){

            $player = $this->plugin->getServer()->getPlayer( $args[0] );
            $xuid = $player->getXuid();

            if ( $player !== null ){

                if( $player->getName() === $sender->getName() && !$sender->isOp() ){
                    $sender->sendMessage( "§cVous ne pouvez définir votre propre grade..." );
                    return;
                }

                $ranks = RankManager::getRanks();
                $args[1] = strtolower( $args[1] );

                $selectRank = null;
                foreach ( $ranks as $rankId => $rank ){

                    foreach ( $rank["cmdIdentifiers"] as $identifier ){

                        if ( $args[1] == strtolower($identifier) ){
                            $selectRank = $rankId;
                            break 2;
                        }
                    }
                }

                if( !empty( $selectRank ) ){

                    $lastRank = PlayerRank::getPlayerRankId( $xuid );

                    PlayerRank::setPlayerRank( $xuid , $selectRank );

                    // Set visual aspect
                    $playerRank = PlayerRank::getPlayerRank( $xuid );
                    $player->setNameTag(
                        str_replace(
                            ["{rank}", "{player}"],
                            [$playerRank["rankDisplayName"], $player->getName()],
                            $playerRank["playerNametag"]
                        )
                    );



                    // Set permissions


                    $playerPermissions = Rank::getRankPermissions(
                        PlayerRank::getPlayerRankId( $xuid )
                    );
                    $playerAtt = PlayerRank::getPlayerAtt( $xuid );

                    foreach( Rank::getRankPermissions( $lastRank ) as $permission ){
                        $playerAtt->unsetPermission( $permission );
                    }
                    foreach ( $playerPermissions as $permission ){
                        $playerAtt->setPermission( $permission, true );
                    }

                    $sender->sendMessage( "§2§l»§r§a Le grade §2{$playerRank["rankDisplayName"]}§a a bien été ajouté à §2{$player->getName()}§a !" );

                }else{

                    $sender->sendMessage( "§2Liste des différents grades :" );
                    foreach ( $ranks as $rank ){
                        $message = "§a - {$rank["rankDisplayName"]} §l»§r§a ";

                        foreach ( $rank["cmdIdentifiers"] as $id => $identifier){
                            if( $id === 0){
                                $message .= $identifier;
                            }else{
                                $message .= ", {$identifier}";
                            }
                        }
                        $sender->sendMessage( $message );
                    }
                    $sender->sendMessage( "§cAucun grade ne correspond a ce que vous avez entré." );
                }

            }else{
                $sender->sendMessage( "§cLe joueur entré est introuvable..." );
            }

        }else{

            $ranks = RankManager::getRanks();
            $sender->sendMessage( "§2Liste des différents grades :" );
            foreach ( $ranks as $rank ){
                $message = "§2 - {$rank["rankDisplayName"]} §l»§r§a ";

                foreach ( $rank["cmdIdentifiers"] as $id => $identifier){
                    if( $id === 0){
                        $message .= $identifier;
                    }else{
                        $message .= ", {$identifier}";
                    }
                }
                $sender->sendMessage( $message );
            }
            $sender->sendMessage( $this->getUsage() );
        }

    }

}