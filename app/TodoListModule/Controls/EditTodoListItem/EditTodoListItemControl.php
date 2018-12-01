<?php

declare(strict_types=1);

namespace App\TodoListModule\Controls\EditTodoListItem;

final class EditTodoListItemControl extends \ITU\Application\UI\BaseControl
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
	 * @var \Nette\Database\Table\ActiveRow
	 */
	private $item;

	/**
	 * @var bool
	 */
	private $isGlobalItem;


	public function __construct(
		\App\TodoListModule\Model\TodoListGlobalItemService $todoListGlobalItemService,
		\App\TodoListModule\Model\TodoListItemService $todoListItemService,
		\Nette\Database\Table\ActiveRow $item
	) {
		parent::__construct();
		$this->todoListGlobalItemService = $todoListGlobalItemService;
		$this->todoListItemService = $todoListItemService;
		$this->item = $item;
		$this->isGlobalItem = !isset($item->todo_list_id);
	}


	/**
	 * @throws \Nette\Application\UI\InvalidLinkException
	 */
	protected function beforeRender(): void
	{
		parent::beforeRender();

		$this->getTemplate()->add('item', $this->item);

		$presenter = $this->getPresenter();
		$backLink = '#';
		if ($presenter) {
			if ($this->isGlobalItem) {
				$backLink = $presenter->link(':TodoList:TodoList:definition');
			} else {
				$backLink = $presenter->link(':TodoList:TodoList:detail', $this->item->todo_list_id);
			}
		}
		$this->getTemplate()->add('backLink', $backLink);
	}


	protected function createComponentEditForm(): \Nette\Application\UI\Form
	{
		$form = new \Nette\Application\UI\Form();

		$nameLabel = 'Item name';
		$form->addText('name', null)
			->setAttribute('placeholder', $nameLabel)
			->setAttribute('aria-label', $nameLabel)
			->setDefaultValue($this->item->name)
			->setRequired();

		$form->addSubmit('save', 'Save');

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
	 * @throws \Nette\Application\AbortException
	 * @throws \Nette\InvalidArgumentException
	 */
	public function onSuccessEditForm(\Nette\Application\UI\Form $form, \Nette\Utils\ArrayHash $values): void
	{
		if ($this->isGlobalItem) {
			$this->todoListGlobalItemService->update((int) $this->item->id, $values->name);
		} else {
			$this->todoListItemService->update((int) $this->item->id, $values->name);
		}

		$this->redirect('this');
	}
}
