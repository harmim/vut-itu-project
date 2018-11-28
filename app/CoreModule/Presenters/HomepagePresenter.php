<?php

declare(strict_types=1);

namespace App\CoreModule\Presenters;

final class HomepagePresenter extends \App\CoreModule\Presenters\SecuredPresenter
{
	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function actionDefault(): void
	{
		$this->redirect(':TodoList:TodoList:list');
	}
}
