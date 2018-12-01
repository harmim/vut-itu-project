<?php

declare(strict_types=1);

namespace App\TodoListModule\Controls\EditTodoList;

final class EditTodoListControl extends \ITU\Application\UI\BaseControl
{
	/**
	 * @var \Nette\Security\User
	 */
	private $user;

	/**
	 * @var \App\TodoListModule\Model\TodoListService
	 */
	private $todoListService;

	/**
	 * @var \Nette\Database\Table\ActiveRow
	 */
	private $todoList;


	public function __construct(
		\Nette\Security\User $user,
		\App\TodoListModule\Model\TodoListService $todoListService,
		\Nette\Database\Table\ActiveRow $todoList
	) {
		parent::__construct();
		$this->user = $user;
		$this->todoListService = $todoListService;
		$this->todoList = $todoList;
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->getTemplate()->add('todoList', $this->todoList);
	}


	protected function createComponentEditForm(): \Nette\Application\UI\Form
	{
		$form = new \Nette\Application\UI\Form();

		$nameLabel = 'Student name';
		$form->addText('name', null)
			->setAttribute('placeholder', $nameLabel)
			->setAttribute('aria-label', $nameLabel)
			->setDefaultValue($this->todoList->name)
			->setRequired();

		$form->addSubmit('edit', 'Rename student');

		$form->onSuccess[] = [$this, 'onSuccessEditForm'];
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
	 * @throws \Nette\InvalidArgumentException
	 * @throws \Nette\Application\AbortException
	 */
	public function onSuccessEditForm(\Nette\Application\UI\Form $form, \Nette\Utils\ArrayHash $values): void
	{
		$this->todoListService->update((int) $this->todoList->id, $values->name);
		$this->redirect('this');
	}


	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function handleDelete(int $id): void
	{
		$presenter = $this->getPresenter();

		if ($this->todoList->user_id !== $this->user->getId()) {
			if ($presenter) {
				$presenter->flashMessage('Access denied.', 'error');
				$presenter->redirect('this');
			}
			return;
		}

		$this->todoListService->delete($id);

		if ($presenter) {
			$presenter->flashMessage('TODO list has been successfully deleted.', 'success');
			$presenter->redirect(':TodoList:TodoList:list');
		}
	}
}
