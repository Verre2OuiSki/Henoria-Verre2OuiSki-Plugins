<?php

namespace HenoriaMod\Commands;

use HenoriaMod\Main;
use HenoriaMod\Utils\BanEntry;
use HenoriaMod\Utils\BanList;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class TempBan extends PluginCommand{

    public $plugin;

    public function __construct( Main $plugin )
    {
        $this->plugin = $plugin;

        parent::__construct( "tempban" , $plugin);

        $this->setDescription('Bannir temporairement un joueur');
        $this->setUsage('/tempban <joueur> <temps> <raison>');
        $this->setAliases([ "tban" ]);

        $this->setPermission("henoriamod.command.tempban" );
        $this->setPermissionMessage("§cVous n'avez pas la permissions d'utiliser cette commande." );
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {

        if( !$sender->hasPermission( $this->getPermission() ) ){
            $sender->sendMessage( $this->getPermissionMessage() ); return false;
        }

        if( isset( $args[0] ) ){

            $player = $this->plugin->getServer()->getPlayer( $args[0] );

            if( $player instanceof Player ){

                if( isset( $args[1] ) ){

                    $time = preg_replace( "%[a-z]+%i", "", $args[1] );
                    $unit = strtolower( preg_replace( "%[^ydhm(min)]+%i", "", $args[1] ) );

                    if( preg_match( "%[ydhm(min)]%", $unit ) === 1 && $time !== 0 ){

                        if( isset( $args[2] ) ){

                            unset( $args[0], $args[1] );
                            $reason = implode( " ", $args );

                            $banTime = new \DateTime();
                            $broadcastTime = "";
                            switch ( $unit ){
                                case "y":
                                    $banTime->modify( "+$time year" );
                                    $broadcastTime =  "$time an(s)";
                                    break;
                                case "m":
                                    $banTime->modify( "+$time month" );
                                    $broadcastTime = "$time mois";
                                    break;
                                case "d":
                                    $banTime->modify( "+$time day" );
                                    $broadcastTime = "$time jour(s)";
                                    break;
                                case "h":
                                    $banTime->modify( "+$time hour" );
                                    $broadcastTime = "$time heure(s)";
                                    break;
                                case "min":
                                    $banTime->modify( "+$time min" );
                                    $broadcastTime = "$time minute(s)";
                                    break;
                            }

                            $banEntry = new BanEntry( $player->getXuid(), $player->getName() );
                            $banEntry->setReason( $reason );
                            $banEntry->setSource( $sender->getName() );
                            $banEntry->setExpire( $banTime );

                            $banList = new BanList( $this->plugin );
                            $banList->addEntry( $banEntry );

                            $broadcastMsg = $this->plugin->getConfig()->get("serverBroadcast")[ "banMessage" ];
                            $playerMsg = $this->plugin->getConfig()->get( "playerBroadcast" )[ "banMessage" ];

                            $this->plugin->getServer()->broadcastMessage(
                                str_replace(
                                    [ "{player}", "{staff}", "{time}", "{reason}" ],
                                    [ $player->getName(), $sender->getName(), $broadcastTime, $reason ],
                                    $broadcastMsg
                                )
                            );

                            $player->kick(
                                str_replace(
                                    [ "{staff}", "{time}", "{reason}" ],
                                    [ $sender->getName(), $broadcastTime, $reason ],
                                    $playerMsg
                                ),
                                false
                            );

                            return true;

                        }else{
                            $sender->sendMessage( "§cVeuillez saisir une raison" ); return false;
                        }
                    }else{
                        $sender->sendMessage( "§cLe temps sélectionné est invalide §o( xd ou xHour ou xMin )" ); return false;
                    }
                }else{
                    $sender->sendMessage( "§cVeuillez saisir une durée §o( y|year, m|month, d|day, h|hour, m|min )" ); return false;
                }
            }else{
                $sender->sendMessage( "§cLe joueur saisi est introuvable" ); return false;
            }
        }else{
            $sender->sendMessage( $this->getUsage() ); return false;
        }
    }
}