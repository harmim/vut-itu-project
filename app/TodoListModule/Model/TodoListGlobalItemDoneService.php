<?php

declare(strict_types=1);

namespace App\TodoListModule\Model;

final class TodoListGlobalItemDoneService extends \ITU\Model\BaseService
{
	/**
	 * @var \App\TodoListModule\Model\TodoListGlobalItemService
	 */
	private $todoListGlobalItemService;


	public function __construct(
		\Nette\Database\Context $database,
		\App\TodoListModule\Model\TodoListGlobalItemService $todoListGlobalItemService
	) {
		parent::__construct($database);
		$this->todoListGlobalItemService = $todoListGlobalItemService;
	}


	public function getTableName(): string
	{
		return 'todo_list_global_item_done';
	}


	public function createRecordsForNewTodoList(int $todoListId): bool
	{
		$globalItemDoneData = [];
		foreach ($this->todoListGlobalItemService->fetchAll() as $globalItem) {
			$globalItemDoneData[] = [
				'todo_list_global_item_id' => $globalItem->id,
				'todo_list_id' => $todoListId,
			];
		}

		if (!$this->getTable()->insert($globalItemDoneData)) {
			return false;
		}

		return true;
	}
}
