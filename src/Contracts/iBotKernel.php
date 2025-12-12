<?php

namespace TgBotCore\Contracts;

use TgBotCore\Contracts\iInputAdapter;
use TgBotCore\Contracts\iOutputAdapter;
use TgBotCore\Contracts\iDatabase;

interface iBotKernel {
	public function __construct(iInputAdapter $InputAdapter, iOutputAdapter $OutputAdapter, array $config);
	public function getInputAdapter() : iInputAdapter;
	public function getOutputAdapter() : iOutputAdapter;
	public function setDatabase(iDatabase $Database) : void;	// Получить объект Database
	public function getDatabase() : iDatabase;	// Получить объект Database
}