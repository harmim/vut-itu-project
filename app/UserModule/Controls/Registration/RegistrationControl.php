<?php

declare(strict_types=1);

namespace App\UserModule\Controls\Registration;

final class RegistrationControl extends \ITU\Application\UI\BaseControl
{
	/**
	 * @var \App\UserModule\Model\UserService
	 */
	private $userService;


	public function __construct(\App\UserModule\Model\UserService $userService)
	{
		parent::__construct();
		$this->userService = $userService;
	}


	/**
	 * @throws \Nette\InvalidArgumentException
	 */
	protected function createComponentRegistrationForm(): \Nette\Application\UI\Form
	{
		$form = new \Nette\Application\UI\Form();

		$form->addText('email', 'E-mail')
			->setType('email')
			->setRequired()
			->addRule(\Nette\Forms\Form::EMAIL);

		$password = $form->addPassword('password', 'Password')
			->setRequired()
			->addRule(\Nette\Application\UI\Form::MIN_LENGTH, null, 6);

		$form->addPassword('passwordConfirmation', 'Confirm password')
			->addConditionOn($password, \Nette\Application\UI\Form::FILLED)
				->setRequired()
				->addRule(\Nette\Application\UI\Form::EQUAL, 'Passwords must match.', $password);

		$form->addSubmit('register', 'Register');

		$form->onSuccess[] = [$this, 'onSuccessRegistrationForm'];

		return $form;
	}


	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function onSuccessRegistrationForm(\Nette\Application\UI\Form $form, \Nette\Utils\ArrayHash $values): void
	{
		try {
			$this->userService->register($values->email, $values->password);
		} catch (\App\UserModule\Model\Exception $e) {
			$form->addError($e->getMessage());
			return;
		}

		$presenter = $this->getPresenter();
		if ($presenter) {
			$presenter->flashMessage('You have been successfully registered.', 'success');
			$presenter->redirect(':User:Sign:login');
		}
	}
}
