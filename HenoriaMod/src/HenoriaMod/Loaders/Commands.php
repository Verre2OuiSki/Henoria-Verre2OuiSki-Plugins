<?php

namespace HenoriaMod\Loaders;

use HenoriaMod\Commands\Ban;
use HenoriaMod\Commands\Kick;
use HenoriaMod\Commands\TempBan;
use HenoriaMod\Main;
use pocketmine\utils\TextFormat;


class Commands{


    public static function Load(){

        $commands = [
            "tempban" => new TempBan( Main::getInstance() ),
            "ban" => new Ban( Main::getInstance() ),
            "kick" => new Kick( Main::getInstance() )
        ];

        foreach ( $commands as $name => $file ){

            Main::getInstance()->getServer()->getCommandMap()->register( $name, $file );
        }

        Main::getInstance()->getLogger()->info( TextFormat::AQUA . strval(count($commands)) . " commandes ont été chargés !" );

    }

}