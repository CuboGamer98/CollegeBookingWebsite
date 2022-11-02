<!DOCTYPE html>
<html>
<title>Reserva Dispositivos Informaticos</title>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="assets/styles.css" />
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>

  <script src="/js/cookies.js"></script>
  <script>
    setCookie("election", "false");
  </script>
</head>

<body>
  <?php
  include_once "account.php";
  ?>
  <section class="s1">
    <h1 class="title">¿Qué desea reservar?</h1>
    <div class="container">
      <?php
      require_once "includes/dbh.inc.php";
      require_once "includes/functions.inc.php";
      $booktypes = getBookTypes($conn, true);

      foreach ($booktypes as &$booktype) {
        echo '<button class="c-button" id="' . strtolower($booktype["name"]) . '" style="background:url(' . $booktype["img_name"] . ') no-repeat"><h3>' . $booktype["name"] . '</h3></button>';
      }
      ?>
    </div>
  </section>

  <h2 id="credits">Por Héctor Sánchez y Miguel Peñaranda</h2>
  <script>
    function saveAndRedirect(cookieValue) {
      setCookie("election", cookieValue);
      window.location.href = "calendario.php";
    }

    let buttons = document.querySelectorAll(".container button");
    for (let button of buttons) {
      button.addEventListener(
        "click",
        function(event) {
          saveAndRedirect(button.id)
        },
        false
      );
    }
  </script>
</body>

</html>