<?php

declare(strict_types=1);

namespace App\UserModule\Controls\UserList;

interface IUserListControlFactory
{
	function create(): \App\UserModule\Controls\UserList\UserListControl;
}
