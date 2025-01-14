<?php

declare(strict_types=1);

namespace mineceit\commands\basic;

use mineceit\commands\MineceitCommand;
use mineceit\MineceitCore;
use mineceit\MineceitUtil;
use mineceit\player\language\Language;
use mineceit\player\MineceitPlayer;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\utils\TextFormat;

class StatsCommand extends MineceitCommand{

	public function __construct(){
		parent::__construct('stats', 'See stats', "Usage: /stats <player>", []);
		parent::setPermission('mineceit.permission.stats');
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param string[]      $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		$msg = null;

		$lang = MineceitCore::getPlayerHandler()->getLanguage();

		if($this->testPermission($sender) && $this->canUseCommand($sender)){

			$size = count($args);
			if($size <= 1){

				$target = $sender;

				if($sender instanceof MineceitPlayer)
					$lang = $sender->getLanguageInfo()->getLanguage();

				if($size === 1){
					$name = $args[0];

					$p = MineceitUtil::getPlayerExact($name, true);
					if($p !== null && $p instanceof MineceitPlayer)
						$target = $p;
					else $msg = $lang->generalMessage(Language::PLAYER_NOT_ONLINE, ["name" => $name]);
				}

				if($msg === null && $target instanceof MineceitPlayer){
					$statsArr = MineceitCore::getPlayerHandler()->listStats($target, $lang);
					$msg = implode("\n", $statsArr);
					$sender->sendMessage($msg);
					return true;
				}else{
					if($msg === null) $msg = $this->getUsage();
				}
			}else $msg = $this->getUsage();
		}

		if($msg !== null) $sender->sendMessage(MineceitUtil::getPrefix() . ' ' . TextFormat::RESET . $msg);

		return true;
	}
}
