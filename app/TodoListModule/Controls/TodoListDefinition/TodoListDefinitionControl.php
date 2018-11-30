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

		$presenter = $this->getPresenter();
		if ($presenter) {
			$presenter->flashMessage('Item has been successfully deleted.', 'success');
			$presenter->redirect('this');
		}
	}


	/**
	 * @throws \Nette\InvalidArgumentException
	 */
	public function handleSort(): void
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

			if (isset($data['itemId'])) {
				$this->todoListGlobalItemService->sort(
					(int) $data['itemId'],
					isset($data['prevItemId']) ? (int) $data['prevItemId'] : null,
					isset($data['nextItemId']) ? (int) $data['nextItemId'] : null
				);
			}
		}
	}
}
