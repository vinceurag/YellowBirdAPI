<html>
  <head>
    <title>Login Sample</title>
  </head>

  <body>
    <form action="<?php echo base_url();?>api/auth" method="POST">
      <p>
        Username: <input type="text" name="username" />
      </p>
      <p>
        Password: <input type="password" name="password" />
      </p>
      <p>
        <input type="submit" name="brn_login" value="Login" />
      </p>
    </form>
  </body>
</html>
