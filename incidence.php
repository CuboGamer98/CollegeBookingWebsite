<!DOCTYPE html>
<html>
<title>Incidencias</title>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="assets/styles.css" />
  <link rel="stylesheet" href="assets/incidences.css" />
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
</head>

<body>
  <?php
  include_once "account.php";
  ?>
  <div class="centered-frame">
    <h3>Reportar incidencia</h3>
    <p>Escriba cual es el problema en esta caja de texto:</p>
    <textarea id="msg">Aquí se escribe, por si aún no se dio cuenta.</textarea>
    <button class="submit login" id="sendincidence">Enviar</button>
  </div>

  <script>
    $('button').click(e => {
      if (e.target.id === "sendincidence") {
        console.log("action=sendincidence&text=" + escape(document.getElementById("msg").innerHTML));
        $.ajax({
          type: 'POST',
          url: 'includes/button_functions.inc.php',
          data: "action=sendincidence&text=" + escape(document.getElementById("msg").innerHTML),
          success: function(data, textStatus, jqXHR) {
            //location.reload();
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
          }
        });
      }
    })
  </script>
</body>

</html>