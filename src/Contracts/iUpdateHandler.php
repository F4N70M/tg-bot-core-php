<?php

namespace TgBotCore\Contracts;

use TgBotCore\Contracts\iBotKernel;
use TgBotCore\Contracts\iUpdate;

interface iUpdateHandler {
	public function __construct(iBotKernel $Kernel);
	public function getUpdate() : iUpdate;	// Получить объект Update из объекта iInputAdapter
	public function returnRawUpdateData();	// Отправить обратно сырые данные обновления
}