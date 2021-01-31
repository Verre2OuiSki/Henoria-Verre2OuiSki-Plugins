<?php

namespace HenoriaRanks\Tools\RankManager;

class Rank extends RankManager{

    public static function getRankPermissions( $rankId, $rankPermissions = [] ) : array {

        $rank = parent::getRankById( $rankId );
        $permissions = $rank["permissions"]["permissions"];

        foreach( $permissions as $permission ){
            if( !in_array( $permission, $rankPermissions ) ){
                array_push( $rankPermissions, $permission );
            }
        }

        if( $rank["permissions"]["children"] !== null ){
            $rankPermissions = self::getRankPermissions( $rank["permissions"]["children"], $rankPermissions );
        }

        return $rankPermissions;
    }


}