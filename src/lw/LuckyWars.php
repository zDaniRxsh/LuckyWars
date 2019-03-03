<?php

namespace lw;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\{Server,Player};
use pocketmine\level\Level;
use pocketmine\scheduler\Task;
use pocketmine\command\{CommandSender,Command};
use pocketmine\level\sound\PopSound;
use pocketmine\item\Item;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\entity\{Entity};
use pocketmine\utils\Color;
use pocketmine\item\Armor;
use pocketmine\item\ItemFactory;
use lw\Entity\{LWNPC,UpdateNPC,EventsNPCLW,GanadorNPC};
use lw\Task\{Buscador,BossUpdate,GameTask};

use lw\Form\MenuForm;
use pocketmine\network\mcpe\protocol\{ChangeDimensionPacket,PlayStatusPacket};
use lw\Dimension;
use lw\Signs\{ChallangeSign,UpdateSign,JoinSign};
 use lw\AntiCheat\{CommandPacketExecute};
 use hola\Main;

 use lw\Buse lw\Listener\{Menu,QuitPlayer,Arena,Death,Lucky,Debug};

class LuckyWars extends PluginBase{

public $t = "§aLucky§eWars §8> §r";
public $players = 0;
public $gamea = null;
public $countTX = 0;
public $plArena = 0;
public $Buscador = null;
public function onEnable(){
$this->getServer()->getLogger()->info($this->t." §aGame enbled");
 $this->core = $this->getServer()->getPluginManager()->getPlugin("DragonCore");
  $this->lang = $this->getServer()->getPluginManager()->getPlugin("BaseLangAPI");
$this->registerTask();
$this->registerListener();
$this->registerEntity();
$this->registerConfigs();
$this->loadArenas();
$this->reloadArenas();
}
public function onDisable(){
	$this->reloadConfig();
	$this->reloadArenas();
}

public function registerListener(){
	new Debug($this);
new EventsNPCLW($this);
new Menu($this);
new QuitPlayer($this);
new Arena($this);
new Death($this);
new ChallangeSign($this);
new JoinSign($this);
new CommandPacketExecute($this);
new Lucky($this);
}

public function registerTask(){
$this->getScheduler()->scheduleRepeatingTask(new UpdateNPC($this), 20);
$this->getScheduler()->scheduleRepeatingTask(new BossUpdate($this), 20);
$this->getScheduler()->scheduleRepeatingTask(new GameTask($this), 20);
$this->getScheduler()->scheduleRepeatingTask(new UpdateSign($this), 20);

}

public function registerEntity(){
Entity::registerEntity(LWNPC::class, true);
Entity::registerEntity(GanadorNPC::class, true);

}

public function registerConfigs(){
	$cfg = new Config($this->getDataFolder()."Kits.yml",Config::YAML);
	$cfg->save();
@mkdir($this->getDataFolder()."Arenas");
@mkdir($this->getDataFolder()."Mapas");
$this->reloadConfig();
}
public function loadArenas(){


	if(empty($this->getDataFolder()."Arenas/")) return;
	$scan = scandir($this->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
	$arena = new Config($this->getDataFolder()."Arenas/".$name.".yml", Config::YAML);
	if(empty($arena->get("level"))) return;
	$level = $arena->get("level");
	$this->getServer()->loadLevel($level);
    $world = $this->getServer()->getLevelByName($level);
    if(!$world instanceof Level) continue;
	$world->setTime(0);
	$world->stopTime();

	}

	}
$this->getServer()->getLogger()->info($this->t."Arenas cargadas");
	}
public function addBossPLayer($id,Player $player){
$arena = new Config($this->getDataFolder()."Arenas/lw-".$id.".yml", Config::YAML);
	$ids = $arena->get("idBoss");
API::sendBossBarToPlayer($player, $ids, "Cargando");
}
public function removeBossToPlayer(Player $player,$arena){
$arena = new Config($this->getDataFolder()."Arenas/".$arena.".yml", Config::YAML);
	$id = $arena->get("idBoss");

		API::removeBossBar([$player], $id);
}

