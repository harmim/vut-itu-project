<?php

declare(strict_types=1);

namespace App\UserModule\Controls\UserList;

final class UserListControl extends \ITU\Application\UI\BaseControl
{
	/**
	 * @var \App\UserModule\Model\UserService
	 */
	private $userService;

	/**
	 * @var \Nette\Security\User
	 */
	private $user;


	public function __construct(\App\UserModule\Model\UserService $userService, \Nette\Security\User $user)
	{
		parent::__construct();
		$this->userService = $userService;
		$this->user = $user;
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();

		$users = $this->userService->fetchAll(function (\Nette\Database\Table\Selection $selection): void {
			$selection->order('id');
		});
		$this->getTemplate()->add('users', $users);
	}


	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function handleDelete(int $id): void
	{
		$this->checkPermission();
		$this->checkSelfModification($id);
		$this->userService->delete($id);
		$this->redirect('this');
	}


	/**
	 * @throws \Nette\Application\AbortException
	 * @throws \Nette\InvalidArgumentException
	 */
	public function handleChangeRole(int $id, string $role): void
	{
		$this->checkPermission();
		$this->checkSelfModification($id);
		try {
			$this->userService->changeRole($id, $role);
		} catch (\App\UserModule\Model\Exception $e) {
			$presenter = $this->getPresenter();
			if ($presenter) {
				$presenter->flashMessage($e->getMessage(), 'error');
			}
		}
		$this->redirect('this');
	}


	/**
	 * @throws \Nette\Application\AbortException
	 */
	private function checkPermission(): void
	{
		if (!$this->user->isInRole(\App\UserModule\Model\UserService::ROLE_ADMIN)) {
			$presenter = $this->getPresenter();
			if ($presenter) {
				$presenter->flashMessage('Access denied.', 'error');
				$presenter->redirect(':Core:Homepage:default');
			}
		}
	}


	/**
	 * @throws \Nette\Application\AbortException
	 */
	private function checkSelfModification(int $id): void
	{
		if ($id === $this->user->getId()) {
			$presenter = $this->getPresenter();
			if ($presenter) {
				$presenter->flashMessage('You can not modify yourself.', 'error');
				$presenter->redirect('this');
			}
		}
	}
}
