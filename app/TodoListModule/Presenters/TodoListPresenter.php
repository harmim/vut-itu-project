<?php

declare(strict_types=1);

namespace App\TodoListModule\Presenters;

final class TodoListPresenter extends \App\CoreModule\Presenters\SecuredPresenter
{
	/**
	 * @var \Nette\Database\Table\ActiveRow|null
	 */
	private $editedItem;

	/**
	 * @var \Nette\Database\Table\ActiveRow|null
	 */
	private $editedTodoList;

	/**
	 * @var \App\TodoListModule\Controls\AddTodoList\IAddTodoListControlFactory
	 */
	private $addTodoListControlFactory;

	/**
	 * @var \App\TodoListModule\Controls\AssignedTodoLists\IAssignedTodoListsControlFactory
	 */
	private $assignedTodoListsControlFactory;

	/**
	 * @var \App\TodoListModule\Controls\AddTodoListItem\IAddTodoListItemControlFactory
	 */
	private $addTodoListItemControlFactory;

	/**
	 * @var \App\TodoListModule\Controls\TodoListDefinition\ITodoListDefinitionControlFactory
	 */
	private $todoListDefinitionControlFactory;

	/**
	 * @var \App\TodoListModule\Model\TodoListGlobalItemService
	 */
	private $todoListGlobalItemService;

	/**
	 * @var \App\TodoListModule\Model\TodoListItemService
	 */
	private $todoListItemService;

	/**
	 * @var \App\TodoListModule\Controls\EditTodoListItem\IEditTodoListItemControlFactory
	 */
	private $editTodoListItemControlFactory;

	/**
	 * @var \App\TodoListModule\Model\TodoListService
	 */
	private $todoListService;

	/**
	 * @var \App\TodoListModule\Controls\EditTodoList\IEditTodoListControlFactory
	 */
	private $editTodoListControlFactory;

	/**
	 * @var \App\TodoListModule\Controls\TodoList\ITodoListControlFactory
	 */
	private $todoListControlFactory;


	public function __construct(
		\App\TodoListModule\Controls\AddTodoList\IAddTodoListControlFactory $addTodoListControlFactory,
		\App\TodoListModule\Controls\AssignedTodoLists\IAssignedTodoListsControlFactory $assignedTodoListsControlFactory,
		\App\TodoListModule\Controls\AddTodoListItem\IAddTodoListItemControlFactory $addTodoListItemControlFactory,
		\App\TodoListModule\Controls\TodoListDefinition\ITodoListDefinitionControlFactory $todoListDefinitionControlFactory,
		\App\TodoListModule\Model\TodoListGlobalItemService $todoListGlobalItemService,
		\App\TodoListModule\Model\TodoListItemService $todoListItemService,
		\App\TodoListModule\Controls\EditTodoListItem\IEditTodoListItemControlFactory $editTodoListItemControlFactory,
		\App\TodoListModule\Model\TodoListService $todoListService,
		\App\TodoListModule\Controls\EditTodoList\IEditTodoListControlFactory $editTodoListControlFactory,
		\App\TodoListModule\Controls\TodoList\ITodoListControlFactory $todoListControlFactory
	) {
		parent::__construct();
		$this->addTodoListControlFactory = $addTodoListControlFactory;
		$this->assignedTodoListsControlFactory = $assignedTodoListsControlFactory;
		$this->addTodoListItemControlFactory = $addTodoListItemControlFactory;
		$this->todoListDefinitionControlFactory = $todoListDefinitionControlFactory;
		$this->todoListGlobalItemService = $todoListGlobalItemService;
		$this->todoListItemService = $todoListItemService;
		$this->editTodoListItemControlFactory = $editTodoListItemControlFactory;
		$this->todoListService = $todoListService;
		$this->editTodoListControlFactory = $editTodoListControlFactory;
		$this->todoListControlFactory = $todoListControlFactory;
	}


	/**
	 * @throws \Nette\Application\BadRequestException
	 */
	public function actionDetail(int $id): void
	{
		$this->editedTodoList = $this->todoListService->fetchById($id);
		if (!$this->editedTodoList) {
			$this->error();
			return;
		}

		if ($this->editedTodoList->user_id !== $this->getUser()->getId()) {
			$this->error();
			return;
		}
	}


	public function renderDetail(): void
	{
		$this->getTemplate()->add('todoList', $this->editedTodoList);
	}


	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function actionDefinition(): void
	{
		if (!$this->getUser()->isInRole(\App\UserModule\Model\UserService::ROLE_ADMIN)) {
			$this->flashMessage('Access denied.', 'error');
			$this->redirect(':Core:Homepage:default');
		}
	}


	/**
	 * @throws \Nette\Application\AbortException
	 * @throws \Nette\Application\BadRequestException
	 */
	public function actionEditTodoListItem(int $id, bool $isGlobalItem = true): void
	{
		if ($isGlobalItem && !$this->getUser()->isInRole(\App\UserModule\Model\UserService::ROLE_ADMIN)) {
			$this->flashMessage('Access denied.', 'error');
			$this->redirect(':Core:Homepage:default');
			return;
		}

		if ($isGlobalItem) {
			$this->editedItem = $this->todoListGlobalItemService->fetchById($id);
		} else {
			$this->editedItem = $this->todoListItemService->fetchById($id);
		}

		if (!$this->editedItem) {
			$this->error();
			return;
		}

		if (!$isGlobalItem && $this->editedItem->todo_list->user_id !== $this->getUser()->getId()) {
			$this->error();
			return;
		}
	}


	protected function createComponentAddTodoList(): \App\TodoListModule\Controls\AddTodoList\AddTodoListControl
	{
		return $this->addTodoListControlFactory->create();
	}


	protected function createComponentAssignedTodoLists(
	): \App\TodoListModule\Controls\AssignedTodoLists\AssignedTodoListsControl {
		return $this->assignedTodoListsControlFactory->create();
	}


	protected function createComponentAddTodoListGlobalItem(
	): \App\TodoListModule\Controls\AddTodoListItem\AddTodoListItemControl {
		return $this->addTodoListItemControlFactory->create();
	}


	protected function createComponentTodoListDefinition(
	): \App\TodoListModule\Controls\TodoListDefinition\TodoListDefinitionControl {
		return $this->todoListDefinitionControlFactory->create();
	}


	protected function createComponentEditTodoListItem(
	): ?\App\TodoListModule\Controls\EditTodoListItem\EditTodoListItemControl {
		if (!$this->editedItem) {
			return null;
		}

		return $this->editTodoListItemControlFactory->create($this->editedItem);
	}


	protected function createComponentEditTodoList(): ?\App\TodoListModule\Controls\EditTodoList\EditTodoListControl
	{
		if (!$this->editedTodoList) {
			return null;
		}

		return $this->editTodoListControlFactory->create($this->editedTodoList);
	}


	protected function createComponentAddTodoListItem(
	): ?\App\TodoListModule\Controls\AddTodoListItem\AddTodoListItemControl {
		if (!$this->editedTodoList) {
			return null;
		}

		return $this->addTodoListItemControlFactory->create($this->editedTodoList);
	}


	protected function createComponentTodoListGlobal(): ?\App\TodoListModule\Controls\TodoList\TodoListControl
	{
		if (!$this->editedTodoList) {
			return null;
		}

		return $this->todoListControlFactory->create($this->editedTodoList);
	}


	protected function createComponentTodoList(): ?\App\TodoListModule\Controls\TodoList\TodoListControl
	{
		if (!$this->editedTodoList) {
			return null;
		}

		return $this->todoListControlFactory->create($this->editedTodoList, false);
	}
}
