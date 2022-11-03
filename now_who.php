<!DOCTYPE html>
<html>
<title>Reserva Dispositivos Informaticos</title>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="assets/styles.css" />
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
</head>

<body>
  <?php
  include_once "account.php";
  ?>
  <section class="s1">
    <h1 class="title">¿A quién le toca ahora?</h1>
    <h2 class="sub-title" id="clock">03/11/22 19:10</h2>
    <select id="book-types">
      <option value="None" disabled>-- Selecciona --</option>
      <?php
      require_once "includes/dbh.inc.php";
      require_once "includes/functions.inc.php";
      $booktypes = getBookTypes($conn, true);

      foreach ($booktypes as &$booktype) {
        echo '<option value="' . $booktype["name"] . '" id="' . $booktype["name"] . '" data-imagesrc="' . substr($booktype["img_name"], 3) . '">' . $booktype["name"] . '</option>';
      }
      ?>
    </select>
    <div class="now-info" id="found" style="display:none">
      <p id="name">Pablo</p>
      <p id="time">12:00 - 13:00</p>
      <p id="class">Biologia</p>
      <p id="grade">1ºA ESO</p>
    </div>
    <div class="now-info" id="clear" style="display:none">
      <p>Vacio</p>
      <img src="images/desert-rolling-bush.gif">
    </div>
  </section>

  <script>
    function update(e) {
      var index = e.target.selectedIndex;
      var inputText = e.target.children[index].innerHTML.trim();
      $.ajax({
        type: 'POST',
        url: 'includes/bookings.inc.php',
        data: "action=getbookingsnow&booktype=" + inputText,
        success: function(response, textStatus, jqXHR) {
          var a = [];
          if (response) {
            try {
              a = JSON.parse(response);
            } catch (e) {
              alert(e);
            }
          }

          var found = false;
          if (a.length !== 0) {
            found = true;
            document.getElementById("name").innerHTML = a["name"];
            document.getElementById("time").innerHTML = a["start"] + " - " + a["end"];
            document.getElementById("class").innerHTML = a["class"];
            document.getElementById("grade").innerHTML = a["grade"];
          }

          if (found === false) {
            document.getElementById("clear").style.display = "";
            document.getElementById("found").style.display = "none";
          } else {
            document.getElementById("found").style.display = "";
            document.getElementById("clear").style.display = "none";
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log(errorThrown);
        }
      });
    }

    const s = document.getElementById("book-types");
    s.addEventListener("change", update)
    if ("createEvent" in document) {
      var evt = document.createEvent("HTMLEvents");
      evt.initEvent("change", false, true);
      s.dispatchEvent(evt);
    } else {
      s.fireEvent("onchange");
    }

    function currentTime() {
      let date = new Date();
      let hh = date.getHours();
      let mm = date.getMinutes();
      let dd = date.getDay();
      let mo = date.getMonth();

      hh = (hh < 10) ? "0" + hh : hh;
      mm = (mm < 10) ? "0" + mm : mm;
      dd = (dd < 10) ? "0" + dd : dd;
      mo = (mo < 10) ? "0" + mo : mo;

      let time = dd + "/" + mo + "/" + date.getFullYear() + " " + hh + ":" + mm;

      document.getElementById("clock").innerText = time;
      var t = setTimeout(function() {
        currentTime()
      }, 1000);
    }
currentTime();
  </script>
</body>

</html>