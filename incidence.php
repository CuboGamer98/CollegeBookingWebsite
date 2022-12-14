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
  <div class="main-table">
    <div class="sub-table" style="background-color: unset">
      <h3>Reportar incidencia</h3>
      <p>Escriba cual es el problema en esta caja de texto:</p>
      <textarea id="msg" placeholder="Aquí se escribe."></textarea>
      <button class="submit login" id="sendincidence">Enviar</button>
    </div>

    <div class="sub-table">
      <div class="sub-table-scroll">
        <table class="users">
          <tr class="tr-sticky">
            <th>Id</th>
            <th>Por</th>
            <th>Hora</th>
            <th>Dia</th>
            <th>Mensaje</th>
            <th>Estado</th>
          </tr>
          <?php
          require_once "includes/dbh.inc.php";
          require_once "includes/functions.inc.php";
          $incidences = getIncidences($conn, true);
          $incidences = array_reverse($incidences);

          foreach ($incidences as &$incidence) {
            echo '<tr><th class="th-id" title="' . $incidence["id"] . '">' . $incidence["id"] . '</th><th>' . $incidence["by"] . '</th><th>' . $incidence["hour"] . '</th><th>' . $incidence["day"] . '</th><th class="th-id" id="th-id" title="' . $incidence["msg"] . '">' . $incidence["msg"] . '</th><th>' . $incidence["status"] . '</th></tr>';
          }
          ?>
        </table>
      </div>
    </div>
  </div>

  <div class="big-msg-amin" style="display:none" id="big-msg">
    <div class="big-msg-centered" id="big-msg-text"></div>
  </div>

  <script>
    $('button').click(e => {
      if (e.target.id === "sendincidence") {
        $.ajax({
          type: 'POST',
          url: 'includes/button_functions.inc.php',
          data: "action=sendincidence&text=" + escape(document.getElementById("msg").value),
          success: function(data, textStatus, jqXHR) {
            console.log(data);
            //location.reload();
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
          }
        });
      }
    })

    $('.th-id').click(e => {
      const d = document.getElementById("big-msg")
      d.style.display = d.style.display === "block" ? "none" : "block";
      const t = document.getElementById("big-msg-text")
      t.innerText = e.target.title;
    })

    $('#big-msg').click(e => {
      if (e.target.id !== "big-msg-text") {
        e.target.style.display = "none";
      }
    })
  </script>
</body>

</html>