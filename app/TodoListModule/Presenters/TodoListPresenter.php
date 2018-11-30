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
	 * @var \App\TodoListModule\Controls\AddTodoList\IAddTodoListControlFactory
	 */
	private $todoListControlFactory;

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


	public function __construct(
		\App\TodoListModule\Controls\AddTodoList\IAddTodoListControlFactory $todoListControlFactory,
		\App\TodoListModule\Controls\AssignedTodoLists\IAssignedTodoListsControlFactory $assignedTodoListsControlFactory,
		\App\TodoListModule\Controls\AddTodoListItem\IAddTodoListItemControlFactory $addTodoListItemControlFactory,
		\App\TodoListModule\Controls\TodoListDefinition\ITodoListDefinitionControlFactory $todoListDefinitionControlFactory,
		\App\TodoListModule\Model\TodoListGlobalItemService $todoListGlobalItemService,
		\App\TodoListModule\Model\TodoListItemService $todoListItemService,
		\App\TodoListModule\Controls\EditTodoListItem\IEditTodoListItemControlFactory $editTodoListItemControlFactory
	) {
		parent::__construct();
		$this->todoListControlFactory = $todoListControlFactory;
		$this->assignedTodoListsControlFactory = $assignedTodoListsControlFactory;
		$this->addTodoListItemControlFactory = $addTodoListItemControlFactory;
		$this->todoListDefinitionControlFactory = $todoListDefinitionControlFactory;
		$this->todoListGlobalItemService = $todoListGlobalItemService;
		$this->todoListItemService = $todoListItemService;
		$this->editTodoListItemControlFactory = $editTodoListItemControlFactory;
	}


	public function actionDetail(int $id): void
	{
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
	}


	protected function createComponentAddTodoList(): \App\TodoListModule\Controls\AddTodoList\AddTodoListControl
	{
		return $this->todoListControlFactory->create();
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
}
