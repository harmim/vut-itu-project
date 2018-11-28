<?php

declare(strict_types=1);

namespace App\CoreModule\Presenters;

abstract class SecuredPresenter extends \App\CoreModule\Presenters\BasePresenter
{
	/**
	 * @throws \Nette\Application\AbortException
	 */
	protected function startup(): void
	{
		parent::startup();
		$this->checkIsLoggedIn();
	}


	/**
	 * @throws \Nette\Application\AbortException
	 */
	protected function checkIsLoggedIn(): void
	{
		if (!$this->getUser()->isLoggedIn()) {
			if ($this->getUser()->getLogoutReason() === \Nette\Http\UserStorage::INACTIVITY) {
				$this->flashMessage(
					'You have been logged out due to prolonged inactivity. Please login again.',
					'info'
				);
			}

			$this->redirect(':User:Sign:login', ['login-backLink' => $this->storeRequest()]);
		}
	}
}
