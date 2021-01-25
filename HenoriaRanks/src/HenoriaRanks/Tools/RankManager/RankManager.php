<?php

namespace HenoriaRanks\Tools\RankManager;

use HenoriaRanks\Tools\ConfigFiles;

class RankManager extends ConfigFiles {

    protected static $ranksConfig;

    protected static function init(){
        if ( empty(self::$ranksConfig) ){
            self::$ranksConfig = parent::getTable( parent::RANKS_CONFIG );
        }
    }

    

}