<!DOCTYPE html>
<html>
<title>Reserva Dispositivos Informaticos</title>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="assets/styles.css" />
  <link rel="stylesheet" href="assets/admin_panel.css" />
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
</head>

<body>
  <?php
  include_once "account.php";
  ?>
  <div class="main-table">
    <h2>Pendientes por revisión</h2>
    <div class="sub-table">
      <table class="pusers">
        <tr>
          <th>User id</th>
          <th>User name</th>
          <th>User email</th>
          <th>Actions</th>
        </tr>
        <?php
        require_once "includes/dbh.inc.php";
        require_once "includes/functions.inc.php";

        $pending_users = getPendingUsers($conn);
        foreach ($pending_users as &$user) {
          echo "<tr><th>" . $user[0] . "</th><th>" . $user[1] . "</th><th>" . $user[2] . "</th><th><button name='accept' value='accept'>Aceptar</button></th></tr>";
        }
        ?>
      </table>
    </div>
    <h2>Cuentas activas</h2>
    <div class="sub-table">
      <table class="users">
        <tr>
          <th>User id</th>
          <th>User name</th>
          <th>User email</th>
          <th>Is admin</th>
          <th>Actions</th>
        </tr>
        <?php
        require_once "includes/dbh.inc.php";
        require_once "includes/functions.inc.php";
        $users = getUsers($conn);

        foreach ($users as &$user) {
          $checked = "";
          if ($user[3] === 1) {
            $checked = "Checked";
          }

          $disabled = "";
          if ($_SESSION["useremail"] === $user[2]) {
            $disabled = "disabled = true";
          }

          echo "<tr><th>" . $user[0] . "</th><th>" . $user[1] . "</th><th>" . $user[2] . "</th><th><input class='checkbox' type='checkbox' value='admin' " . $disabled . " " . $checked . "></th><th><button name='delete' value='delete' " . $disabled . ">Eliminar</button></th></tr>";
        }
        ?>
      </table>
    </div>
    <script>
      $('button').click(e => {
        var ajaxurl = 'ajax.php',
          data = {
            'action': e.value,
            'email': e.target.parentNode.parentNode.getElementsByTagName("th")[2].innerHTML
          };
        $.post(ajaxurl, data, function(response) {
          //location.reload();
        });
      });

      $('.checkbox').each(function() {
        this.addEventListener("change", e => {
          var ajaxurl = 'ajax.php',
            data = {
              'action': e.value,
              'value': e.target.checked
            };
          $.post(ajaxurl, data, function(response) {
            location.reload();
          });
        });
      });
    </script>
</body>
</html>