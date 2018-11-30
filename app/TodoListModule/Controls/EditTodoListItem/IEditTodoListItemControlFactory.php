<?php

declare(strict_types=1);

namespace App\TodoListModule\Controls\EditTodoListItem;

interface IEditTodoListItemControlFactory
{
	function create(
		\Nette\Database\Table\ActiveRow $item
	): \App\TodoListModule\Controls\EditTodoListItem\EditTodoListItemControl;
}
