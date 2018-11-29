<?php

declare(strict_types=1);

namespace App\TodoListModule\Model;

final class TodoListGlobalItemService extends \ITU\Model\BaseService
{
	public function getTableName(): string
	{
		return 'todo_list_global_item';
	}
}
