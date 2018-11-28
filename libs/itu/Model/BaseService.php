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


	abstract public function getTableName(): string;


	public function getTable(): \Nette\Database\Table\Selection
	{
		return $this->database->table($this->getTableName());
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
}
