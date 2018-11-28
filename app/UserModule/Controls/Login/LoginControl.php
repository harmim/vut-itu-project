<?php

declare(strict_types=1);

namespace App\UserModule\Controls\Login;

final class LoginControl extends \ITU\Application\UI\BaseControl
{
	/**
	 * @persistent
	 * @var string
	 */
	public $backLink = '';

	/**
	 * @var \Nette\Security\User
	 */
	private $user;


	public function __construct(\Nette\Security\User $user)
	{
		parent::__construct();
		$this->user = $user;
	}


	protected function createComponentLoginForm(): \Nette\Application\UI\Form
	{
		$form = new \Nette\Application\UI\Form();

		$form->addText('email', 'E-mail')
			->setType('email')
			->setRequired()
			->addRule(\Nette\Forms\Form::EMAIL);

		$form->addPassword('password', 'Password')
			->setRequired();

		$form->addCheckbox('remember', 'Remember me');

		$form->addSubmit('login', 'Log in');

		$form->onSuccess[] = [$this, 'onSuccessLoginForm'];

		return $form;
	}


	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function onSuccessLoginForm(\Nette\Application\UI\Form $form, \Nette\Utils\ArrayHash $values): void
	{
		try {
			$this->user->setExpiration(
				$values->remember ? '14 days' : '30 minutes',
				\Nette\Security\IUserStorage::CLEAR_IDENTITY
			);
			$this->user->login($values->email, $values->password);
		} catch (\Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
			return;
		}

		$presenter = $this->getPresenter();
		if ($presenter) {
			$presenter->flashMessage('You have been successfully logged in.', 'success');
			if ($this->backLink) {
				$presenter->restoreRequest($this->backLink);
			}
			$presenter->redirect(':Core:Homepage:default');
		}
	}
}
