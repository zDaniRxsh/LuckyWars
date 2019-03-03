<?php
namespace lw\Entity;
use pocketmine\{Server,Player};
use pocketmine\level\Level;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use pocketmine\math\{Vector2,Vector3};
use lw\LuckyWars;
use lw\Entity\{LWNPC,GanadorNPC};
use pocketmine\network\mcpe\protocol\MoveEntityAbsolutePacket;
class UpdateNPC extends Task {
public $yawr = 0;

public function __construct(LuckyWars $eid){

		$this->plugin = $eid;

	}
public function onRun(int $currentTick){
if($this->yawr <360){
$r = $this->yawr+30;
	$this->yawr = $r;
}
if($this->yawr >= 360){
	$this->yawr = 0;
}
$this->plugin->onUpdateStatic();
foreach($this->plugin->getServer()->getLevels() as $level) {
			foreach($level->getEntities() as $entity) {
	if($entity instanceof LWNPC){
		if(empty($this->plugin->getDataFolder()."Arenas/")) return;
	$scan = scandir($this->plugin->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
$entity->setNameTag($this->plugin->updatename());
$entity->setNametagVisible(true);
$entity->setNameTagAlwaysVisible(true);
$entity->setImmobile(true);
$entity->setScale(1.4);
}
}
}
}
}
foreach($this->plugin->getServer()->getLevels() as $level) {
			foreach($level->getEntities() as $entity) {
	if($entity instanceof GanadorNPC){
$entity->setNametagVisible(true);
$entity->setNameTagAlwaysVisible(true);
$entity->setImmobile(true);
  $entity->broadcastEntityEvent(4);
    $entity->setRotation($this->yawr,0);
}
}
}
$this->plugin->players = $this->plugin->countPlayers();
if(empty($this->plugin->getDataFolder()."Arenas/")) return;
	$scan = scandir($this->plugin->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
	$arena = new Config($this->plugin->getDataFolder()."Arenas/".$name.".yml", Config::YAML);
	if(empty($arena->get("level"))) return;
$levelArena = $this->plugin->getServer()->getLevelByName($arena->get("level"));

				if($levelArena instanceof Level)

				{

					$players = $levelArena->getPlayers();
					foreach ($players as $p) {
					if($arena->get('status')== 'on' or $arena->get('status') == 'reset'){
						$p->setFood(20);
					}
					}
$pl = count($players);
$arena->set('playersOnlineArena',$pl);
$arena->save();

}
}
}
}


}
