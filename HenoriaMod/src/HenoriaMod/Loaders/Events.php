<?php

namespace HenoriaMod\Loaders;

use HenoriaMod\Events\LoginEvent;
use HenoriaMod\Main;
use pocketmine\utils\TextFormat;


class Events{


    public static function Load(){

        $events = [
            "login" => new LoginEvent( Main::getInstance() )
        ];

        foreach ( $events as $name => $file ){

            Main::getInstance()->getServer()->getPluginManager()->registerEvents( $file, Main::getInstance() );
        }

        Main::getInstance()->getLogger()->info( TextFormat::AQUA . (string) count($events) . " events ont été chargés !" );
    }

}