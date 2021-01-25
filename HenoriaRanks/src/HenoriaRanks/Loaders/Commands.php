<?php

namespace HenoriaRanks\Loaders;

use pocketmine\utils\TextFormat;
use HenoriaRanks\Main;

class Commands{


    public static function Load(){

        $commands = [


            // Utilities


        ];

        foreach ( $commands as $name => $file ){

            Main::getInstance()->getServer()->getCommandMap()->register( $name, $file );
        }

        Main::getInstance()->getLogger()->info( TextFormat::AQUA . strval(count($commands)) . " commandes ont été chargés !" );

    }

}