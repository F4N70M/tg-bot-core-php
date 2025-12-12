<?php

namespace TgBotCore;

use TgBotCore\Contracts\iUpdateHandler;
use TgBotCore\Contracts\iBotKernel;
use TgBotCore\Contracts\iUpdate;

use TgBotCore\Models\Message;

/**
 * 
 */
class UpdateHandler implements iUpdateHandler
{
	protected $Kernel;
	
	public function __construct(iBotKernel $Kernel) {
		$this->Kernel = $Kernel;
	}

	public function returnRawUpdateData() {
		$lvl = BotKernelDebug();
		$Update = $this->getUpdate();
		$rawData = $Update->getRawData();
		$Chat = $Update->getChat();
		$chatPID = $Chat->getPID();
		$Response = new Message();
		$Response->setText(print_r($rawData, true));
		$Response->setChatPID($chatPID);
		$result = $this->Kernel->sendMessage($Response);
		return $result;
	}

	public function getUpdate() : iUpdate {	// Получить объект Update из объекта iInputAdapter
		$lvl = BotKernelDebug();
		$rawData = json_decode(file_get_contents('php://input'), true);
		if (!is_array($rawData)) {
			$rawData = $this->getTestRawData();
			// throw new Exception("Нет данных обновления");
		}
		$Update = $this->Kernel->getInputAdapter()->convertToStandartUpdate($rawData);
		$Update->setDatabase($this->Kernel->getDatabase());
		return $Update;
	}

	private function getTestRawData() {
		$lvl = BotKernelDebug();
		return [
			"update_id" => 123456789,
			"message" => [
				"from" => [
					"id" => 440955330,
					"is_bot" => false,
					"first_name" => "Эд",
					"username" => "konard",
					"language_code" => "ru",
					"is_premium" => 1
				],
				"chat" => [
					"id" => 440955330,
					"first_name" => "Эд",
					"username" => "konard",
					"type" => "private"
				],
				"date" => 1764184940,
				"text" => "/start"
			]
		];
	}
}