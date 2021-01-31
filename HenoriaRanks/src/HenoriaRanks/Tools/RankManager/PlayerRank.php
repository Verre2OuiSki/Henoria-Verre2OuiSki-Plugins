<?php

namespace HenoriaRanks\Tools\RankManager;

use pocketmine\permission\PermissionAttachment;

class PlayerRank extends RankManager{



    public static $playersPermissions;



    public static function getPlayerRankId( $playerXuid ){

        parent::$playersRank->reload();
        return parent::$playersRank->get( "xuid" . $playerXuid );
    }

    public static function getPlayerRank( $playerXuid ){

        parent::$playersRank->reload();
        return parent::getRankById( parent::$playersRank->get( "xuid" . $playerXuid ) ) ;
    }

    public static function setPlayerRank( $playerXuid, $rankId ){

        parent::$playersRank->set( "xuid" . $playerXuid, $rankId );
        parent::$playersRank->save();
    }



    // Function about PlayerPermissionsAttachement

        // get player permissionsAttachment
    public static function getPlayerAtt( string $playerXuid ){
        if( isset(self::$playersPermissions[ $playerXuid ]) ){
            return self::$playersPermissions[ $playerXuid ];
        }
        return null;
    }
        // set player permissionsAttachment
    public static function setPlayerAtt( string $playerXuid, PermissionAttachment $att){
        self::$playersPermissions[ $playerXuid ] = $att;
    }
        // remove player permissionsAttachment
    public static function removePlayerAtt( string $playerXuid ){
        unset( self::$playersPermissions[ $playerXuid ] );
    }



}