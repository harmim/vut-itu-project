<?php

declare(strict_types=1);

namespace App\TodoListModule\Controls\EditTodoList;

interface IEditTodoListControlFactory
{
	function create(
		\Nette\Database\Table\ActiveRow $todoList
	): \App\TodoListModule\Controls\EditTodoList\EditTodoListControl;
}
