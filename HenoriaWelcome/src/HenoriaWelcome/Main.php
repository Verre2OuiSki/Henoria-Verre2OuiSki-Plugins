<?php

namespace HenoriaWelcome;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;


class Main extends PluginBase{


    private $sayWelcomeTimer;
    private $welcomeMessage;

    private $lastPlayer = []; // Name of the last player join the server for the first time and its timestamp
    private $welcomePlayers = []; // Name of all player sayed welcome to the last player join the server for the first time

    public function onLoad() : void {
        $this->getLogger()->info( TextFormat::DARK_PURPLE . $this->getFullName() . " a été chargé !" );
    }

    public function onEnable() : void{

        $this->getServer()->getPluginManager()->registerEvents(new JoinEvent($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new QuitEvent($this), $this);

        $this->getLogger()->info( TextFormat::DARK_GREEN . $this->getFullName() . " a été activé !" );


        // Get the value of key "sayWelcomeTimer" in YAML config file
        $sayWelcomeTimer = $this->getConfig()->get( "sayWelcomeTimer" );
        $sayWelcomeTimer = explode( ":", $sayWelcomeTimer );

        // Get the value of key "welcomeMessage" in YAML config file
        $this->welcomeMessage = $this->getConfig()->get( "welcomeMessage" );

        // Convert the "H:m:s" value to seconds
        $this->sayWelcomeTimer = (int)$sayWelcomeTimer[0] * 60 * 60 + (int)$sayWelcomeTimer[1] * 60 + (int)$sayWelcomeTimer[2];

    }

    public function onDisable() : void{
        $this->getLogger()->info( TextFormat::DARK_RED . $this->getFullName() . " a été désactivé !" );
    }



    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{


        switch ( strtolower($command->getName()) ){

            case "bienvenue":

                if( $sender instanceof Player ){

                    if ( !empty($this->lastPlayer) ){

                        if ( $this->lastPlayer[0] !== $sender->getName() ){

                            if(  !in_array( $sender->getName(), $this->welcomePlayers ) ){

                                if ( (time() - $this->lastPlayer[1]) <= $this->sayWelcomeTimer ){

                                    $message = "Salut @{player} ! Je te souhaite la bienvenue à sur le serveur !";

                                   	$sender->chat(
                                        str_replace(
                                            [ "{player}" ],
                                            [ $this->lastPlayer[0] ],
                                            $this->welcomeMessage
                                        )
                                    );
                                    array_push( $this->welcomePlayers, $sender->getName() );
                                    return true;

                                }else{

                                    // Time is past

                                    $sayWelcomeTimer = $this->getConfig()->get( "sayWelcomeTimer" );
                                    $sayWelcomeTimer = explode( ":", $sayWelcomeTimer );

                                    $h = (int)$sayWelcomeTimer[0];
                                    $m = (int)$sayWelcomeTimer[1];
                                    $s = (int)$sayWelcomeTimer[2];

                                    if( $h !== 0 ){
                                        $sender->sendMessage( "§cMalheureusement, cela fait déjà plus de {$h} heures {$m} minutes et {$s} secondes que {$this->lastPlayer[0]} a rejoint le serveur ..." );
                                    }elseif ( $m !== 0 ){
                                        $sender->sendMessage( "§cMalheureusement, cela fait déjà plus de {$m} minutes et {$s} secondes que {$this->lastPlayer[0]} a rejoint le serveur ..." );
                                    }else{
                                        $sender->sendMessage( "§cMalheureusement, cela fait déjà plus de {$s} secondes que {$this->lastPlayer[0]} a rejoint le serveur ..." );
                                    }
                                    return true;
                                }
                            }else{

                                // Sender already sayed welcome

                                $sender->sendMessage( "§cVous avez déjà souhaité la bienvenue à {$this->lastPlayer[0]}" );
                                return true;
                            }
                        }else{

                            // Sender say welcome to his self

                            $sender->sendMessage( "§cVous ne pouvez pas vous souhaiter la bienvenue ..." );
                            return true;
                        }

                    }else{

                        // No new player

                        $sender->sendMessage( "§cIl n'y a pas encore eu de nouveaux joueurs :/" );
                        return true;
                    }
                }else{

                    if( !empty($this->lastPlayer) ){

                        if( !in_array( "console", $this->welcomePlayers ) ){

                            $this->getServer()->broadcastMessage(
                                "§5§l»§r  §dUne entité supérieur souhaite la bienvenue à @{$this->lastPlayer[0]} sur le serveur !"
                            );
                            array_push( $this->welcomePlayers, "console" );
                            return true;

                        }else{

                            // Sender already sayed welcome

                            $sender->sendMessage("§cVous avez déjà souhaité la bienvenue à {$this->lastPlayer[0]}");
                            return true;
                        }
                    }else{

                        // No new player

                        $sender->sendMessage( "§cIl n'y a pas encore eu de nouveaux joueurs :/" );
                        return true;
                    }
                }
            default:
                return false;
        }

    }



    public function setLastPlayer( $playerName, $timestamp ){
        $this->lastPlayer = [ $playerName, $timestamp ];
    }

    public function getLastPlayer(){
        return $this->lastPlayer;
    }

    public function resetLastPlayer(){
        $this->lastPlayer = [];
    }

    public function resetWelcomePlayers(){
        $this->welcomePlayers = [];
    }



}
