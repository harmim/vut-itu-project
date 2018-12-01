<?php

declare(strict_types=1);

namespace App\TodoListModule\Controls\AddTodoListItem;

final class AddTodoListItemControl extends \ITU\Application\UI\BaseControl
{
	/**
	 * @var \App\TodoListModule\Model\TodoListItemService
	 */
	private $todoListItemService;

	/**
	 * @var \App\TodoListModule\Model\TodoListGlobalItemService
	 */
	private $todoListGlobalItemService;

	/**
	 * @var \Nette\Database\Table\ActiveRow|null
	 */
	private $todoList;


	public function __construct(
		\App\TodoListModule\Model\TodoListItemService $todoListItemService,
		\App\TodoListModule\Model\TodoListGlobalItemService $todoListGlobalItemService,
		?\Nette\Database\Table\ActiveRow $todoList = null
	) {
		parent::__construct();
		$this->todoListItemService = $todoListItemService;
		$this->todoListGlobalItemService = $todoListGlobalItemService;
		$this->todoList = $todoList;
	}


	protected function createComponentAddForm(): \Nette\Application\UI\Form
	{
		$form = new \Nette\Application\UI\Form();

		$nameLabel = 'Item name';
		$form->addText('name', null)
			->setAttribute('placeholder', $nameLabel)
			->setAttribute('aria-label', $nameLabel)
			->setRequired();

		$form->addSubmit('add', 'Add item');

		$form->onSuccess[] = [$this, 'onSuccessAddForm'];
		$form->onError[] = function (\Nette\Application\UI\Form $form): void {
			$presenter = $this->getPresenter();
			if ($presenter) {
				foreach ($form->getErrors() as $error) {
					$presenter->flashMessage($error, 'error');
				}
			}
		};

		return $form;
	}


	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function onSuccessAddForm(\Nette\Application\UI\Form $form, \Nette\Utils\ArrayHash $values): void
	{
		if ($this->todoList) {
			$success = $this->todoListItemService->add($values->name, (int) $this->todoList->id);
		} else {
			$success = $this->todoListGlobalItemService->add($values->name);
		}

		$presenter = $this->getPresenter();
		if ($presenter) {
			if ($success) {
				$presenter->flashMessage('Item has been successfully added.', 'success');
				$presenter->redirect('this');
			} else {
				$presenter->flashMessage('Addition of item failed.', 'error');
			}
		}
	}
}
