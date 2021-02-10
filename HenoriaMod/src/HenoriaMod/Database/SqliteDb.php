<?php

namespace HenoriaMod\Database;

use HenoriaMod\Main;

class SqliteDb{

    private $plugin;
    private $db;


    public function __construct( Main $plugin ){

        $this->plugin = $plugin;

        $this->db = new \SQLite3( $this->plugin->getDataFolder() . "punishments.db" );

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS warns (
                warn_id INTEGER PRIMARY KEY AUTOINCREMENT,
                xuid TEXT,
                playerName TEXT,
                reason TEXT,
                source TEXT,
                creationDate TEXT
            );
            
            CREATE TABLE IF NOT EXISTS mutes_history (
                mute_id INTEGER PRIMARY KEY AUTOINCREMENT,
                xuid TEXT,
                playerName TEXT,
                reason TEXT,
                source TEXT,
                creationDate TEXT,
                expirationDate TEXT 
            );
            CREATE TABLE IF NOT EXISTS current_mutes (
                current_mute_id INTEGER PRIMARY KEY AUTOINCREMENT,
                xuid TEXT,
                playerName TEXT,
                reason TEXT,
                source TEXT,
                creationDate TEXT,
                expirationDate TEXT 
            );
            
            CREATE TABLE IF NOT EXISTS bans_history (
                ban_id INTEGER PRIMARY KEY AUTOINCREMENT,
                xuid TEXT,
                playerName TEXT,
                reason TEXT,
                source TEXT,
                creationDate TEXT,
                expirationDate TEXT 
            );
            CREATE TABLE IF NOT EXISTS current_bans (
                current_ban_id INTEGER PRIMARY KEY AUTOINCREMENT,
                xuid TEXT,
                playerName TEXT,
                reason TEXT,
                source TEXT,
                creationDate TEXT,
                expirationDate TEXT 
            );
            CREATE TABLE IF NOT EXISTS ip_bans (
                ban_id INTEGER PRIMARY KEY AUTOINCREMENT,
                ip TEXT,
                xuid TEXT,
                playerName TEXT,
                reason TEXT,
                source TEXT,
                creationDate TEXT
            );
        ");
    }



    protected function getCurrentBan( string $xuid ) : array {

        $request = $this->db->prepare("
            SELECT *
            FROM current_bans
            WHERE xuid = :xuid
        ");
        $request->bindValue( ":xuid", $xuid );
        $result = $request->execute()->fetchArray( SQLITE3_ASSOC );

        return $result === false ? [] : $result;
    }

    protected function addCurrentBan( string $xuid, string $playerName, string $reason, string $source, \DateTime $creationDate, \DateTime $expirationDate ){

        $request = $this->db->prepare( "
            INSERT INTO current_bans
                ( xuid, playerName, reason, source, creationDate, expirationDate )
            VALUES 
                ( :xuid, :playerName, :reason, :source, :creationDate, :expirationDate )
        ");
        $request->bindValue( ":xuid", $xuid );
        $request->bindValue( ":playerName", $playerName );
        $request->bindValue( ":reason", $reason );
        $request->bindValue( ":source", $source );
        $request->bindValue( ":creationDate", $creationDate->format( "Y-m-d H:i:s" ) );
        $request->bindValue( ":expirationDate", $expirationDate->format( "Y-m-d H:i:s" ) );
        $request->execute();
    }

    protected function getBanIp( string $xuid, string $ip ) : array {

        $request = $this->db->prepare("
            SELECT ip, xuid, playerName, reason, source, creationDate
            FROM ip_bans
            WHERE ip = :ip
            OR xuid = :xuid
        ");
        $request->bindValue( ":ip", $ip );
        $request->bindValue( ":xuid", $xuid );
        $result = $request->execute()->fetchArray( SQLITE3_ASSOC );

        return $result === false ? [] : $result;
    }

    protected function addBanIp( string $ip, string $xuid, string $playerName, string $reason, string $source, \DateTime $creationDate ){

        $request = $this->db->prepare( "
            INSERT INTO ip_bans
                ( ip, xuid, playerName, reason, source, creationDate )
            VALUES 
                ( :ip, :xuid, :playerName, :reason, :source, :creationDate )
        ");
        $request->bindValue( ":ip", $ip );
        $request->bindValue( ":xuid", $xuid );
        $request->bindValue( ":playerName", $playerName );
        $request->bindValue( ":reason", $reason );
        $request->bindValue( ":source", $source );
        $request->bindValue( ":creationDate", $creationDate->format( "Y-m-d H:i:s" ) );
        $request->execute();
    }

    protected function addExpiredBan( int $current_ban_id, string $xuid, string $playerName, string $reason, string $source, string $creationDate, string $expirationDate ){

        $request = $this->db->prepare( "
            INSERT INTO bans_history
                ( xuid, playerName, reason, source, creationDate, expirationDate )
            VALUES 
                ( :xuid, :playerName, :reason, :source, :creationDate, :expirationDate );
                
            DELETE FROM current_bans
            WHERE current_ban_id = :current_ban_id;
        ");
        $request->bindValue( ":xuid", $xuid );
        $request->bindValue( ":playerName", $playerName );
        $request->bindValue( ":reason", $reason );
        $request->bindValue( ":source", $source );
        $request->bindValue( ":creationDate", $creationDate );
        $request->bindValue( ":expirationDate", $expirationDate );
        $request->bindValue( ":current_ban_id", $current_ban_id );
        $request->execute();
    }

    protected function addWarn( string $xuid, string $playerName, string $source, string $reason, string $creationDate ){
        $request = $this->db->prepare( "
            INSERT INTO warns
                ( xuid, playerName, reason, source, creationDate )
            VALUES 
                ( :xuid, :playerName, :reason, :source, :creationDate );
        ");
        $request->bindValue( ":xuid", $xuid );
        $request->bindValue( ":playerName", $playerName );
        $request->bindValue( ":reason", $reason );
        $request->bindValue( ":source", $source );
        $request->bindValue( ":creationDate", $creationDate );
        $request->execute();
    }

    protected function getWarns( string $xuid, string $date = null, bool $lastWarn = false ){

        if( $lastWarn ){
            $request = $this->db->prepare("
                SELECT * FROM warns
                WHERE xuid = :xuid
                ORDER BY DESC LIMIT 1;
            ");
            $request->bindValue( ":xuid", $xuid );
            $result = $request->execute()->fetchArray( SQLITE3_ASSOC );

            return $result === false ? [] : $result;
        }

        $sqlRequest = "SELECT * FROM warns WHERE xuid = :xuid ";

        if( !empty( $date ) ){
            $now = new \DateTime();
            $sqlRequest .= "AND creationDate BETWEEN \"{$date}\" AND \"{$now->format( "Y-m-d H:i:s" )}\" ";
        }
        $sqlRequest .= "ORDER BY creationDate DESC;";


        $request = $this->db->prepare( $sqlRequest );
        $request->bindValue( ":xuid", $xuid );
        $sqlResult = $request->execute();

        $result = [];
        while ( $row = $sqlResult->fetchArray( SQLITE3_ASSOC ) ){
            $result[] = $row;
        }

        return $result;
    }

    protected function removeWarn( int $warn_id ){

        $request = $this->db->prepare("
                DELETE FROM warns
                WHERE warn_id = :warn_id;
            ");
        $request->bindValue( ":warn_id", $warn_id );
        $result = $request->execute();
    }

}