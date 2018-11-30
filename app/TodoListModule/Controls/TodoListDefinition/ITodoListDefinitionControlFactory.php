<?php

declare(strict_types=1);

namespace App\TodoListModule\Controls\TodoListDefinition;

interface ITodoListDefinitionControlFactory
{
	function create(): \App\TodoListModule\Controls\TodoListDefinition\TodoListDefinitionControl;
}
