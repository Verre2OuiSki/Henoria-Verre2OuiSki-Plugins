<?php

namespace HenoriaRanks\Loaders;

use pocketmine\utils\TextFormat;

use HenoriaRanks\Main;
use HenoriaRanks\Events\{JoinEvent,QuitEvent , ChatEvent};

class Events{


    public static function Load(){

        $events = [

            "join" => new JoinEvent( Main::getInstance() ),
            "quit" => new QuitEvent( Main::getInstance() ),
            "chat" => new ChatEvent( Main::getInstance() )
        ];

        foreach ( $events as $name => $file ){

            Main::getInstance()->getServer()->getPluginManager()->registerEvents( $file, Main::getInstance() );
        }

        Main::getInstance()->getLogger()->info( TextFormat::AQUA . (string) count($events) . " events ont été chargés !" );
    }

}