public function removeBossToArena(Player $player){

	if(empty($this->getDataFolder()."Arenas/")) return;
	$scan = scandir($this->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
	$arena = new Config($this->getDataFolder()."Arenas/".$name.".yml", Config::YAML);

$id = $arena->get("idBoss");

		API::removeBossBar([$player], $id);

}
}
}
public function sendBossBar($game){
	$arena = new Config($this->getDataFolder()."Arenas/".$game.".yml", Config::YAML);
	$id = $arena->get("idBoss");
	if($id == null){
		return;
	}else{
		foreach($this->getServer()->getLevelByName($arena->get("level"))->getPlayers() as $player){
			API::setTitle($this->Text($game),$id, [$player]);

API::setPercentage(intval($this->vida($game)),$id);
}
}}
public function getGame(){

	return "§bJoin §7: §d".$this->gamea."§7: §7(§a".$this->plArena."§7/§a6§7)";
}
public function getTotalPlayers(){
	return "§d".$this->players."§a Playing";
}
public function getTime($int) {
    $m = floor($int / 60);
    $s = floor($int % 60);
    return (($m < 10 ? "0" : "") . $m . "§b:§d" . ($s < 10 ? "0" : "") . $s);
    }
    public function onUpdateStatic(){
if($this->countArchivos()>0){

	if($this->countTX>=$this->countArchivos()){
	$this->countTX = 0;
	$this->gamea = null;

}

$name = "lw-".$this->countTX;
$arena = new Config($this->getDataFolder()."Arenas/".$name.".yml", Config::YAML);
if($arena->get("status")=="reset"){
	$this->countTX++;
}
if($arena->get("status")=="off"){
	$this->countTX++;

}else{
if($arena->get("status")=="on" && $arena->get('playersOnlineArena')<6){
	$this->Buscador = $this->countTX;
$this->gamea = $arena->get('nameArena');
$this->plArena = $arena->get('playersOnlineArena');
	$this->countTX = 0;
	$this->gamea = $arena->get('nameArena');
}
}

}

}
public function Text($game){
$arena = new Config($this->getDataFolder()."Arenas/".$game.".yml", Config::YAML);
if($arena->get('status') == 'on' && $arena->get('playersOnlineArena') < 6 && $arena->get('lobbytime') == 100){
	$text1 = "         §l§aLucky§eWars§r";
	$text2 = "§bBuscando Players §a: §7".$arena->get('playersOnlineArena')."/6";
}
if($arena->get('status') == 'on' && $arena->get('playersOnlineArena')< 6 && $arena->get('lobbytime') < 100){
	$text1 = "             §l§aLucky§eWars§r";
	$text2 = "§bComenznado en §a:§d ".$this->getTime($arena->get('lobbytime'))."§b segundos";
}
if($arena->get('status') == 'on' && $arena->get('playersOnlineArena') == 6 ){
	$text1 = "             §l§aLucky§eWars§r";
	$text2 = "§bComenznado en §a:§d ".$this->getTime($arena->get('lobbytime'))."§b segundos";
}
if($arena->get('status') == 'off' && $arena->get('playersOnlineArena') == 6 && $arena->get('lobbytime') < 12){
	$text1 = "             §l§aLucky§eWars§r";
$text2 = "§bComenznado en §a:§d ".$this->getTime($arena->get('lobbytime'))."§b segundos";
}
if($arena->get('status') == 'off'  && $arena->get('lobbytime') < 11){
	$text1 = "             §l§aLucky§eWars§r";
$text2 = "§bComenznado en §a:§d ".$this->getTime($arena->get('lobbytime'))."§b segundos";
}
if($arena->get('status') == 'off' && $arena->get('lobbytime') == 0){
	$text1 = "            §l§aLucky§eWars§r";
	$text2 = "§eTiempo §b: §d".$this->getTime($arena->get('time'))." §eJugadores§b : §d".$this->countPlayersGM($game);
}
if($arena->get('status') == 'reset'){
$text1 = "               §l§aLucky§eWars§r";
$text2 = "§7Termiando Partida en §b: §d".$arena->get('resetTime')." §7segundos§a...";
}
$br = "\n\n";
return $text1.$br.$text2;
}

public function vida($game){
$arena = new Config($this->getDataFolder()."Arenas/".$game.".yml", Config::YAML);
$lobby = $arena->get("lobbytime");
$status = $arena->get("status");

if($status=="on" or $status == 'off' && $lobby >0){
$vida = $lobby;
}

if($status=="off" && $lobby == 0){
$vida = 100;
}
if($status=="reset"){
$vida = 100;
}
return $vida;

}
public function updatename(){

$t = "§l§aLucky§eWars§r";
$pl = null;
if($this->gamea == null){
	$pl = $this->getTotalPlayers();
}else{
	$pl = $this->getGame();
}
$br = "\n";
return $t.$br.$pl.$br.$br."§l§eCLICK TO PLAY";
}


