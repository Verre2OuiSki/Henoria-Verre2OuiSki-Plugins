<?php

namespace HenoriaRanks\Tools\RankManager;

use HenoriaRanks\Tools\ConfigFiles;

class RankManager extends ConfigFiles {

    protected static $ranksConfig;
    protected static $playersRank;

    public static function init(){
        if ( empty(self::$ranksConfig) ){
            self::$ranksConfig = parent::getTable( parent::RANKS_CONFIG );
        }

        if ( empty(self::$playersRank) ){
            self::$playersRank = parent::getTable( parent::PLAYERS_RANK );
        }
    }

    public static function getDefaultRankId(){

        return self::$ranksConfig->get("defaultRank");
    }

    public static function getRanks() : array {

        return self::$ranksConfig->get("ranks");
    }

    public static function getRanksId() : array {

        return array_keys( self::$ranksConfig->get("ranks") );
    }

    public static function getRankById( string $rankId ){

        return self::$ranksConfig->get("ranks")[ $rankId ];
    }

    

}