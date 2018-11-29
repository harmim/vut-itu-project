<?php

declare(strict_types=1);

namespace App\TodoListModule\Controls\AddTodoList;

interface IAddTodoListControlFactory
{
	function create(): \App\TodoListModule\Controls\AddTodoList\AddTodoListControl;
}