public function countArchivos(){
	$dir = $this->getDataFolder()."Arenas/";
$a=0;
if (is_dir($dir)) {
    if ($gd = opendir($dir)) {
        while (($archivo = readdir($gd)) !== false) {

            $a++;

        }
        closedir($gd);
    }
}
return $a-2;
}
public function system(Player $player){
	$player->sendMessage($this->t."§7Buscando servidor...");
	if($this->countTX >= $this->countArchivos()){
			$player->sendMessage($this->t."§7Error servidores no encontrados...");
	}else{
$this->core->removeBossToPlayer($player);
	$this->removeBossToArena($player);
	$this->joinMatch($player,$this->Buscador);
}
}
public function countPlayers(){
	if(empty($this->getDataFolder()."Arenas/")) return;

	$scan = scandir($this->getDataFolder()."Arenas/");

	foreach($scan as $files){

	if($files !== ".." and $files !== "."){

	$name = str_replace(".yml", "", $files);

	if($name == "") continue;

	$arena = new Config($this->getDataFolder()."Arenas/".$name.".yml", Config::YAML);
	$level = $arena->get('level');
$i = count($this->getServer()->getLevelByName($level)->getPlayers());
}
}
return $i;
}



///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////// COMMANDS //////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////

public function createArena(Player $player,$name,$id,$level){
	$idboss = mt_rand(1,1000)+mt_rand(1,500)+mt_rand(1,10);
if(file_exists($this->getDataFolder()."Arenas/lw-".$id.".yml")){
	$player->sendMessage($this->t."Esta arena ya existe");
}else{

$cfg = new Config($this->getDataFolder()."Arenas/lw-".$id.".yml",Config::YAML,[
'nameArena' => $name,
'level' => $level,
'lobby' => 0,
'ganador' => 0,
'espectador' => 0,
'status' => 'on',
'minVoid' => 0,
'idBoss' => $idboss,
'spawn1' => 0,
'spawn2' => 0,
'spawn3' => 0,
'spawn4' => 0,
'spawn5' => 0,
'spawn6' => 0,
'player1' => null,
'player2' => null,
'player3' => null,
'player4' => null,
'player5' => null,
'player6' => null,
'time' => 900,
'lobbytime' => 100,
'resetTime' => 25,
'playersOnlineArena' => 0,
]);

$cfg->save();
$player->sendMessage($this->t.'Ha creado la Arena correctamente : '.$name." ha entrdo en modo creador");
$player->setGamemode(1);
$this->getServer()->loadLevel($level);
$player->teleport($this->getServer()->getLevelByName($level)->getSpawnLocation());
}

}


public function setLobby(Player $player,$id){
if(!file_exists($this->getDataFolder()."Arenas/lw-".$id.".yml")){
	$player->sendMessage($this->t."Esta arena no existe");
}else{
$lobby = new Config($this->getDataFolder()."Arenas/lw-".$id.".yml");
$x = $player->getX(); $y = $player->getY(); $z = $player->getZ();
$lobby->set('lobby',[$x,$y,$z]);
$lobby->save();
$player->sendMessage($this->t."Lobby registrado a id: lw-".$id);

}
}

public function setMinimoVoid(Player $player,$id){
if(!file_exists($this->getDataFolder()."Arenas/lw-".$id.".yml")){
	$player->sendMessage($this->t."Esta arena no existe");
}else{
$lobby = new Config($this->getDataFolder()."Arenas/lw-".$id.".yml");
$y = $player->getY();
$lobby->set('minVoid',$y);
$lobby->save();
$player->sendMessage($this->t."minVoid registrado a id: lw-".$id);
}
}

