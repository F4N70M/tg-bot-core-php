<?php

namespace TgBotCore\Adapters\Telegram;

use TgBotCore\Contracts\iOutputAdapter;	// OutputAdapter interface
use TgBotCore\Contracts\iMessage;

/**
 * 
 */
class OutputAdapter implements iOutputAdapter
{
	protected $token;
	
	public function __construct(string $token) {
		$lvl = BotKernelDebug();
		$this->token = $token;
	}

	public function getRequestUrl($method) : string {
		$lvl = BotKernelDebug();
		return 'https://api.telegram.org/bot' . $this->token . '/' . $method;
	}

	public function sendMessage(iMessage $Message) {
		$lvl = BotKernelDebug();
		$url = $this->getRequestUrl('sendMessage');
		$data = $this->convertMessageToData($Message);

		return $this->curl($url, $data);
	}

	protected function convertMessageToData($Message) : array {
		$lvl = BotKernelDebug();
		$result = [
			"chat_id"	=> $Message->getChatPID(),
			"text"		=> $Message->getText()
		];
		return $result;
	}

	protected function curl(string $url, array $data) {
		$lvl = BotKernelDebug();
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE),
			// CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"))
		]);
		$result = curl_exec($curl);
		curl_close($curl);
		// echo "<pre>";
		// 	echo str_repeat("    ", $lvl);
		// 	print_r(json_decode($result, true));
		// echo "</pre>";
		return (($return = json_decode($result, true)) ? $return : $result);
	}
}