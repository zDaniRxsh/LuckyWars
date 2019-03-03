<?php

namespace lw\Listener;
use pocketmine\event\Listener;
use pocketmine\{Server,Player};
use pocketmine\utils\Config;
use pocketmine\math\{Vector2,Vector3};
use lw\LuckyWars;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\event\player\{PlayerInteractEvent};
use pocketmine\item\Item;
use pocketmine\event\player\PlayerItemHeldEvent;
class Menu implements Listener{

public function __construct(LuckyWars $plugin){

$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
$this->db = $plugin;
}

public function onMenuPacket(PlayerInteractEvent $event){
	$player = $event->getPlayer();
	$item = $event->getItem()->getId();
	$scan = scandir($this->db->getDataFolder()."Arenas/");
		foreach($scan as $files){
		if($files !== ".." and $files !== "."){
		$name = str_replace(".yml", "", $files);
		if($name == "") continue;
		$arena = new Config($this->db->getDataFolder()."Arenas/".$name.".yml", Config::YAML);
		if($event->getPlayer()->getLevel()->getFolderName() == $arena->get("level")){
	if($item == 426){
		//leave game
		$this->db->quitGame($player);
	}
	if($item == 339){
		//ksit
		$this->db->addForm($player);

	}
}
}
}
}
public function onMenuPacketName(PlayerItemHeldEvent $event){
	$player = $event->getPlayer();
	$item = $event->getItem()->getId();
	if($item == 426){
		//leave game
	$player->sendPopup("§l§cSalir del Juego");
	}
	if($item == 339){
		//kits
		$player->sendPopup("§l§aSeleccionar Kit");
	}
}


}