public function setSpawn(Player $player,$id,$slot){
if(!file_exists($this->getDataFolder()."Arenas/lw-".$id.".yml")){
	$player->sendMessage($this->t."Esta arena no existe");
}else{
$lobby = new Config($this->getDataFolder()."Arenas/lw-".$id.".yml");
$x = $player->getX(); $y = $player->getY(); $z = $player->getZ();
if($slot > 6){
	$player->sendMessage($this->t."Cantidad de slot incorrecta");
}
if(!is_numeric($slot)){
	$player->sendMessage($this->t."Args incorrecto");
}
if($slot == 1){
$lobby->set('spawn1',[$x,$y,$z]);
$lobby->save();
$player->sendMessage($this->t."Spawn1 registrado id : lw-".$id);
}
if($slot == 2){
$lobby->set('spawn2',[$x,$y,$z]);
$lobby->save();
$player->sendMessage($this->t."Spawn2 registrado id : lw-".$id);
}
if($slot == 3){
$lobby->set('spawn3',[$x,$y,$z]);
$lobby->save();
$player->sendMessage($this->t."Spawn3 registrado id : lw-".$id);
}
if($slot == 4){
$lobby->set('spawn4',[$x,$y,$z]);
$lobby->save();
$player->sendMessage($this->t."Spawn4 registrado id : lw-".$id);
}
if($slot == 5){
$lobby->set('spawn5',[$x,$y,$z]);
$lobby->save();
$player->sendMessage($this->t."Spawn5 registrado id : lw-".$id);
}
if($slot == 6){
$lobby->set('spawn6',[$x,$y,$z]);
$lobby->save();
$player->sendMessage($this->t."Spawn6 registrado id : lw-".$id);
}

}
}
public function setSpectador(Player $player , $id){
if(!file_exists($this->getDataFolder()."Arenas/lw-".$id.".yml")){
	$player->sendMessage($this->t."Esta arena no existe");
}else{
$lobby = new Config($this->getDataFolder()."Arenas/lw-".$id.".yml");
$x = $player->getX(); $y = $player->getY(); $z = $player->getZ();
$lobby->set('espectador',[$x,$y,$z]);
$lobby->save();
$player->sendMessage($this->t."Espectador registrado a id: lw-".$id);
}
}
public function setSaveMap(Player $player,$id){
if(!file_exists($this->getDataFolder()."Arenas/lw-".$id.".yml")){
	$player->sendMessage($this->t."Esta arena no existe");
}else{
	$name = 'lw-'.$id;
	$this->zipper($player,$name);
	$player->teleport($this->getServer()->getDefaultLevel()->getSpawnLocation());
	$player->sendMessage($this->t."Arena configurada modo creador off");
}
}
public function GanadorSet(Player $player,$id){
if(!file_exists($this->getDataFolder()."Arenas/lw-".$id.".yml")){
	$player->sendMessage($this->t."Esta arena no existe");
}else{
$lobby = new Config($this->getDataFolder()."Arenas/lw-".$id.".yml");
$x = $player->getX(); $y = $player->getY(); $z = $player->getZ();
$lobby->set('ganador',[$x,$y,$z]);
$lobby->save();
$player->sendMessage($this->t."Spawn NpC Ganador registrado a id: lw-".$id);
}


}
public function zipper($player, $name)

        {

        $path = realpath($player->getServer()->getDataPath() . 'worlds/' . $name);
			$zip = new \ZipArchive;
				@mkdir($this->getDataFolder() . 'Mapas/', 0755);
				$zip->open($this->getDataFolder() . 'Mapas/' . $name . '.zip', $zip::CREATE | $zip::OVERWRITE);
				$files = new \RecursiveIteratorIterator(
					new \RecursiveDirectoryIterator($path),
					\RecursiveIteratorIterator::LEAVES_ONLY
				);

                                foreach ($files as $datos) {

					if (!$datos->isDir()) {
						$relativePath = $name . '/' . substr($datos, strlen($path) + 1);
						$zip->addFile($datos, $relativePath);

					}

				}

				$zip->close();
				$player->getServer()->loadLevel($name);
				unset($zip, $path, $files);

        }
public function createCLONE(Player $player){
		$nbt = Entity::createBaseNBT(new Vector3((float) $player->getX(), (float) $player->getY(), (float) $player->getZ()));
$nbt->setTag(clone $player->namedtag->getCompoundTag("Skin"));

$humano = new LWNPC($player->getLevel(), $nbt);
$humano->setNameTag("");
$humano->setNametagVisible(true);
$humano->setNameTagAlwaysVisible(true);
$humano->spawnToAll();

	}
	public function startGame(Player $player,$id){
if(!file_exists($this->getDataFolder()."Arenas/lw-".$id.".yml")){
	$player->sendMessage($this->t."Esta arena no existe");
}else{
$data = new Config($this->getDataFolder()."Arenas/lw-".$id.".yml");
if($data->get('playersOnlineArena') >= 2 && $data->get('status') == 'on'){
$data->set('lobbytime',15);
$data->save();
$player->sendMessage($this->t."Has Forzado el start Time reducido a 15 segundos...");
}else{
	$player->sendMessage($this->t."Error Data sutch");
}
}


	}
