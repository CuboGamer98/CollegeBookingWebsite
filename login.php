<?php
if (isset($_POST["login"])) {
  $login = array(
    "user" => $_POST["nombre"],
    "password" => $_POST["password"]

  );

  if ($login["user"] === "admin" && $login["password"] === "admin") {
    $cookie_name = "login";
    $cookie_value = "logged";
    setcookie(
      $cookie_name,
      $cookie_value,
      time() + 86400,
      "/"
    );
    header("Location: index.html");
  } else {
    header("Location: login.php");
    return;
  }
}
?>

<!DOCTYPE html>
<html>
<title>Reserva Dispositivos Informaticos</title>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="assets/login.css" />
</head>

<body>
  <script>
    function writeCookie(name, val, days) {
      var expires = "";

      if (days) {
        var date = new Date();
        date.setTime(date.getTime + days * 24 * 60 * 60 * 1000);
        expires = "; expires=" + date.toGMTString();
      }

      document.cookie = name + "=" + val + expires + "; path=/";
    }

    writeCookie("login", "", -1);
  </script>

  <form action="login.php" method="post">
    <h1 class="title">Inicia sesión</h1>
    <div class="container-rows">
      <label id="nombre">Nombre: </label>
      <input type="text" id="nombreI" name="nombre" />
      <label id="password">Cotraseña: </label>
      <input type="password" id="passwordI" name="password" />
    </div>
    <h1 class="register-info">¿No tienes una cuenta aún? <a href="login.php">Click aquí para registrarte</a></h1>
    <input type="submit" value="Iniciar sesión" id="login" name="login" class="submit"/>
  </form>

</body>

</html>