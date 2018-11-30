<?php

declare(strict_types=1);

namespace App\TodoListModule\Model;

final class TodoListGlobalItemService extends \ITU\Model\BaseService
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
		return 'todo_list_global_item';
	}


	public function add(string $name): bool
	{
		$this->database->beginTransaction();

		$globalItem = $this->getTable()->insert([
			'name' => $name,
			'position' => $this->getMaxPosition() + 1,
		]);

		if (!$globalItem instanceof \Nette\Database\Table\ActiveRow) {
			$this->database->rollBack();
			return false;
		}

		if (!$this->todoListGlobalItemDoneService->createRecordForNewGlobalItem((int) $globalItem->id)) {
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
	 * @throws \Nette\InvalidArgumentException
	 */
	public function check(int $id, int $todoListId, bool $done): void
	{
		$this->todoListGlobalItemDoneService->check($id, $todoListId, $done);
	}
}