public function createNPC(PLayer $player){
$this->createCLONE($player);
$player->sendMessage($this->t.'NPC colocado');
}
 public function onCommand(CommandSender $player, Command $cmd, $label, array $args):bool{
        switch($cmd->getName()){
        	case 'lw':

if($player->isOp()){
if(isset($args[0])){
if($args[0] == 'setnpc'){
$this->createNPC($player);
}
if($args[0] == 'removenpc'){
foreach($this->getServer()->getLevels() as $level) {
			foreach($level->getEntities() as $entity) {
				if($entity instanceof LWNPC) {
               $entity->kill();
				}
			}
		}
}
if($args[0] == 'addarena'){
	if(isset($args[1])){
	if(isset($args[2])){
		if(isset($args[3])){
	$this->createArena($player,$args[1],$args[2],$args[3]);
}
	}
	}
}
if($args[0] == 'setlobby'){
if(isset($args[1])){
$this->setLobby($player,$args[1]);
}
}
if($args[0] == 'setminVoid'){
if(isset($args[1])){
$this->setMinimoVoid($player,$args[1]);
}
}
if($args[0] == 'setspawn'){
if(isset($args[1])){
	if(isset($args[2])){
$this->setSpawn($player,$args[1],$args[2]);
}
}
}
if($args[0] == 'setespectador'){
if(isset($args[1])){
$this->setSpectador($player,$args[1]);
}
}
if($args[0] == 'savemap'){
if(isset($args[1])){
$this->setSaveMap($player,$args[1]);
}
}
if($args[0] == 'start'){
if(isset($args[1])){
	$this->startGame($player,$args[1]);
}
}
if($args[0] == 'setganador'){
if(isset($args[1])){
	$this->GanadorSet($player,$args[1]);
}
}
if($args[0] == 'setnpcfixed'){
foreach($this->getServer()->getLevels() as $level) {
			foreach($level->getEntities() as $entity) {
	if($entity instanceof LWNPC){

$entity->setRotation($player->yaw,0);

	}
}
}
}
}
}
return true;

}
}

//JOIN THE MATCH SYSTEM
public function ManagerSlot(Player $player,$id){
$data = new Config($this->getDataFolder()."Arenas/lw-".$id.".yml",Config::YAML);
if($data->get('player1') == null && $data->get('status') == 'on' && $data->get('playersOnlineArena') < 6){
	$data->set('player1',$player->getName());
	$data->save();
}else{
if($data->get('player2') == null && $data->get('status') == 'on' && $data->get('playersOnlineArena') < 6){
	$data->set('player2',$player->getName());
	$data->save();
}else{
	if($data->get('player3') == null && $data->get('status') == 'on' && $data->get('playersOnlineArena') < 6){
	$data->set('player3',$player->getName());
	$data->save();
}else{
	if($data->get('player4') == null && $data->get('status') == 'on' && $data->get('playersOnlineArena') < 6){
	$data->set('player4',$player->getName());
	$data->save();
}else{
	if($data->get('player5') == null && $data->get('status') == 'on' && $data->get('playersOnlineArena') < 6){
	$data->set('player5',$player->getName());
	$data->save();
}else{
	if($data->get('player6') == null && $data->get('status') == 'on' && $data->get('playersOnlineArena') < 6){
	$data->set('player6',$player->getName());
	$data->save();
}else{
	$player->sendMessage($this->t."Error al conectarse");
	$player->teleport($this->getServer()->getDefaultLevel()->getSpawnLocation());

}
}
}
}
}

}
}

public function addMenu(Player $player){
	$player->getInventory()->clearAll();
	$player->getArmorInventory()->clearAll();
	$player->getInventory()->setItem(0,Item::get(339,0,1));
	$player->getInventory()->setItem(8,Item::get(426,0,1));
}
public function joinMatch(Player $player,$id){
$data = new Config($this->getDataFolder()."Arenas/lw-".$id.".yml",Config::YAML);
$name = $player->getName();
$this->addMenu($player);
$this->removerKit($player);
$lobby = $data->get('lobby');
$player->setGamemode(2);
$world = $this->getServer()->getLevelByName($data->get('level'));
$world->setTime(0);
$world->stopTime();
$this->addMessage($player,$id);
$this->getServer()->loadLevel($data->get('level'));
$this->dimension($player, new Vector3($lobby[0],$lobby[1],$lobby[2]),$data->get('level'));
foreach($this->getServer()->getLevelByName($data->get('level'))->getPlayers() as $pl){
	$pla = $data->get('playersOnlineArena')+1;
	$pl->sendMessage($this->t."§a".$name." §ese ha unido §6(§c".$pla."§6/§c6§6)");
}
$this->addBossPLayer($id,$player);
$this->ManagerSlot($player,$id);
}


