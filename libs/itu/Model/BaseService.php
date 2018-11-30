<?php

declare(strict_types=1);

namespace ITU\Model;

abstract class BaseService
{
	use \Nette\SmartObject;

	/**
	 * @var \Nette\Database\Context
	 */
	protected $database;


	public function __construct(\Nette\Database\Context $database)
	{
		$this->database = $database;
	}


	abstract public static function getTableName(): string;


	public function getTable(): \Nette\Database\Table\Selection
	{
		return $this->database->table(static::getTableName());
	}


	public function selectionCallback(?callable $callback): \Nette\Database\Table\Selection
	{
		$selection = $this->getTable();
		if ($callback) {
			$callback($selection);
		}

		return $selection;
	}


	public function fetch(callable $callback = null): ?\Nette\Database\Table\ActiveRow
	{
		return $this->selectionCallback($callback)->fetch() ?: null;
	}


	public function fetchById(int $id): ?\Nette\Database\Table\ActiveRow
	{
		return $this->getTable()->get($id) ?: null;
	}


	/**
	 * @return \Nette\Database\Table\ActiveRow[]
	 */
	public function fetchAll(callable $callback = null): array
	{
		return $this->selectionCallback($callback)->fetchAll();
	}


	public function delete(int $id): void
	{
		$this->getTable()->wherePrimary($id)->delete();
	}


	public function getMaxPosition(): ?int
	{
		return $this->getTable()->max('position');
	}


	/**
	 * @throws \Nette\InvalidArgumentException
	 */
	public function sort(int $id, ?int $prevId = null, ?int $nextId = null): void
	{
		// Find out order of current item.
		$item = $this->fetchById($id);
		if (!$item) {
			return;
		}

		// Find all items that have to be moved one position up.
		$prevItem = $prevId ? $this->fetchById($prevId) : null;
		if ($prevItem) {
			$this->getTable()
				->where('position <= ?', $prevItem->position)
				->where('position > ?', $item->position)
				->update([
					'position-=' => 1,
				]);
		}
		// Find all items that have to be moved one position down.
		$nextItem = $nextId ? $this->fetchById($nextId) : null;
		if ($nextItem) {
			$this->getTable()
				->where('position >= ?', $nextItem->position)
				->where('position < ?', $item->position)
				->update([
					'position+=' => 1,
				]);
		}

		// Update current item position.
		if ($prevId) {
			$prevItem = $prevId ? $this->fetchById($prevId) : null;
			$position = $prevItem ? $prevItem->position + 1 : 0;
		} elseif ($nextId) {
			$nextItem = $nextId ? $this->fetchById($nextId) : null;
			$position = $nextItem ? $nextItem->position - 1 : 0;
		} else {
			$position = 1;
		}
		if ($position) {
			$item->update([
				'position' => $position,
			]);
		}
	}
}
