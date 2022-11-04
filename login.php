<?php
  include_once "header.php";
?>
  <form action="includes/login.inc.php" method="post">
    <h1 class="title">Inicia sesión</h1>
    <div class="container-rows">
      <label id="name_email">Nombre / Correo: </label>
      <input type="text" id="nombreI" name="name_email" value="<?php echo isset($_SESSION['name_email']) ? $_SESSION['name_email'] : ''?>"/>
      <label id="pwd">Contraseña: </label>
      <input type="password" id="passwordI" name="pwd"/>
    </div>
    <h1 class="register-error", id="register-error">Error</h1>
    <h1 class="register-info">¿No tienes una cuenta aún? <a href="signup.php">Click aquí para registrarte</a></h1>
    <input type="submit" name="submit" class="submit login"/>
  </form>

<?php
  $result = "";
  if (isset($_GET["error"])) {
    $result = $_GET["error"];
  }
?>

<script type='text/javascript'> 
  var setStatus = function(status) {
    var ind = document.getElementById('register-error');

    if (status !== "" && status !== "none") {
      ind.style.display = "block";

      if (status == "wronglogin") {
        ind.innerHTML = "Los datos introducidos son incorrectos.";
      } else if (status == "emailpending") {
        ind.innerHTML = "El nombre o el email intruducido está pendiendete de aprobación. Contacta al administrador para más información.";
      } else {
        ind.innerHTML = "Algo fue mal... pero no sabes qué.";
      }
    } else {
      ind.style.display = "none";
    }
  }

  window.onload = function() {
      setStatus(<?php echo json_encode($result);?>);
  };
</script>

<?php
  include_once "footer.php"
?>