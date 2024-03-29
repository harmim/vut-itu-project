<?php

declare(strict_types=1);

namespace App\TodoListModule\Model;

final class TodoListService extends \ITU\Model\BaseService
{
	/**
	 * @var \App\TodoListModule\Model\TodoListGlobalItemDoneService
	 */
	private $todoListGlobalItemDoneService;


	public function __construct(
		\Nette\Database\Context $database,
		\App\TodoListModule\Model\TodoListGlobalItemDoneService $todoListGlobalItemDoneService
	) {
		parent::__construct($database);
		$this->todoListGlobalItemDoneService = $todoListGlobalItemDoneService;
	}


	public static function getTableName(): string
	{
		return 'todo_list';
	}


	public function create(string $name, int $userId): bool
	{
		$this->database->beginTransaction();

		$todoList = $this->getTable()->insert([
			'name' => $name,
			'user_id' => $userId,
		]);
		if (!$todoList instanceof \Nette\Database\Table\ActiveRow) {
			$this->database->rollBack();
			return false;
		}

		if (!$this->todoListGlobalItemDoneService->createRecordsForNewTodoList((int) $todoList->id)) {
			$this->database->rollBack();
			return false;
		}

		$this->database->commit();
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


	/**
	 * @return array[$todoListId => $status]
	 */
	public function getTodoListsStatuses(int $userId): array
	{
		$todoListItemCount = $this->getTable()
			->select('
				todo_list.id AS id,
				COUNT(:todo_list_item.id) AS item_count,
				SUM(IF(:todo_list_item.done = 1, 1, 0)) AS item_done_count
			')
			->where('todo_list.user_id', $userId)
			->group('todo_list.id')
			->fetchAssoc('id');

		$todoListGlobalItemCount = $this->getTable()
			->select('
				todo_list.id AS id,
				COUNT(:todo_list_global_item_done.done) AS global_item_count,
				SUM(IF(:todo_list_global_item_done.done = 1, 1, 0)) AS global_item_done_count
			')
			->where('todo_list.user_id', $userId)
			->group('todo_list.id')
			->fetchAssoc('id');

		$statuses = [];
		foreach ($todoListItemCount as $id => ['item_count' => $itemCount, 'item_done_count' => $itemDoneCount]) {
			$statuses[$id] = (int) round(
				($itemDoneCount + $todoListGlobalItemCount[$id]['global_item_done_count'])
				/ ($itemCount + $todoListGlobalItemCount[$id]['global_item_count'])
				* 100
			);
		}

		return $statuses;
	}


	/**
	 * @param \Nette\Database\Table\ActiveRow[] $todoListItems
	 * @return array[$todoListItemId => $done]
	 */
	public function getTodoListItemsDone(array $todoListItems, bool $isGlobal, int $todoListId): array
	{
		if ($isGlobal) {
			$todoListGlobalItemsDone = $this->todoListGlobalItemDoneService->fetchAll(
				function (\Nette\Database\Table\Selection $selection) use ($todoListId): void {
					$selection->where('todo_list_id', $todoListId);
				}
			);
			$todoListItemsDone = \array_column($todoListGlobalItemsDone, 'done', 'todo_list_global_item_id');
		} else {
			$todoListItemsDone = \array_column($todoListItems, 'done', 'id');
		}

		return $todoListItemsDone;
	}
}
