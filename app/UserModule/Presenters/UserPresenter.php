<?php

declare(strict_types=1);

namespace App\UserModule\Presenters;

final class UserPresenter extends \App\CoreModule\Presenters\SecuredPresenter
{
	/**
	 * @var \App\UserModule\Controls\UserList\IUserListControlFactory
	 */
	private $userListControlFactory;


	public function __construct(\App\UserModule\Controls\UserList\IUserListControlFactory $userListControlFactory)
	{
		parent::__construct();
		$this->userListControlFactory = $userListControlFactory;
	}


	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function actionList(): void
	{
		if (!$this->getUser()->isInRole(\App\UserModule\Model\UserService::ROLE_ADMIN)) {
			$this->flashMessage('Access denied.', 'error');
			$this->redirect(':Core:Homepage:default');
		}
	}


	protected function createComponentUserList(): \App\UserModule\Controls\UserList\UserListControl
	{
		return $this->userListControlFactory->create();
	}
}
