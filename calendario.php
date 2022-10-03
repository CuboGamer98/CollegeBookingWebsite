<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../assets/styles.css" />
    <link rel="stylesheet" href="../assets/calendario.css" />
    <link rel="stylesheet" href="../assets/evo-calendar.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet" />

    <script>
        function getCookie(cname) {
            let name = cname + "=";
            let ca = document.cookie.split(";");
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == " ") {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function capitalize(s) {
            return s[0].toUpperCase() + s.slice(1);
        }
        let cookie = getCookie("election");
        if (cookie != "") {
            cookie = capitalize(cookie);
        } else {
            cookie = "No elegido";
            window.location.href = "../index.php"
        }

        document.title = "Calendario: " + cookie;
    </script>
</head>

<body>
    <?php
    include_once "account.php";
    ?>

    <div class="hero">
        <div id="calendar"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
    <script src="../js/evo-calendar.min.js"></script>

    <script>
        function capitalize(s) {
            return s[0].toUpperCase() + s.slice(1);
        }

        $.ajax({
            type: 'POST',
            url: 'includes/bookings.inc.php',
            data: "action=getbookings",
            success: function(data, textStatus, jqXHR) {
                console.log(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });

        $(document).ready(function() {
            $("#calendar").evoCalendar({});

            fetch("./reservas.json")
                .then(response => {
                    return response.json();
                })
                .then(jsondata => {
                    for (let i = 0; i <= jsondata.length - 1; i++) {
                        $('#calendar').evoCalendar('addCalendarEvent', {
                            id: jsondata[i]["id"],
                            name: "De " + jsondata[i]["Hora_inicio"] + " Hasta " + jsondata[i][
                                "Hora_final"
                            ],
                            description: jsondata[i].Nombre + " | " + jsondata[i].Asignatura + " " +
                                jsondata[i].Clase + " | " + capitalize(jsondata[i].Elecion),
                            date: jsondata[i].Date,
                            type: 'event'
                        });
                    }
                });

            function getCookie(cname) {
                let name = cname + "=";
                let ca = document.cookie.split(";");
                for (let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == " ") {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            }

            const urlParams = new URLSearchParams(window.location.search);
            const back = urlParams.get('back')
            if (back == 1) {
                let cookie = getCookie("date");
                if (cookie != "") {
                    $('#calendar').evoCalendar('selectDate', cookie);
                }
            }
        });
    </script>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <h1 class="tittle">Nueva reserva: </h1>
            <span class="close">&times;</span>
            <label id="Asignatura">Asignatura:</label>
            <select id="asignaturas" name="asignaturas">
                <option value="None" disabled>-- Selecciona --</option>
            </select>
            <label id="clase">Clase:</label>
            <select id="clase-select" name="clase">
                <option value="None" disabled>-- Selecciona --</option>
            </select>
            <label id="hora.inicio">Hora de Inicio:</label>
            <input type="text" class="time-pickable" id="s.hora.inicio" value="08:00" readonly>
            <div class="time-picker">
            </div>
            <label id="hora.final">Hora Final:</label>
            <input type="text" class="time-pickable" id="s.hora.final" value="08:00" readonly>
            <div class="time-picker">
            </div>
            <p class="red">*Rellena todos los campos</p>
            <input type="submit" value="Reserva" id="submit" class="submit" />
            <p class="error"><?php echo @$error ?></p>
            <p class="succes"><?php echo @$succes ?></p>
        </div>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const back = urlParams.get('back')
        if (back == 1) {
            document.getElementById("myModal").style.display = "block"
        }
    </script>

    <script src="../js/timepickable.js"></script>
    <script>
        function getCookie(cname) {
            let name = cname + "=";
            let ca = document.cookie.split(";");
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == " ") {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        let eCookie = getCookie("election")
        let dCookie = getCookie("date")

        const frame = document.getElementById("asignaturas");
        fetch("../info.json").then(response => {
            return response.json();
        }).then(jsondata => {
            for (let i = 0; i <= jsondata.length; i++) {
                if (jsondata[i] != null) {
                    var option = document.createElement("option");
                    option.value = jsondata[i].name;
                    option.id = jsondata[i].name;
                    var text = document.createTextNode(option.value);
                    option.appendChild(text);
                    frame.appendChild(option);
                }
            }
        });

        function addCurses(max, siglas) {
            const frameclases = document.getElementById("clase-select");
            for (let i = 1; i <= max; i++) {
                for (let j = 1; j <= 2; j++) {
                    var c = "A"
                    if (j == 2) c = "B"
                    var option = document.createElement("option");
                    option.value = i.toString() + "\u00ba " + c + " " + siglas
                    option.id = i.toString() + "\u00ba " + c + " " + siglas
                    var text = document.createTextNode(option.value);
                    option.appendChild(text)
                    frameclases.appendChild(option)
                }
            }
        }
        addCurses(6, "EP")
        addCurses(4, "ESO")

        function updateButton() {
            var select = document.getElementById('s.hora.inicio');
            const {
                hour0,
                minute0
            } = getTimePartsFromPickable(select);

            var select2 = document.getElementById('s.hora.final');
            const {
                hour1,
                minute1
            } = getTimePartsFromPickable(select2);

            var button = document.getElementById('submit');
            var disableButton = false;
            if (hour0 === hour1 && minute0 === minute1) {
                disableButton = true;
            } else {
                if (hour0 === hour1 && minute0 > minute1) {
                    disableButton = true;
                } else if (hour0 > hour1) {
                    disableButton = true;
                }
            }
            button.disabled = disableButton;
        }

        document.getElementById("s.hora.inicio").onchange = updateButton
        document.getElementById("s.hora.final").onchange = updateButton
        updateButton()


        $('input[type="submit"]').click(e => {
            var a = document.getElementById("asignaturas");
            var c = document.getElementById("clase-select");
            var s = document.getElementById("s.hora.inicio");
            var e = document.getElementById("s.hora.final");
            a = a.options[a.selectedIndex].innerHTML;
            c = c.options[c.selectedIndex].innerHTML;
            s = s.options[s.selectedIndex].innerHTML;
            e = e.options[e.selectedIndex].innerHTML;
            var id = s + e + dCookie + eCookie;
            $.ajax({
                type: 'POST',
                url: 'includes/bookings.inc.php',
                data: "action=book&id=" + id + "&start=" + s + "&end=" + e + "&class=" + a + "&grade=" + c + "&book=" + eCookie + "&date=" + dCookie,
                success: function(data, textStatus, jqXHR) {
                    console.log(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                }
            });
        });
    </script>
</body>

</html>