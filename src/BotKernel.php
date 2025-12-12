<?php

namespace TgBotCore;

use TgBotCore\Contracts\iBotKernel;
use TgBotCore\Contracts\iInputAdapter;
use TgBotCore\Contracts\iOutputAdapter;
use TgBotCore\Contracts\iMessage;
use TgBotCore\Contracts\iDatabase;

use TgBotCore\Models\Message;
use TgBotCore\Services\Database\Database;
use TgBotCore\Exception;


/**
 * 
 */
class BotKernel implements iBotKernel
{
	protected $config;
	protected $InputAdapter;
	protected $OutputAdapter;

	protected $Database;
	
	public function __construct(iInputAdapter $InputAdapter, iOutputAdapter $OutputAdapter, array $config) {
		$lvl = BotKernelDebug();

		$this->config = $config;
		
		$this->InputAdapter = $InputAdapter;
		$this->OutputAdapter = $OutputAdapter;
	}

	public function getInputAdapter() : iInputAdapter {
		return $this->InputAdapter;
	}

	public function getOutputAdapter() : iOutputAdapter {
		return $this->OutputAdapter;
	}

	public function sendMessage(iMessage $Message) {	// Отправить сообщение
		$lvl = BotKernelDebug();
		
		return $this->OutputAdapter->sendMessage($Message);
	}

	public function getDatabase() : iDatabase {	// Получить объект Database
		$lvl = BotKernelDebug();

		if ($this->Database === null) {
			$this->initDatabase();
		}

		return $this->Database;
	}

	public function setDatabase(iDatabase $Database) : void {
		$this->Database = $Database;
	}

	protected function initDatabase() : void {
		$lvl = BotKernelDebug();

		$credentials = $this->config["database"];
		if (
			!(
				isset($credentials["host"]) &&
				isset($credentials["user"]) &&
				isset($credentials["pass"]) &&
				isset($credentials["base"])
			)
		) {
			throw new Exception("Нет данных для подключения к базе данных");
		}

		$db = new Database();
		$db->setCredentials(
			$credentials["host"],
			$credentials["user"],
			$credentials["pass"],
			$credentials["base"]
		);

		$this->Database = $db;
	}
}