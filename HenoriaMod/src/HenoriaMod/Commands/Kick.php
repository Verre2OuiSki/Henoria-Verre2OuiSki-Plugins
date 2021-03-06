<?php

namespace HenoriaMod\Commands;

use HenoriaMod\Main;
use HenoriaMod\Utils\BanEntry;
use HenoriaMod\Utils\BanList;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class Kick extends PluginCommand{

    public $plugin;

    public function __construct( Main $plugin )
    {
        $this->plugin = $plugin;

        parent::__construct( "kick" , $plugin);

        $this->setDescription('Kick un joueur');
        $this->setUsage('/kick <joueur> <raison>');

        $this->setPermission("henoriamod.command.kick" );
        $this->setPermissionMessage("§cVous n'avez pas la permissions d'utiliser cette commande." );
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){

        if( !$sender->hasPermission( $this->getPermission() ) ){
            $sender->sendMessage( $this->getPermissionMessage() );
            return;
        }

        if( isset( $args[0], $args[1] ) ){

            $player = $this->plugin->getServer()->getPlayer( $args[0] );

            if( $player instanceof Player){

                unset( $args[0] );
                $reason = implode( " ", $args );

                $broadcastMsg = $this->plugin->getConfig()->get("serverBroadcast")[ "kickMessage" ];
                $playerMsg = $this->plugin->getConfig()->get( "playerBroadcast" )[ "kickMessage" ];

                $this->plugin->getServer()->broadcastMessage(
                    str_replace(
                        [ "{player}", "{staff}", "{reason}" ],
                        [ $player->getName(), $sender->getName(), $reason ],
                        $broadcastMsg
                    )
                );

                $player->kick(
                    str_replace(
                        [ "{staff}", "{reason}" ],
                        [ $sender->getName(), $reason ],
                        $playerMsg
                    ),
                    false
                );

            }else{
                // $player not instanceof Player
                $sender->sendMessage( "§cLe joueur saisi est introuvable" );
            }

        }else{
            $sender->sendMessage( $this->getUsage() );
        }

    }

}