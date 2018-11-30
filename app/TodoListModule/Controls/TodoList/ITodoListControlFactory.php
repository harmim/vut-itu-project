<?php

declare(strict_types=1);

namespace App\TodoListModule\Controls\TodoList;

interface ITodoListControlFactory
{
	function create(
		\Nette\Database\Table\ActiveRow $todoList,
		bool $isGlobal = true
	): \App\TodoListModule\Controls\TodoList\TodoListControl;
}
