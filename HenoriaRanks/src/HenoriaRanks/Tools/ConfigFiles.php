<?php

namespace HenoriaRanks\Tools;

use HenoriaRanks\Main;
use pocketmine\utils\Config;

class Database{

    private static $plugin;

    const CONFIG = "pluginConfig";
    const RANKS_CONFIG = [ "ranksConfig.json", "resource", Config::JSON ];
    const PLAYERS_RANK = [ "playersRank.yml", Config::YAML ];

    private static function init(){
        if ( empty(self::$plugin) ){
            self::$plugin = Main::getInstance();
        }
    }

    public static function getTable( $table ){
        self::init();

        if( $table === self::CONFIG ){
            return self::$plugin->getConfig();
        }

        if( $table[1] === "resource" ){
            self::$plugin->saveResource( $table[0] );
            return new Config( self::$plugin->getDataFolder() . $table[0], $table[2]);
        }

        return new Config( self::$plugin->getDataFolder() . $table[0], $table[1] );
    }

}