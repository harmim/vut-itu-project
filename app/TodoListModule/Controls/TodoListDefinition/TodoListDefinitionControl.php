<?php

declare(strict_types=1);

namespace App\TodoListModule\Controls\TodoListDefinition;

final class TodoListDefinitionControl extends \ITU\Application\UI\BaseControl
{
	/**
	 * @var \App\TodoListModule\Model\TodoListGlobalItemService
	 */
	private $todoListGlobalItemService;


	public function __construct(\App\TodoListModule\Model\TodoListGlobalItemService $todoListGlobalItemService)
	{
		parent::__construct();
		$this->todoListGlobalItemService = $todoListGlobalItemService;
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();

		$this->getTemplate()->add(
			'todoListItems',
			$this->todoListGlobalItemService->fetchAll(function (\Nette\Database\Table\Selection $selection): void {
				$selection->order('position');
			})
		);
	}


	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function handleDelete(int $id): void
	{
		$this->todoListGlobalItemService->delete($id);
		$this->redirect('this');
	}


	/**
	 * @throws \Nette\InvalidArgumentException
	 */
	public function handleSort(): void
	{
		$presenter = $this->getPresenter();
		if ($presenter && $presenter->isAjax()) {
			$parameters = $presenter->getParameters();
			if (isset($parameters['itemId'], $parameters['prevItemId'], $parameters['nextItemId'])) {
				$this->todoListGlobalItemService->sort(
					(int) $parameters['itemId'],
					$parameters['prevItemId'] ? (int) $parameters['prevItemId'] : null,
					$parameters['nextItemId'] ? (int) $parameters['nextItemId'] : null
				);
			}
		}
	}
}
