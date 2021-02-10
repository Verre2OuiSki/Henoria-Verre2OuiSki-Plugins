<?php

namespace HenoriaMod\Utils;


use HenoriaMod\Database\SqliteDb;

class WarnList extends SqliteDb {


    public function getLastEntry( string $xuid ){

        $entry = parent::getWarns( $xuid, $lastWarn = true );
        if( empty( $entry ) ){ return null; }

        $warnEntry = new WarnEntry( $xuid, $entry["playerName"] );
        $warnEntry->setSource( $entry["source"] );
        $warnEntry->setReason( $entry["reason"] );
        $warnEntry->setCreation( new \DateTime( $entry["creationDate"] ) );
        $warnEntry->setWarnId( $entry["warn_id"] );

        return $warnEntry;
    }

    public function getEntries( string $xuid, \DateTime $date = null){

        $arrayEntries = parent::getWarns( $xuid, $date );

        $result = [];
        foreach ( $arrayEntries as $entry ){

            $warnEntry = new WarnEntry( $xuid, $entry["playerName"] );

            $warnEntry->setReason( $entry["reason"] );
            $warnEntry->setSource( $entry["source"] );
            $warnEntry->setCreation( new \DateTime( $entry["creationTime"] ) );
            $warnEntry->setWarnId( $entry["warn_id"] );

            $result[] = $warnEntry;
        }

        if ( empty( $result ) ){ return null; }

        return $result;
    }

    public function addEntry( WarnEntry $entry ){

        parent::addWarn(
            $entry->getXuid(),
            $entry->getPlayerName(),
            $entry->getSource(),
            $entry->getReason(),
            $entry->getCreation()->format( "Y-m-d H:i:s" )
        );

    }

    public function removeEntry( int $warn_id ){

        parent::removeWarn( $warn_id );

    }


}