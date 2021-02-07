<?php

namespace HenoriaMod\Utils;


class BanEntry{

    private $xuid;
    private $playerName;

    private $reason;
    private $source;

    private $creationDate;
    private $expirationDate  = null;
    private $ip = null;

    public function __construct( string $xuid, string $playerName ){
        $this->xuid = $xuid;
        $this->playerName = $playerName;
        $this->creationDate = new \DateTime();
    }



    public function getXuid() : string {
        return $this->xuid;
    }

    public function getPlayerName() : string {
        return $this->playerName;
    }

    // Setter/Getter reason
    public function setReason( string $reason ){
        $this->reason = $reason;
    }

    public function getReason() : string {
        return $this->reason;
    }


    // Setter/Getter source
    public function setSource( string $source ){
        $this->source = $source;
    }

    public function getSource() : string {
        return $this->source;
    }



    // Setter/Getter expirationDate
    public function setExpire( \DateTime $date ){
        $this->expirationDate = $date;
    }

    public function getExpire(){
        return $this->expirationDate;
    }

    public function hasExpired() : bool{
        $now = new \DateTime();
        return empty( $this->expirationDate ) ? false : $now > $this->expirationDate;
    }



    // Setter/Getter creationDate
    public function setCreation( \DateTime $date ){
        $this->creationDate = $date;
    }

    public function getCreation() : \DateTime {
        return $this->creationDate;
    }



    // Setter/Getter ip
    public function setIp( string $ip ){
        $this->ip = $ip;
    }

    public function getIp(){
        return $this->ip;
    }
}