<?php

declare(strict_types=1);

namespace App\TodoListModule\Model;

final class TodoListItemService extends \ITU\Model\BaseService
{
	public static function getTableName(): string
	{
		return 'todo_list_item';
	}


	public function add(string $name, int $todoListId): bool
	{
		$this->getTable()->insert([
			'name' => $name,
			'todo_list_id' => $todoListId,
		]);

		return true;
	}


	/**
	 * @throws \Nette\InvalidArgumentException
	 */
	public function update(int $id, string $name): void
	{
		$this->getTable()->wherePrimary($id)->update([
			'name' => $name,
		]);
	}
}
