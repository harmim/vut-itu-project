<?php

declare(strict_types=1);

namespace App\TodoListModule\Presenters;

final class TodoListPresenter extends \App\CoreModule\Presenters\SecuredPresenter
{
	/**
	 * @var \App\TodoListModule\Controls\AddTodoList\IAddTodoListControlFactory
	 */
	private $todoListControlFactory;

	/**
	 * @var \App\TodoListModule\Controls\AssignedTodoLists\IAssignedTodoListsControlFactory
	 */
	private $assignedTodoListsControlFactory;


	public function __construct(
		\App\TodoListModule\Controls\AddTodoList\IAddTodoListControlFactory $todoListControlFactory,
		\App\TodoListModule\Controls\AssignedTodoLists\IAssignedTodoListsControlFactory $assignedTodoListsControlFactory
	) {
		parent::__construct();
		$this->todoListControlFactory = $todoListControlFactory;
		$this->assignedTodoListsControlFactory = $assignedTodoListsControlFactory;
	}


	public function actionDetail(int $id): void
	{
	}


	protected function createComponentAddTodoList(): \App\TodoListModule\Controls\AddTodoList\AddTodoListControl
	{
		return $this->todoListControlFactory->create();
	}


	protected function createComponentAssignedTodoLists(
	): \App\TodoListModule\Controls\AssignedTodoLists\AssignedTodoListsControl {
		return $this->assignedTodoListsControlFactory->create();
	}
}
