<?php

declare(strict_types=1);

namespace App\TodoListModule\Controls\AddTodoList;

final class AddTodoListControl extends \ITU\Application\UI\BaseControl
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


	protected function createComponentAddForm(): \Nette\Application\UI\Form
	{
		$form = new \Nette\Application\UI\Form();

		$nameLabel = 'Student name';
		$form->addText('name', null)
			->setAttribute('placeholder', $nameLabel)
			->setAttribute('aria-label', $nameLabel)
			->setRequired();

		$form->addSubmit('assign', 'Assign student');

		$form->onSuccess[] = [$this, 'onSuccessAddForm'];
		$form->onError[] = function (\Nette\Application\UI\Form $form) {
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
		$success = $this->todoListService->create($values->name, $this->user->getId());

		$presenter = $this->getPresenter();
		if ($presenter) {
			if ($success) {
				$presenter->flashMessage('Student has been successfully assigned.', 'success');
			} else {
				$presenter->flashMessage('Assignment of student failed.', 'error');
			}
			$presenter->redirect('this');
		}
	}
}
