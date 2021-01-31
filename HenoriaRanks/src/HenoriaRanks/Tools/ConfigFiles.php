<?php

namespace HenoriaRanks\Tools;

use HenoriaRanks\Main;
use pocketmine\utils\Config;

class ConfigFiles{


    const RANKS_CONFIG = [ "ranksConfig.json", "resource", Config::JSON ];
    const PLAYERS_RANK = [ "playersRank.yml", Config::YAML ];



    public static function getTable( $table ){

        if( $table[1] === "resource" ){
            Main::getInstance()->saveResource( $table[0] );
            return new Config( Main::getInstance()->getDataFolder() . $table[0], $table[2]);
        }

        return new Config( Main::getInstance()->getDataFolder() . $table[0], $table[1] );
    }

}