<!DOCTYPE html>
<html>
	<head>
		<title>Test home page</title>
	</head>
	<body>
<?php
		$perm = 'home.test-permission';
		_echo ('User::declarePermission(\''.$perm.'\')', User::declarePermission($perm));
		
		_echo ('User::isAuthenticated()', User::isAuthenticated());
		_echo ('User::getCurrentUser()', User::getCurrentUser());
		_echo ('User::authenticate()', User::authenticate('1234567', ''));
		_echo ('User::isAuthenticated()', User::isAuthenticated());
		
		$user = User::getCurrentUser();
		_echo ('User::getCurrentUser()', $user);
		_echo ('$user->getUsername()', $user->getUsername());
		_echo ('$user->getDisplayName()', $user->getDisplayName());
		_echo ('$user->hasPermission(\''.$perm.'\')', $user->hasPermission($perm));
		_echo ('$user->logOut()', $user->logOut());
		
		_echo ('User::isAuthenticated()', User::isAuthenticated());
		_echo ('User::getCurrentUser()', User::getCurrentUser());
?>
		
		<style>
			p {
				display: block;
				width: 50%;
				float: left;
			}
		</style>
	</body>
</html>

<?php
function _echo($string, $val)
{
	echo '<p>'.$string.'</p>';
	echo '<p>'; var_dump($val); echo '</p>';
	echo '<hr/>';
}
