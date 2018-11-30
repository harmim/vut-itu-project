<?php

declare(strict_types=1);

namespace App\TodoListModule\Controls\AssignedTodoLists;

final class AssignedTodoListsControl extends \ITU\Application\UI\BaseControl
{
	/**
	 * @var \App\TodoListModule\Model\TodoListService
	 */
	private $todoListService;

	/**
	 * @var \Nette\Security\User
	 */
	private $user;


	public function __construct(\App\TodoListModule\Model\TodoListService $todoListService, \Nette\Security\User $user)
	{
		parent::__construct();
		$this->todoListService = $todoListService;
		$this->user = $user;
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();

		$this->getTemplate()->add(
			'todoLists',
			$this->todoListService->fetchAll(function (\Nette\Database\Table\Selection $selection): void {
				$selection->where('user_id', $this->user->getId());
				$selection->order('id DESC');
			})
		);
		$this->getTemplate()->add(
			'todoListsStatuses',
			$this->todoListService->getTodoListsStatuses($this->user->getId())
		);
	}
}
