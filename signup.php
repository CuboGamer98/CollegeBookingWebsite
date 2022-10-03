<?php
  include_once "header.php";
  session_start();
?>
  <div class="emailpending" id="emailpending">
      <h2 id="name"> ¡Muy bien! Estás pendiende para ser aceptado. Contacta al administrador para ser aceptado. </h2>
  </div>

  <form action="includes/signup.inc.php" method="post">
    <h1 class="title">Registrarse</h1>
    <div class="container-rows">
      <label id="name">Nombre: </label>
      <input type="text" id="nombreI" name="name" value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''?>"/>
      <label id="email">Correo: </label>
      <input type="text" id="correoI" name="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''?>"/>
      <label id="pwd">Cotraseña: </label>
      <input type="password" id="passwordI" name="pwd"/>
      <label id="pwdrepeat">Repite la cotraseña: </label>
      <input type="password" id="passwordIR" name="pwdrepeat"/>
    </div>
    <h1 class="register-error" id="register-error">Error</h1>
    <h1 class="register-info">¿Ya estás registrad@? <a href="login.php">Click aquí para inciar sesión</a></h1>
    <input type="submit" name="submit" class="submit signup"/>
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

    if (status !== "" && status !== "none" && status !== "emailstartedpending") {
      ind.style.display = "block";

      if (status == "emptyinput") {
        ind.innerHTML = "Rellena todos los recuadros.";
      } else if (status == "invalidname") {
        ind.innerHTML = "Nombre invalido (solo letras y espacios).";
      } else if (status == "invalidemail") {
        ind.innerHTML = "Email invalido.";
      } else if (status == "pwdnotmatch") {
        ind.innerHTML = "Las contraseñas no coinciden.";
      } else if (status == "emailinuse") {
        ind.innerHTML = "El nombre o el email introducido ya está en uso.";
      } else if (status == "emailpending") {
        ind.innerHTML = "El nombre o el email intruducido está pendiendete de aprobación. Contacta al administrador para más información.";
      } else {
        ind.innerHTML = "Algo fue mal... pero no sabes qué.";
      }
    } else {
      var indep = document.getElementById('emailpending');
      if (status === "emailstartedpending") {
        indep.style.display = "block";
      } else {
        indep.style.display = "none";
      }
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