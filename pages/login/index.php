<!DOCTYPE html>
<html>
  <head>
    <title>Login</title>
  </head>
  <body>
    <form method="post" action=".">
      <label>Username<input type="text" name="username" autofocus /></label>
      <label>Password<input type="password" name="password" /></label>
      <input type="submit" />
    </form>
    <?php if (User::isAuthenticated()) : ?>
    <p>You are logged in as <?php echo User::getCurrentUser()->getDisplayName(); ?>.</p>
    <form method="post" action=".">
      <input type="hidden" name="logout" value="" />
      <input type="submit" value="Logout" />
    </form>
    <?php else : ?>
    <p>You are not logged in</p>
    <?php endif; ?>
  </body>
</html>
