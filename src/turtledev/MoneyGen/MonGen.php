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


namespace turtledev\MoneyGen;
use turtledev\MoneyGen\Event\onPlayerInteractItem;
use turtledev\MoneyGen\Event\onPlayerDisconnect;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
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
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\entity\Effect;
use pocketmine\tile\Chest;
use pocketmine\inventory\ChestInventory;
use pocketmine\nbt\tag\StringTag;
use onebone\economyapi\EconomyAPI;
use jojoe77777/FormAPI/SimpleForm;

class Main extends PluginBase {
    protected $generatedUniqueIds = "";
    public $itemNameGen1 = "Generator Lvl 1";
    protected $db;
    protected $timer;

    public function onLoad(): void {
        $this->getLogger()->info("Loading Script...");
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
            case "genshop":
                    if($sender instamceof Player){
                            $this->openShopForm($sender);
                    }
            case "mongen":
                    if($sender instanceof Player){
                        if(!$sender->hasPermission("mongen.get")){
                            $sender->sendMessage("You've don't have required hasPermission");
                        }
                        $item = Item::get(399.0,1);
                        $item->setCustomName($this->itemNameGen1);
                        $item->setNamedTagEntry(new StringTag("Gen","1"));
                        $sender->getInvemtory()->addItem($item);

                    }else{
                            $sender->sendMessage("Must run in game");
                    }
                // Connect to Website : MySQL
                break;
            case "upgen":
                break;

        }
    }
    public function openShopForm(Player $s){
            $form = new SimpleForm(function (Player $p, int $data){
                $result = $data;
                if($result === null){
                        return true;
                }
                switch($result){
                        case 0:
                                $q = $db->query("SELECT * FROM data WHERE GenLvl = 1");
                                if(!$q instanceof \SQLite3Result){
                                        $p->sendMessage("Internal Error Been Found!, System cannot fix that");
                                        return true;
                                }
                                $arr = $q->fetchArray(SQLITE3_ASSOC);
                                if($arr[$p->getName()] == "buyyed"){
                                        $p->sendMessage("You've Already Buy This One");
                                        return true;
                                }
                                if(EconomyAPI::getInstance()->myMoney($p) < 35000){
                                        $p->sendMessage("You money is too low, need " . 35000 - EconomyAPI::getInstance()->myMoney($p) . "money to buy this";
                                }
                                EconomyAPI::getInstance()->reduceMoney($p, 35000);
                                $item = Item::get(399,0,1);
                                $item->setCustomName($this->itemNameGen1);
                                $item->setNamedTag(new StringTag("Gen","1"));
                                $p->getInventory()->addItem($item);
                                $p->sendMessage("Thank you to visit!");
                                break;
                        case 1:
                                $p->sendMessage("Thank you to visit!");
                                break;

                }

            });
            $form->setTitle("Generator Shop");
            $form->setContent("Select action what you want to do");
            $form->addButton("Buy Generator Lv. 1");
            $form->sendToPlayer($s);
            return $form;
    }

    public function getTimer(){
        return $timer;
    }

    public function setTimer($timers){
        $timer = $timers
    }




}
