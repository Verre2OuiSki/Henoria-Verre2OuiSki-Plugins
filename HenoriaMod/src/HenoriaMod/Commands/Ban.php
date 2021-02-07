<?php

namespace HenoriaMod\Commands;

use HenoriaMod\Main;
use HenoriaMod\Utils\BanEntry;
use HenoriaMod\Utils\BanList;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class Ban extends PluginCommand{

    public $plugin;

    public function __construct( Main $plugin )
    {
        $this->plugin = $plugin;

        parent::__construct( "ban" , $plugin);

        $this->setDescription('Bannir un joueur et son IP a vie');
        $this->setUsage('/ban <joueur> <raison>');
        $this->setAliases([ "banip", "ipban" ]);

        $this->setPermission("henoriamod.command.banip" );
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

                $banEntry = new BanEntry( $player->getXuid(), $player->getName() );
                $banEntry->setIp( $player->getAddress() );
                $banEntry->setSource( $sender->getName() );
                $banEntry->setReason( $reason );

                $banList = new BanList( $this->plugin );
                $banList->addEntry( $banEntry );

                $broadcastMsg = $this->plugin->getConfig()->get("serverBroadcast")[ "banIpMessage" ];
                $playerMsg = $this->plugin->getConfig()->get( "playerBroadcast" )[ "banIpMessage" ];

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