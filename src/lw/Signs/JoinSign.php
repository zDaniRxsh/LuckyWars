<?php
namespace lw\Signs;

use pocketmine\event\Listener;
use pocketmine\{Server,Player};
use pocketmine\level\Level;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\tile\Sign;
use lw\LuckyWars;
use pocketmine\event\player\{PlayerQuitEvent, PlayerJoinEvent, PlayerInteractEvent, PlayerDeathEvent, PlayerMoveEvent};
class JoinSign implements Listener {

public function __construct(LuckyWars $plugin){

$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
$this->db = $plugin;
}


public function onJoinGame(PlayerInteractEvent $event){
$player = $event->getPlayer();
$block = $event->getBlock();
$tile = $player->getLevel()->getTile($block);
if($tile instanceof Sign) 
		{
$text = $tile->getText();
$prefix = "§l§7[§aLucky§eWars§7]";
if($text[0]==$prefix){
$name = $text[2];
$arena = new Config($this->db->getDataFolder()."Arenas/".$name.".yml", Config::YAML);
$status = $arena->get("status"); $players = $arena->get("playersOnlineArena");
if($status == "on" && $players < 6){
	//joinGame
$this->db->joinMatchSign($player,$name);
}
if($status=="on" && $players==6){
	$player->sendMessage($this->db->t." El juego ya esta completo");
}
if($status=="off"){
	$player->sendMessage($this->db->t." El juego  ya comenzo!");
}
if($status=='reset'){
$player->sendMessage($this->db->t." El juego  esta terminando!");
}


}
	
		}

	}

}
