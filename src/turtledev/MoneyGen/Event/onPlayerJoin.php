<?php

use turtledev\MoneyGen\MoneyGen;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class onPlayerJoin implements Listener {
    protected $plugin;
    public function __construct(MoneyGen $m){
        $this->plugin = $m;
    }

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();
        $query = $this->plugin->db->query("SELECT * FROM data WHERE name = '$name'");
        if(!$query instanceof \SQLite3Result){
            throw new Exception("Error in querying database");
        }
        $arr = $query->fetch_array(SQLITE3_ASSOC);
        $cd = $arr["cooldown"];
        if($cd == null){
            $cdt = $this->getCD();
            $c = $cdt += [$name => 5];
            $this->plugin->setCD($c);
        }
        if($this->plugin->getCD()[$name] == null){
            $t = $this->getCD() += [$name => $cd];
            $this->plugin->setCD($t);
        }else{
            $t = $this->getCD();
            $t[$name] = $cd;
            $this->plugin->setCD($t);
        }
    }

}
