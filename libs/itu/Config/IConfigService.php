<?php

declare(strict_types=1);

namespace ITU\Config;

interface IConfigService
{
	function getConfig(): array;

	/**
	 * @return mixed
	 */
	function getConfigByKey(string ...$keys);
}