//signs
public function ManagerSlotSign(Player $player,$id){
$data = new Config($this->getDataFolder()."Arenas/".$id.".yml",Config::YAML);
if($data->get('player1') == null && $data->get('status') == 'on' && $data->get('playersOnlineArena') < 6){
	$data->set('player1',$player->getName());
	$data->save();
}else{
if($data->get('player2') == null && $data->get('status') == 'on' && $data->get('playersOnlineArena') < 6){
	$data->set('player2',$player->getName());
	$data->save();
}else{
	if($data->get('player3') == null && $data->get('status') == 'on' && $data->get('playersOnlineArena') < 6){
	$data->set('player3',$player->getName());
	$data->save();
}else{
	if($data->get('player4') == null && $data->get('status') == 'on' && $data->get('playersOnlineArena') < 6){
	$data->set('player4',$player->getName());
	$data->save();
}else{
	if($data->get('player5') == null && $data->get('status') == 'on' && $data->get('playersOnlineArena') < 6){
	$data->set('player5',$player->getName());
	$data->save();
}else{
	if($data->get('player6') == null && $data->get('status') == 'on' && $data->get('playersOnlineArena') < 6){
	$data->set('player6',$player->getName());
	$data->save();
}else{
	$player->sendMessage($this->t."Error al conectarse");
	$player->teleport($this->getServer()->getDefaultLevel()->getSpawnLocation());

}
}
}
}
}

}
}
public function addBossPLayerSign($id,Player $player){
$arena = new Config($this->getDataFolder()."Arenas/".$id.".yml", Config::YAML);
	$ids = $arena->get("idBoss");
API::sendBossBarToPlayer($player, $ids, "Cargando");
}
public function joinMatchSign(Player $player,$id){
$data = new Config($this->getDataFolder()."Arenas/".$id.".yml",Config::YAML);
$name = $player->getName();
$this->addMenu($player);
$this->removerKit($player);
$lobby = $data->get('lobby');
$world = $this->getServer()->getLevelByName($data->get('level'));
$world->setTime(0);
$world->stopTime();
$player->setGamemode(2);
$this->core->removeBossToPlayer($player);
$this->addMessageSign($player,$id);
$this->getServer()->loadLevel($data->get('level'));
$this->dimension($player, new Vector3($lobby[0],$lobby[1],$lobby[2]),$data->get('level'));
foreach($this->getServer()->getLevelByName($data->get('level'))->getPlayers() as $pl){
	$pla = $data->get('playersOnlineArena')+1;
	$pl->sendMessage($this->t."§a".$name." §ese ha unido §6(§c".$pla."§6/§c6§6)");
}
$this->addBossPLayerSign($id,$player);
$this->ManagerSlotSign($player,$id);
}
public function addMessageSign(Player $player,$id){
$cfg = new Config($this->getDataFolder()."Arenas/".$id.".yml",Config::YAML);
$arena = $cfg->get('nameArena');
$msg1 = "§a=================";
$msg2 = "     §l§aLucky§eWars§r     ";
$msg3 = "§bArena §8: §d".$arena;
$msg4 = "§6Selecciona tu Kit";
$msg5 = "§a=================";
$br = "\n";
$player->sendMessage($msg1.$br.$msg2.$br.$msg3.$br.$msg4.$br.$msg5);
}
public function quitGame(Player $player){
	if(empty($this->getDataFolder()."Arenas/")) return;

	$scan = scandir($this->getDataFolder()."Arenas/");

	foreach($scan as $files){

	if($files !== ".." and $files !== "."){

	$name = str_replace(".yml", "", $files);

	if($name == "") continue;
	$data = new Config($this->getDataFolder()."Arenas/".$name.".yml",Config::YAML);
	if($data->get('status') == 'on'){
		$this->core->addBoss($player);
		$player->getInventory()->clearAll();
		$this->removerKit($player);
		$this->removeBossToPlayer($player,$name);
$player->teleport($this->getServer()->getDefaultLevel()->getSpawnLocation());
$name = $player->getName();
foreach($this->getServer()->getLevelByName($data->get('level'))->getPlayers() as $pl){
	$pla = $data->get('playersOnlineArena')-1;
	$pl->sendMessage($this->t."§4".$name." §esalio del juego §6(§c".$pla."§6/§c6§6)");
}

if($data->get('player1') == $player->getName()){
	$data->set('player1',null);
	$data->save();
}
if($data->get('player2') == $player->getName()){
	$data->set('player2',null);
	$data->save();
}
if($data->get('player3') == $player->getName()){
	$data->set('player3',null);
	$data->save();
}
if($data->get('player4') == $player->getName()){
	$data->set('player4',null);
	$data->save();
}
if($data->get('player5') == $player->getName()){
	$data->set('player5',null);
	$data->save();
}
if($data->get('player6') == $player->getName()){
	$data->set('player6',null);
	$data->save();
}
}
}
}
}
public function quitGameUi(Player $player){
	if(empty($this->getDataFolder()."Arenas/")) return;
	$scan = scandir($this->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
	$data = new Config($this->getDataFolder()."Arenas/".$name.".yml",Config::YAML);
	if($data->get('status') == 'off'){
		$this->core->addBoss($player);
		$player->getInventory()->clearAll();
		$this->removeBossToPlayer($player,$name);
$player->teleport($this->getServer()->getDefaultLevel()->getSpawnLocation());
$name = $player->getName();
$player->setGamemode(2);
foreach($this->getServer()->getLevelByName($data->get('level'))->getPlayers() as $pl){
	$pla = $data->get('playersOnlineArena')-1;
	$pl->sendMessage($this->t."§4".$name." §esalio del juego §6(§c".$pla."§6/§c6§6)");
}
}
}
}
}
public function Query(){
$datos = array(

            "type"    => "form",
            "title"   => "§l§dKIT SELECTOR",
            "content" => "",
            "buttons" => array()

        );

for($i = 0; $i<3; $i++){
	$name = array("§l§cCancelar","§l§aBuilder\n§r§eContiene 20 blocks","§l§aBrawler\n§r§eContiene Armor Basic");
	$datos["buttons"][] = array("text" => $name[$i]);
}

return $datos;
}

