<?php

declare(strict_types=1);

namespace App\TodoListModule\Controls\AssignedTodoLists;

interface IAssignedTodoListsControlFactory
{
	function create(): \App\TodoListModule\Controls\AssignedTodoLists\AssignedTodoListsControl;
}
