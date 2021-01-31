<?php

namespace HenoriaRanks\Loaders;

use HenoriaRanks\Commands\RemoveRank;
use HenoriaRanks\Commands\SetRank;
use pocketmine\utils\TextFormat;
use HenoriaRanks\Main;


class Commands{


    public static function Load(){

        $commands = [


            // Utilities
            "setrank" => new SetRank( Main::getInstance() ),
            "removerank" => new RemoveRank( Main::getInstance() )

        ];

        foreach ( $commands as $name => $file ){

            Main::getInstance()->getServer()->getCommandMap()->register( $name, $file );
        }

        Main::getInstance()->getLogger()->info( TextFormat::AQUA . strval(count($commands)) . " commandes ont été chargés !" );

    }

}