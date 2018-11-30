<?php

declare(strict_types=1);

namespace App\TodoListModule\Controls\AddTodoListItem;

interface IAddTodoListItemControlFactory
{
	function create(
		?\Nette\Database\Table\ActiveRow $todoList = null
	): \App\TodoListModule\Controls\AddTodoListItem\AddTodoListItemControl;
}
