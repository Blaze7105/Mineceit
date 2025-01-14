<?php

declare(strict_types=1);

namespace mineceit\player\info\ips\tasks;

use mineceit\MineceitCore;
use mineceit\player\info\ips\IPInfo;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class AsyncLoadIPs extends AsyncTask{

	/** @var string */
	private $ipsFile;

	/** @var string */
	private $timezonesFile;

	/** @var string */
	private $aliasesFile;

	public function __construct(string $file, string $aliases, string $csvTimezones){
		$this->ipsFile = $file;
		$this->timezonesFile = $csvTimezones;
		$this->aliasesFile = $aliases;
	}

	/**
	 * Actions to execute when run
	 *
	 * @return void
	 */
	public function onRun(){

		// Create the aliases file.
		if(!file_exists($this->aliasesFile)){

			$file = fopen($this->aliasesFile, 'wb');
			fclose($file);
		}

		// Create the time zones file.
		if(!file_exists($this->timezonesFile)){

			$file = fopen($this->timezonesFile, 'wb');
			fclose($file);
		}

		// Create the ips file.
		if(!file_exists($this->ipsFile)){
			$file = fopen($this->ipsFile, 'wb');
			fclose($file);
		}

		// Open time zones file.
		$file = fopen($this->timezonesFile, 'r');
		$tzIps = [];
		while(($data = fgetcsv($file)) !== false){
			if(count($data) >= 2){
				$outputArr = ['tz' => $data[1], '24-hr' => $data[2]];
				$country = isset($data[3]) ? $data[3] : "";
				$outputArr['country'] = $country;
				$tzIps[$data[0]] = $outputArr;
			}
		}
		fclose($file);

		// Open the safe ips file.
		$safeIps = file_get_contents($this->ipsFile);

		$safeIps = array_diff(explode("\n", $safeIps), [""]);

		// Open the aliases file.

		$aliasIps = yaml_parse_file($this->aliasesFile) ?? [];

		$this->setResult(['safe-ips' => $safeIps, 'ipsInfo' => $tzIps, 'aliases' => $aliasIps]);
	}

	public function onCompletion(Server $server){
		$core = $server->getPluginManager()->getPlugin('Mineceit');
		$result = $this->getResult();
		if($core instanceof MineceitCore && $core->isEnabled()){
			$playerManager = MineceitCore::getPlayerHandler();
			$ipManager = $playerManager->getIPManager();
			if($result !== null){
				$safeIps = $result['safe-ips'];
				$aliases = $result['aliases'];
				$inputtedInfo = $result['ipsInfo'];
				$ipsInfo = [];
				foreach($inputtedInfo as $ip => $data){
					$outputInfo = IPInfo::loadInfo($ip, $data);
					if($outputInfo !== null){
						$ipsInfo[$ip] = IPInfo::loadInfo($ip, $data);
					}
				}
				$ipManager->loadIps($safeIps, $aliases, $ipsInfo);
			}
		}
	}
}
