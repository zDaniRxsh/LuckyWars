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
use pocketmine\item\Item;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\level\sound\{AnvilFallSound,DoorBumpSound};
class Death implements Listener{

public function __construct(LuckyWars $plugin){

$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
$this->db = $plugin;
}

public function onDeath(EntityDamageEvent $event){
 $victima = $event->getEntity();
 if ($event instanceof EntityDamageByEntityEvent) {
                if ($victima instanceof Player && $event->getDamager() instanceof Player) {
$scan = scandir($this->db->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
	$arena = new Config($this->db->getDataFolder()."Arenas/".$name.".yml", Config::YAML);
	if($event->getEntity()->getLevel()->getFolderName() == $arena->get("level")){

 if ($event->getFinalDamage() >= $victima->getHealth()) {

$status = $arena->get("status");
if($status=="off"){
$event->setCancelled(true);
$this->db->addEspectador($victima,$name);
foreach ($this->db->getServer()->getLevelByName($arena->get('level'))->getPlayers() as $p) {
	$this->db->getServer()->getLevelByName($arena->get('level'))->addSound(new DoorBumpSound($p));
	$p->sendMessage($this->db->t."§6".$victima->getName()." §emurio a causa de §6".$event->getDamager()->getName());
}
}
}


	}
}
}

             }
          }
}

public function Void(PlayerMoveEvent $event){
	$player = $event->getPlayer();
	$scan = scandir($this->db->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
	$arena = new Config($this->db->getDataFolder()."Arenas/".$name.".yml", Config::YAML);
	if($player->getLevel()->getFolderName() == $arena->get("level")){
$min = $arena->get('minVoid');
if($player->getY() <= $min && $arena->get('status')=='off'){
	if($player->getGamemode() == 0){
$this->db->addEspectador($player,$name);
foreach ($this->db->getServer()->getLevelByName($arena->get('level'))->getPlayers() as $p) {
	$this->db->getServer()->getLevelByName($arena->get('level'))->addSound(new DoorBumpSound($p));
	$p->sendMessage($this->db->t."§6".$player->getName()." §emurio a causa de §6 Void");
}
}else{
	if($player->getGamemode() == 3){
		$lob = $arena->get('espectador');
	$player->teleport(new Vector3($lob[0],$lob[1],$lob[2]));
	}
}
}
if($player->getY() <= $min && $arena->get('status')=='on'){
	$lob = $arena->get('lobby');
	$player->teleport(new Vector3($lob[0],$lob[1],$lob[2]));
}
if($player->getY() <= $min && $arena->get('status')=='reset'){
	$lob = $arena->get('lobby');
	$player->teleport(new Vector3($lob[0],$lob[1],$lob[2]));
}


	}
}
}
}



}