public function addForm(Player $player){
$accion = function($player,$data){
if($data == null){
	return;
}
	$name = array("§l§cCancelar","§l§aBuilder\n§r§eContiene 20 blocks","§l§aBrawler\n§r§eContiene Armor Basic");
switch ($name[$data]) {

	case $name[1]:
	$this->addPlayerKit($player,"builder");
	break;
	case $name[2]:
	$this->addPlayerKit($player,"armor");
	break;


}
};
$player->sendForm(new MenuForm($this->Query(),$accion));
}
public function reloadArenas(){
if(empty($this->getDataFolder()."Arenas/")) return;
	$scan = scandir($this->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
	$data = new Config($this->getDataFolder()."Arenas/".$name.".yml",Config::YAML);

	$this->reload($this->getServer()->getLevelByName($data->get('level')));
	$this->getServer()->getLogger()->info($this->t."Arenas resetadas");
}
}
}
public function reloadConfig(){
if(empty($this->getDataFolder()."Arenas/")) return;
	$scan = scandir($this->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
	$data = new Config($this->getDataFolder()."Arenas/".$name.".yml",Config::YAML);
$data->set('player1',null);
$data->set('player2',null);
$data->set('player3',null);
$data->set('player4',null);
$data->set('player5',null);
$data->set('player6',null);
$data->set('lobbytime',100);
$data->set('time',900);
$data->set('resetTime',25);
$data->set('status','on');
$data->save();
}
}
}
public function reloadConfigPRIV($name){
	$data = new Config($this->getDataFolder()."Arenas/".$name.".yml",Config::YAML);
$data->set('player1',null);
$data->set('player2',null);
$data->set('player3',null);
$data->set('player4',null);
$data->set('player5',null);
$data->set('player6',null);
$data->set('lobbytime',100);
$data->set('time',900);
$data->set('resetTime',25);
$data->set('status','on');
$data->save();
$this->getServer()->getLogger()->info('Config reload by :'.$name);
}
public function refillChests(Level $level)
	{
		$tiles = $level->getTiles();
		foreach($tiles as $t) {
			if($t instanceof Chest)
			{
				$chest = $t;
				$chest->getInventory()->clearAll();
				if($chest->getInventory() instanceof ChestInventory)
				{
					for($i=0;$i<=26;$i++)
					{
						$rand = rand(1,3);
						if($rand==1)
						{
$items = array("1,0,60","258,0,1","368,0,1","396,0,5");
							$k = array_rand($items);
							$v = $items[$k];
							$chest->getInventory()->setItem($i, Item::get($v));
						}
					}
				}
			}
		}
	}

        public function reload(Level $lev)
	{
                $name = $lev->getFolderName();
		if ($this->getServer()->isLevelLoaded($name))
			$this->getServer()->unloadLevel($this->getServer()->getLevelByName($name));
		if (!is_file($this->getDataFolder() . 'Mapas/' . $name . '.zip'))
			return false;
		$zip = new \ZipArchive;
		$zip->open($this->getDataFolder() . 'Mapas/' . $name . '.zip');
		$zip->extractTo($this->getServer()->getDataPath() . 'worlds');
		$zip->close();
		unset($zip);
		$this->getServer()->loadLevel($name);
		return true;

	}

