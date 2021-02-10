<?php

namespace HenoriaMod\Utils;


class WarnEntry{

    private $xuid;
    private $playerName;

    private $warn_id;

    private $reason;
    private $source;

    private $creationDate;

    public function __construct( string $xuid, string $playerName ){
        $this->xuid = $xuid;
        $this->playerName = $playerName;
        $this->creationDate = new \DateTime();
    }




    public function setWarnId( int $warn_id ){
        $this->warn_id = $warn_id;
    }

    public function getWarnId(){
        return $this->warn_id;
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




    // Setter/Getter creationDate
    public function setCreation( \DateTime $date ){
        $this->creationDate = $date;
    }

    public function getCreation() : \DateTime {
        return $this->creationDate;
    }



}