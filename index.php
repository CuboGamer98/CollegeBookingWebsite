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
      <button class="c-button" id="ordenador">
        <h3>Sala de informática</h3>
      </button>
      <button class="c-button" id="chromebook">
        <h3>Chromebooks</h3>
      </button>
      <button class="c-button" id="tablet carro 1">
        <h3>Tablets carro 1</h3>
      </button>
      <button class="c-button" id="tablet carro 2">
        <h3>Tablets carro 2</h3>
      </button>
      <button class="c-button" id="capilla">
        <h3>Capilla</h3>
      </button>
      <button class="c-button" id="biblioteca">
        <h3>Biblioteca</h3>
      </button>
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