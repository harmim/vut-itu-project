<?php

declare(strict_types=1);

namespace App\TodoListModule\Controls\TodoList;

final class TodoListControl extends \ITU\Application\UI\BaseControl
{
	/**
	 * @var \App\TodoListModule\Model\TodoListGlobalItemService
	 */
	private $todoListGlobalItemService;

	/**
	 * @var \App\TodoListModule\Model\TodoListItemService
	 */
	private $todoListItemService;

	/**
	 * @var \App\TodoListModule\Model\TodoListService
	 */
	private $todoListService;

	/**
	 * @var \Nette\Database\Table\ActiveRow
	 */
	private $todoList;

	/**
	 * @var bool
	 */
	private $isGlobal;


	public function __construct(
		\App\TodoListModule\Model\TodoListGlobalItemService $todoListGlobalItemService,
		\App\TodoListModule\Model\TodoListItemService $todoListItemService,
		\App\TodoListModule\Model\TodoListService $todoListService,
		\Nette\Database\Table\ActiveRow $todoList,
		bool $isGlobal = true
	) {
		parent::__construct();
		$this->todoListGlobalItemService = $todoListGlobalItemService;
		$this->todoListItemService = $todoListItemService;
		$this->todoListService = $todoListService;
		$this->todoList = $todoList;
		$this->isGlobal = $isGlobal;
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();

		$this->getTemplate()->add('isGlobal', $this->isGlobal);

		$todoListItems = $this->isGlobal
			? $this->todoListGlobalItemService->fetchAll(function (\Nette\Database\Table\Selection $selection): void {
				$selection->order('position');
			})
			: $this->todoListItemService->fetchAll(function (\Nette\Database\Table\Selection $selection): void {
				$selection->where('todo_list_id', $this->todoList->id);
				$selection->order('id');
			});
		$this->getTemplate()->add('todoListItems', $todoListItems);

		$this->getTemplate()->add(
			'todoListItemsDone',
			$this->todoListService->getTodoListItemsDone($todoListItems, $this->isGlobal, (int) $this->todoList->id)
		);
	}


	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function handleDelete(int $id): void
	{
		$presenter = $this->getPresenter();

		$item = $this->todoListItemService->fetchById($id);
		if (!$item) {
			$this->redirect('this');
			return;
		}

		if ($item->todo_list_id !== $this->todoList->id) {
			if ($presenter) {
				$presenter->flashMessage('Access denied.', 'error');
				$presenter->redirect('this');
			}
			return;
		}

		$this->todoListItemService->delete($id);
		if ($presenter) {
			$presenter->flashMessage('Item has been successfully deleted.', 'success');
			$presenter->redirect('this');
		}
	}


	/**
	 * @throws \Nette\InvalidArgumentException
	 */
	public function handleCheck(): void
	{
		$presenter = $this->getPresenter();
		if ($presenter && $presenter->isAjax()) {
			try {
				$data = \Nette\Utils\Json::decode(
					$presenter->getHttpRequest()->getRawBody(),
					\Nette\Utils\Json::FORCE_ARRAY
				);
			} catch (\Nette\Utils\JsonException $e) {
				$data = [];
			}

			if (isset($data['itemId'], $data['isGlobalItem'], $data['isChecked'])) {
				if ((bool) $data['isGlobalItem']) {
					$this->todoListGlobalItemService->check(
						(int) $data['itemId'],
						(int) $this->todoList->id,
						(bool) $data['isChecked']
					);
				} else {
					$this->todoListItemService->check((int) $data['itemId'], (bool) $data['isChecked']);
				}
			}
		}
	}
}
