<?php

use turtledev\MoneyGen\MoneyGen;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class onPlayerJoin implements Listener {
    public function __construct(MoneyGen $m){
        parent::__construct($m);
    }

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();
        $query = $this->db->query("SELECT * FROM data WHERE name = '$name'");
        if(!$query instanceof \SQLite3Result){
            throw new Exception("Error in querying database");
        }
        $arr = $query->fetch_array(SQLITE3_ASSOC);
        $cd = $arr["cooldown"];
        if($cd == null){
            $cdt = $this->getCD();
            $c = $cdt += [$name => 5];
            $this->setCD($c);
        }
        if($this->getCD()[$name] == null){
            $t = $this->getCD() += [$name => $cd];
            $this->setCD($t);
        }else{
            $t = $this->getCD();
            $t[$name] = $cd;
            $this->setCD($t);
        }
    }

}
