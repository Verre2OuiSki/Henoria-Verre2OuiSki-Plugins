<?php

namespace HenoriaRanks\Commands;

use HenoriaRanks\Main;
use HenoriaRanks\Tools\RankManager\PlayerRank;
use HenoriaRanks\Tools\RankManager\Rank;
use HenoriaRanks\Tools\RankManager\RankManager;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

class RemoveRank extends PluginCommand{

    public $plugin;

    public function __construct( Main $plugin )
    {
        $this->plugin = $plugin;

        parent::__construct( "removerank" , $plugin);

        $this->setDescription('Permet de définir le grade d\'un joueur sur le grade par défaut');
        $this->setUsage('/removerank <joueur>');
        $this->setAliases([ "rrank" ]);

        $this->setPermission("henoriaranks.command.rankmanager" );
        $this->setPermissionMessage("§cVous n'avez pas la permissions d'utiliser cette commande." );
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){

        if( !$sender->hasPermission( $this->getPermission() ) ){
            $sender->sendMessage( $this->getPermissionMessage() );
            return;
        }

        if ( isset( $args[0] )  ){

            $player = $this->plugin->getServer()->getPlayer( $args[0] );
            $xuid = $player->getXuid();

            if ( $player !== null ){

                if( $player->getName() === $sender->getName() && !$sender->isOp() ){
                    $sender->sendMessage( "§cVous ne pouvez définir votre propre grade..." );
                    return;
                }

                $lastRank = PlayerRank::getPlayerRankId( $xuid );

                PlayerRank::setPlayerRank( $xuid , RankManager::getDefaultRankId() );

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
                $sender->sendMessage( "§cLe joueur entré est introuvable..." );
            }

        }else{
            $sender->sendMessage( $this->getUsage() );
        }

    }

}