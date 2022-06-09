<?php

// Using Modified Source Code (Fork) : Pocketmine/SimpleAuth
// ZioCraft
/*
 * AdvLogin plugin for PocketMine-MP
 * Copyright (C) 2014 TurtleTeam <https://github.com/turtleion/AdvLogin>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
*/


namespace KontolodonTerbang;


use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\tile\Sign;
use pocketmine\level\Level;
use pocketmine\item\Item;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamagaaeByEntityEvent;
use pocketmine\entity\Effect;
use pocketmine\tile\Chest;
use pocketmine\inventory\ChestInventory;
use pocketmine\nbt\tag\StringTag;



class Main extends PluginBase implements Listener {
    protected $email = "";
    protected $emailConf = "";
    protected $pin = 0;
    protected $otpcode = 0;
    protected $generatedUniqueIds = "";
    protected $db;

    public function onLoad(): void {
        $this->getLogger()->info(Colors::GREEN . "Loading Script...");
    }

    public function onEnable() : void {
        @mkdir($this->getDataFolder());
        $this->getLogger()->info("§ePlugin §6LoginUI §ehas enable!.");
        $this->saveResource("settings.yml");
        $this->settings = new Config($this->getDataFolder() . "settings.yml", Config::YAML);;
        $this->saveDefaultConfig();

        if(!file_exists($this->getDataFolder() . 'player.db')){
            $this->db = new \SQLite3($this->getDataFolder() . 'player.db', SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE );
            $sqlres = $this->getResource("sqlite_setup.sql");
            $this->db->exec(stream_get_contents($sqlres));
            @fclose($sqlres);
        }else{
            $this->db = new \SQLite3($this->getDataFolder() . 'player.db',  SQLITE3_OPEN_READWRITE );
        }

        if(!settings["appversion"] == "1.0.0"){
            $this->getLogger()->info("Failed Load : Incompatible App Version");
            throw new Exception("[MonGen] Error Occured!");
        }



        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("Everything loaded!");
    }

    public function onCommand((CommandSender $sender, Command $command, $label, array $args) : bool{
        
        switch($command->getName()){
            case "mongen":
                
                break;
            
            case "verifycode":
                if(isset($args[0]) && isset($args[1])){
                    $sender->sendMessage(Colors::RED . "Needed 2 Arguments, Please Correct Again What You Type");
                    return true;
                }
                if(count($args) !== 2){
                    $sender->sendMessage(Colors::RED . "Needed 2 Arguments, Please Correct Again What You Type");
                }

                $code = $args[0];
                $pin = $args[1];

                
                if(strlen($pin) < 4 || strlen($code) < 6){
                    $sender->sendMessage(Colors:YELLOW . "Email must be 5 characters or above | if you're email actually 5 char only, you can use other email");
                    return true;
                }

                // Connect to Website : MySQL
                break;
            case "login":

        }
    }

    public function unLoggedWarning(Player $sender){
        $sender->addEffect(EFFECT::BLINDNESS)->setDuration(62441286000);
        $sender->addEffect(EFFECT::INVICIBILITY)->setDuration(62441286000);
        $cmd = "title \"" . "\" title \"Please Register!\" ";
        $sender->sendMessage("use /register to register\nUsage : /register example@gmail.com example@gmail.com\n");        
        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), $cmd);
    }

    public function onPlayerJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $playername = trim(strtolower($player->getName()));
/*        $prepare = $this->db->prepare("SELECT * FROM playerdata WHERE name = :name");
        $prepare->bindValue(":name",$playername,SQLITE3_TEXT);
                        $parsedData = $res->fetchArray(SQLITE3_ASSOC);
                $uuid = $parsedData["uuid"];
                $email = $parsedData["email"];
                $res->finalize();
*/
        $res = $prepare->execute();
        if($res instanceof \SQLite3Result){
            if($res->numRows() <= 0){
                $player->sendMessage("Please register your account to enter this server");
                $this->unLoggedWarning($player);

            }

        }
    }
}
