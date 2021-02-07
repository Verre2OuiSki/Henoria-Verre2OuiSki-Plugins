<?php

namespace HenoriaMod\Utils;


use HenoriaMod\Database\SqliteDb;

class BanList extends SqliteDb {

    public function isBanned( $xuid ) : bool {

        $ban_ip = parent::getBanIp( $xuid );
        if( !empty( $ban_ip ) ){ return true; }



        $current_ban = parent::getCurrentBan( $xuid );
        if( empty($current_ban) ){ return false; };

        $now = new \DateTime();
        $ban = new \DateTime( $current_ban["expirationDate"] );

        if( $now < $ban ){ return true; }


        // move the expired ban of the player to another table (ban_history)
        parent::addExpiredBan(
            $current_ban["current_ban_id"],
            $current_ban["xuid"],
            $current_ban["playerName"],
            $current_ban["reason"],
            $current_ban["source"],
            $current_ban["creationDate"],
            $current_ban["expirationDate"]
        );

        return false;

    }

    public function getEntry( $xuid ){

        $ban_ip = parent::getBanIp( $xuid );

        if( !empty( $ban_ip ) ){

            $banEntry = new BanEntry( $xuid, $ban_ip["playerName"] );

            $banEntry->setIp( $ban_ip["ip"] );
            $banEntry->setSource( $ban_ip["source"] );
            $banEntry->setReason( $ban_ip["reason"] );
            $banEntry->setCreation( new \DateTime( $ban_ip["creationDate"] ) );

            return $banEntry;
        }

        $current_ban = parent::getCurrentBan( $xuid );

        if( empty( $current_ban ) ){ return null; }

        $banEntry = new BanEntry( $xuid, $current_ban["playerName"] );

        $banEntry->setSource( $current_ban["source"] );
        $banEntry->setReason( $current_ban["reason"] );
        $banEntry->setCreation( new \DateTime( $current_ban["creationDate"] ) );
        $banEntry->setExpire( new \DateTime( $current_ban["expirationDate"] ) );

        return $banEntry;
    }

    public function addEntry( BanEntry $entry ){

        if( !empty( $entry->getIp() ) ){

            parent::addBanIp(
                $entry->getIp(),
                $entry->getXuid(),
                $entry->getPlayerName(),
                $entry->getReason(),
                $entry->getSource(),
                $entry->getCreation()
            );

        }

        parent::addCurrentBan(
            $entry->getXuid(),
            $entry->getPlayerName(),
            $entry->getReason(),
            $entry->getSource(),
            $entry->getCreation(),
            $entry->getExpire()
        );

    }


}