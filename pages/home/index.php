<!DOCTYPE html>
<html>
	<head>
		<title>Test home page</title>
	</head>
	<body>
		This was a triumph!
<?php
		_echo ('User::isAuthenticated()', User::isAuthenticated());
		_echo ('User::getCurrentUser()', User::getCurrentUser());
		_echo ('User::authenticate()', User::authenticate('1234567', ''));
		_echo ('User::isAuthenticated()', User::isAuthenticated());
		_echo ('User::getCurrentUser()', User::getCurrentUser());
?>
	</body>
</html>

<?php
function _echo($string, $val)
{
	echo '<p>'.$string.'</p>'.PHP_EOL;
	echo '<p>'; var_dump($val); echo '</p>'.PHP_EOL;
}
