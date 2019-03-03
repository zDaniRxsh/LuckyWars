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
use pocketmine\event\player\{PlayerQuitEvent, PlayerJoinEvent, PlayerInteractEvent, PlayerDeathEvent, PlayerMoveEvent};
use pocketmine\event\block\{BlockBreakEvent,BlockPlaceEvent};
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\level\sound\PopSound;
use pocketmine\block\Block;
 use pocketmine\event\entity\ProjectileHitEvent;
 use pocketmine\entity\Snowball;
class Arena implements Listener{

public function __construct(LuckyWars $plugin){

$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
$this->db = $plugin;
}

public function onBlockPlaceEvent(BlockPlaceEvent $event){
if(empty($this->db->getDataFolder()."Arenas/")) return;
	$scan = scandir($this->db->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
	$arena = new Config($this->db->getDataFolder()."Arenas/".$name.".yml", Config::YAML);
	if($event->getPlayer()->getLevel()->getFolderName() == $arena->get("level")){
		if($arena->get('status') == 'on' && $arena->get('lobbytime') > 0){
		$event->setCancelled(true);
	}
		if($arena->get('status') == 'reset' && $arena->get('lobbytime') > 0){
		$event->setCancelled(true);
	}
	if($arena->get('status') == 'off' && $arena->get('lobbytime') > 0){
		$event->setCancelled(true);
	}
	}
}
}
}

public function onBlockBreakEvent(BlockBreakEvent $event){
if(empty($this->db->getDataFolder()."Arenas/")) return;
	$scan = scandir($this->db->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
	$arena = new Config($this->db->getDataFolder()."Arenas/".$name.".yml", Config::YAML);
	if($event->getPlayer()->getLevel()->getFolderName() == $arena->get("level")){
		if($arena->get('status') == 'on' && $arena->get('lobbytime') > 0){
		$event->setCancelled(true);
	}
		if($arena->get('status') == 'reset' && $arena->get('lobbytime') > 0){
		$event->setCancelled(true);
	}
	if($arena->get('status') == 'off' && $arena->get('lobbytime')>0){
		$event->setCancelled(true);
	}
	}
}
}
}
  public function colocarBloque(BlockPlaceEvent $event)
    {
        
            $jugador = $event->getPlayer();
            $dinamita = $event->getBlock()->getId();
            if(empty($this->db->getDataFolder()."Arenas/")) return;
	$scan = scandir($this->db->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
	$arena = new Config($this->db->getDataFolder()."Arenas/".$name.".yml", Config::YAML);
	if($event->getPlayer()->getLevel()->getFolderName() == $arena->get("level")){
            if ($dinamita === 46) {
                $event->getBlock()->ignite();
               
            }
}}}
        

    }
 public function enDrop(PlayerDropItemEvent $event) {
          $player = $event->getPlayer();
          $scan = scandir($this->db->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
	$arena = new Config($this->db->getDataFolder()."Arenas/".$name.".yml", Config::YAML);
	if($event->getPlayer()->getLevel()->getFolderName() == $arena->get("level")){
		if($arena->get('status') == 'on'){
			$event->setCancelled(true);
		}
if($arena->get('status') == 'reset'){
			$event->setCancelled(true);
		}
	}
}
}

  }
  public function onEntityDamangeEvent(EntityDamageEvent $event){
if($event instanceof EntityDamageByEntityEvent)
		{
			$player = $event->getEntity();
			$damager = $event->getDamager();
			if($player instanceof Player)
			{
				if($damager instanceof Player)
				{
$scan = scandir($this->db->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
	$arena = new Config($this->db->getDataFolder()."Arenas/".$name.".yml", Config::YAML);
	if($event->getEntity()->getLevel()->getFolderName() == $arena->get("level")){

$status = $arena->get("status");

if($status == "on"){
	 $event->setCancelled(true);
}
if($status == 'off' && $arena->get('lobbytime')>0){
 $event->setCancelled(false);	
}
if($status == 'reset'){
 $event->setCancelled(true);	
}




}
}
				}
			}
		}
}
}
public function caida(EntityDamageEvent $event){
	$player = $event->getEntity();
		
			
if ($event->getCause() === EntityDamageEvent::CAUSE_FALL) {
$event->setCancelled(true);
}

if ($event->getCause() === EntityDamageEvent::CAUSE_BLOCK_EXPLOSION) {
$event->setCancelled(true);

}
if ($event->getCause() === EntityDamageEvent::CAUSE_ENTITY_EXPLOSION) {
$event->setCancelled(true);

}
}
 
            
   }

