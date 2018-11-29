<?php

declare(strict_types=1);

namespace App\TodoListModule\Model;

final class TodoListItemService extends \ITU\Model\BaseService
{
	public function getTableName(): string
	{
		return 'todo_list_item';
	}
}