public function countPlayersGM($game){
$data = new Config($this->getDataFolder()."Arenas/".$game.".yml",Config::YAML);

$i = 0;
foreach($this->getServer()->getLevelByName($data->get('level'))->getPlayers() as $pl){

if($pl->getGamemode() == 0){
	if($i < count($this->getServer()->getLevelByName($data->get('level'))->getPlayers())){
		$i++;
	}
}


}
return $i;

}
public function addEspectador(Player $player,$game){
	$data = new Config($this->getDataFolder()."Arenas/".$game.".yml",Config::YAML);
	$player->setGamemode(3);
	$pos = $data->get('espectador');
	$player->teleport(new Vector3($pos[0],$pos[1],$pos[2]));
	$player->sendMessage($this->t."§bEres Espectador");
		$player->getInventory()->clearAll();
		$accion = function($player,$data){
if($data == null)
{
	return;
}
$name = array("§l§aSPECTATE\n§r§eEspectear Arena","§l§aPlay Again\n§r§eBuscar nueva Partida!","§l§aBack To Lobby\n§r§eRegresar al lobby!");
switch ($name[$data]) {
	case $name[0]:
		break;
		case $name[1]:
		$this->system($player);
		break;
		case $name[2]:
		$this->quitGameUi($player);
		break;
}

		};

		$player->sendForm(new MenuForm($this->dataFormEspectador(),$accion));

}
public function dataFormEspectador(){
$datos = array(

            "type"    => "form",
            "title"   => "§l§6Game Selector",
            "content" => "",
            "buttons" => array()

        );

for($i = 0; $i<3; $i++){
	$name = array("§l§aSPECTATE\n§r§eEspectear Arena","§l§aPlay Again\n§r§eBuscar nueva Partida!","§l§aBack To Lobby\n§r§eRegresar al lobby!");
	$datos["buttons"][] = array("text" => $name[$i]);
}

return $datos;

}
public function addPlayerKit(Player $player,$type){
	$cfg = new Config($this->getDataFolder()."Kits.yml",Config::YAML);
	$name = $player->getName();
if($type == 'builder'){
	$player->sendMessage($this->t."§7Kit §bBuilder §7selecionado");
	$cfg->set($name,'builder');
	$cfg->save();
	}

if($type == 'armor'){
	$player->sendMessage($this->t."§7Kit §bArmor§7 selecionado");
	$cfg->set($name,'armor');
	$cfg->save();
}
}

public function addKit(Player $player){
		$cfg = new Config($this->getDataFolder()."Kits.yml",Config::YAML);
	$name = $player->getName();
if($cfg->get($name) == 'builder'){
$player->getInventory()->setItem(0,Item::get(43,4,20));
$cfg->set($name,null);
$cfg->save();
}
if($cfg->get($name) == 'armor'){
$player->getInventory()->setItem(0,Item::get(268,0,1));
$player->getInventory()->setItem(1,Item::get(270,0,1));
$player->getInventory()->setItem(2,Item::get(271,0,1));
$cfg->set($name,null);
$cfg->save();
}
}

public function removerKit(Player $player){
	$cfg = new Config($this->getDataFolder()."Kits.yml",Config::YAML);
	$name = $player->getName();
$cfg->set($name,null);
$cfg->save();
}
public function dimension(Player $p, Vector3 $pos, $level){
$p->teleport($this->getServer()->getLevelByName($level)->getSpawnLocation());
$p->teleport($pos);
}
public function addMessage(Player $player,$id){
$cfg = new Config($this->getDataFolder()."Arenas/lw-".$id.".yml",Config::YAML);
$arena = $cfg->get('nameArena');
$msg1 = "§a=================";
$msg2 = "     §l§aLucky§eWars§r     ";
$msg3 = "§bArena §8: §d".$arena;
$msg4 = "§6Selecciona tu Kit";
$msg5 = "§a=================";
$br = "\n";
$player->sendMessage($msg1.$br.$msg2.$br.$msg3.$br.$msg4.$br.$msg5);
}


public function createGanador(Player $player, Vector3 $pos, Level $level){
		$nbt = Entity::createBaseNBT($pos);
$nbt->setTag(clone $player->namedtag->getCompoundTag("Skin"));

$humano = new GanadorNPC($player->getLevel(), $nbt);
$humano->setNameTag("§l§aGanador §aLucky§eWars§r\n§61# §b".$player->getName());
$humano->setNametagVisible(true);
$humano->setNameTagAlwaysVisible(true);
$humano->spawnToAll();
$humano->setScale(1.5);

	}

public function spawnGanador(PLayer $ganador,$id){
$cfg = new Config($this->getDataFolder()."Arenas/".$id.".yml",Config::YAML);
$peda = $cfg->get('ganador');
$pos = new Vector3($peda[0],$peda[1],$peda[2]);
$level = $this->getServer()->getLevelByName($cfg->get('level'));
$this->createGanador($ganador,$pos,$level);
}



}
