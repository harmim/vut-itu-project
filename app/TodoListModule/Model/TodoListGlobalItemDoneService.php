<?php

declare(strict_types=1);

namespace App\TodoListModule\Model;

final class TodoListGlobalItemDoneService extends \ITU\Model\BaseService
{
	public static function getTableName(): string
	{
		return 'todo_list_global_item_done';
	}


	public function createRecordsForNewTodoList(int $todoListId): bool
	{
		$globalItemDoneData = [];
		$globalItems = $this->database->table(\App\TodoListModule\Model\TodoListGlobalItemService::getTableName())
			->fetchAll();
		foreach ($globalItems as $globalItem) {
			$globalItemDoneData[] = [
				'todo_list_global_item_id' => $globalItem->id,
				'todo_list_id' => $todoListId,
			];
		}

		return (bool) $this->getTable()->insert($globalItemDoneData);
	}


	public function createRecordForNewGlobalItem(int $globalItemId): bool
	{
		$globalItemDoneData = [];
		$todoLists = $this->database->table(\App\TodoListModule\Model\TodoListService::getTableName())->fetchAll();
		foreach ($todoLists as $todoList) {
			$globalItemDoneData[] = [
				'todo_list_global_item_id' => $globalItemId,
				'todo_list_id' => $todoList->id,
			];
		}

		return (bool) $this->getTable()->insert($globalItemDoneData);
	}


	/**
	 * @throws \Nette\InvalidArgumentException
	 */
	public function check(int $id, int $todoListId, bool $done): void
	{
		$this->getTable()->where('todo_list_global_item_id', $id)->where('todo_list_id', $todoListId)->update([
			'done' => $done,
		]);
	}
}
