<?php

declare(strict_types=1);

namespace App\UserModule\Model;

final class UserService extends \ITU\Model\BaseService implements \Nette\Security\IAuthenticator
{
	public const ROLE_ADMIN = 'admin',
		ROLE_USER = 'user';

	public const ROLE_TRANSLATION_MAP = [
		self::ROLE_ADMIN => 'Administrator',
		self::ROLE_USER => 'User',
	];


	public static function getTableName(): string
	{
		return 'user';
	}


	public function authenticate(array $credentials): \Nette\Security\IIdentity
	{
		[self::USERNAME => $email, self::PASSWORD => $password] = $credentials;
		$user = $this->getTable()->where('email', $email)->fetch();

		$errorMessage = 'Invalid credentials.';
		if (!$user) {
			throw new \Nette\Security\AuthenticationException($errorMessage, self::IDENTITY_NOT_FOUND);

		} elseif (!\Nette\Security\Passwords::verify($password, $user->password)) {
			throw new \Nette\Security\AuthenticationException($errorMessage, self::INVALID_CREDENTIAL);

		} elseif (\Nette\Security\Passwords::needsRehash($user->password)) {
			$user->update([
				'password' => \Nette\Security\Passwords::hash($password),
			]);
		}

		$userData = $user->toArray();
		unset($userData['password']);

		return new \Nette\Security\Identity($user->id, [$user->role], $userData);
	}


	/**
	 * @throws \App\UserModule\Model\Exception
	 */
	public function register(string $email, string $password): void
	{
		try {
			$this->getTable()->insert([
				'email' => $email,
				'password' => \Nette\Security\Passwords::hash($password),
			]);
		} catch (\Nette\Database\UniqueConstraintViolationException $e) {
			throw new \App\UserModule\Model\Exception('User with this e-mail is already registered.');
		}
	}


	/**
	 * @throws \App\UserModule\Model\Exception
	 * @throws \Nette\InvalidArgumentException
	 */
	public function changeRole(int $id, string $role): void
	{
		if (!\array_key_exists($role, self::ROLE_TRANSLATION_MAP)) {
			throw new \App\UserModule\Model\Exception(\sprintf("Invalid role '%s'.", $role));
		}

		$this->getTable()->wherePrimary($id)->update([
			'role' => $role,
		]);
	}
}
