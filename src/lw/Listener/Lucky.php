<?php

namespace lw\Listener;
use pocketmine\event\Listener;
use pocketmine\{Server,Player};
use pocketmine\utils\Config;
use pocketmine\math\{Vector2,Vector3};
use lw\LuckyWars;
use pocketmine\entity\{Entity};
use pocketmine\entity\object\{PrimedTNT,FallingBlock};
use pocketmine\level\Level;
use pocketmine\event\block\{BlockBreakEvent,BlockPlaceEvent};
use pocketmine\item\Item;
use pocketmine\block\{Block,BlockFactory,Bedrock,Air};
class Lucky implements Listener{

public function __construct(LuckyWars $plugin){

$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
$this->db = $plugin;
}

public function onLucky(BlockBreakEvent $event){
$player = $event->getPlayer();
$block = $event->getBlock();
if(empty($this->db->getDataFolder()."Arenas/")) return;
	$scan = scandir($this->db->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
	$arena = new Config($this->db->getDataFolder()."Arenas/".$name.".yml", Config::YAML);
if($block->getId()==19){
if($event->getPlayer()->getLevel()->getFolderName() == $arena->get("level")){
$rand = mt_rand(1,11);
$pos = new Vector3($block->x,$block->y,$block->z);
$level = $this->db->getServer()->getLevelByName($arena->get('level'));
  $event->setDrops(array(Item::get(Item::AIR,0,1)));
if($rand == 1){
$level->dropItem($pos,Item::get(276,0,1));
}
if($rand == 2){
$level->dropItem($pos,Item::get(261,0,1));
$level->dropItem($pos,Item::get(262,0,15));
}
if($rand == 3){
	$level->dropItem($pos,Item::get(322,0,3));
}
if($rand == 4){
	$level->dropItem($pos,Item::get(368,0,3));
}
if($rand == 5){
	$level->dropItem($pos,Item::get(1,0,64));
}
if($rand == 6){
	//eliminar bloques
	$level->setBlock($pos, new Air());
	$level->setBlock(new Vector3($block->x,$block->y+1,$block->z), new Air());
	$level->setBlock(new Vector3($block->x,$block->y+2,$block->z), new Air());
	$level->setBlock(new Vector3($block->x,$block->y+3,$block->z), new Air());
	$level->setBlock(new Vector3($block->x,$block->y+4,$block->z), new Air());
	$level->setBlock(new Vector3($block->x,$block->y+5,$block->z), new Air());
	$level->setBlock(new Vector3($block->x,$block->y+6,$block->z), new Air());
	$level->setBlock(new Vector3($block->x,$block->y+7,$block->z), new Air());
	$level->setBlock(new Vector3($block->x,$block->y+8,$block->z), new Air());
	$level->setBlock(new Vector3($block->x,$block->y+9,$block->z), new Air());
	$level->setBlock(new Vector3($block->x,$block->y+10,$block->z), new Air());

	//redstone block
	$posA = new Vector3($block->x,$block->y+5,$block->z);
		$compoundtag = Entity::createBaseNBT($posA);
		$compoundtag->setInt("TileID", 152);
        $compoundtag->setByte("Data", 0);
        $npcgame = Entity::createEntity("FallingBlock", $level, $compoundtag);
        $npcgame->spawnToAll();
        //diamond
        $posB = new Vector3($block->x,$block->y+7,$block->z);
		$compoundtaga = Entity::createBaseNBT($posB);
		$compoundtaga->setInt("TileID", 57);
        $compoundtaga->setByte("Data", 0);
        $npcgamea = Entity::createEntity("FallingBlock", $level, $compoundtaga);
        $npcgamea->spawnToAll();
        //restone
        $posA = new Vector3($block->x,$block->y+10,$block->z);
		$compoundtag = Entity::createBaseNBT($posA);
		$compoundtag->setInt("TileID", 152);
        $compoundtag->setByte("Data", 0);
        $npcgame = Entity::createEntity("FallingBlock", $level, $compoundtag);
        $npcgame->spawnToAll();
}
if($rand == 7){
 $compoundtag = Entity::createBaseNBT($pos);
    $npcgame = Entity::createEntity("PrimedTNT", $level, $compoundtag);
    $npcgame->setScale(3.0);
    $npcgame->setNametagVisible(true);
$npcgame->setNameTagAlwaysVisible(true);
    $npcgame->spawnToAll();
    
}
if($rand == 8){
$level->dropItem($pos,Item::get(19,0,2));
}
if($rand == 9){
$level->dropItem($pos,Item::get(310,0,1));
$level->dropItem($pos,Item::get(307,0,1));
$level->dropItem($pos,Item::get(312,0,1));
$level->dropItem($pos,Item::get(317,0,1));
}
if($rand == 10){
$level->dropItem($pos,Item::get(322,1,3));
$level->dropItem($pos,Item::get(283,0,1));
}
if($rand == 11){
	$level->dropItem($pos,Item::get(251,2,64));
}
}
}

}
}
}






}
