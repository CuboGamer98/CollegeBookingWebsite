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
        window.location.href = "../index.html"
    }

    document.title = "Calendario: " + cookie;
    </script>
</head>

<body>
    <script src="../check_login.js"></script>
    <div class="hero">
        <div id="calendar"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
    <script src="../js/evo-calendar.min.js"></script>

    <script>
    function capitalize(s) {
        return s[0].toUpperCase() + s.slice(1);
    }

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
            <form action="reserva.php" method="post" class="form">
                <h1 class="tittle">Nueva reserva: </h1>
                <span class="close">&times;</span>
                <div class="container-columns">
                    <div class="container-rows-f">
                        <label id="nombreP">Nombre del Profesor:</label>
                        <label id="Asignatura">Asignatura:</label>
                        <label id="clase">Clase:</label>
                        <label id="hora.inicio">Hora de Inicio:</label>
                        <label id="hora.final">Hora Final:</label>
                    </div>
                    <div class="container-rows-s">
                        <input type="text" id="nombreP" name="nombreP" />
                        <select id="asignaturas" name="asignaturas">
                            <option value="None" disabled>-- Selecciona --</option>
                        </select>
                        <select id="clase-select" name="clase">
                            <option value="None" disabled>-- Selecciona --</option>
                        </select>
                        <select id="s.hora.inicio" name="shorainicio">
                            <option value="None" disabled>-- Selecciona --</option>
                        </select>
                        <select id="s.hora.final" name="shorafinal">
                            <option value="None" disabled>-- Selecciona --</option>
                        </select>
                    </div>
                </div>
                <p class="red">*Rellena todos los campos</p>
                <input type="submit" value="Reserva" id="submit" name="btnenviar" class="submit" />
                <p class="error"><?php echo @$error ?></p>
                <p class="succes"><?php echo @$succes ?></p>
            </form>
        </div>
    </div>

    <script>
    const urlParams = new URLSearchParams(window.location.search);
    const back = urlParams.get('back')
    if (back == 1) {
        document.getElementById("myModal").style.display = "block"
    }
    </script>

    <script>
    function pad(num, size) {
        num = num.toString();
        while (num.length < size) num = "0" + num;
        return num;
    }

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

    function initHour(frameName) {
        const frame = document.getElementById(frameName);
        for (let i = 8; i < 18; i++) {
            for (let j = 0; j < 12; j++) {
                var option = document.createElement("option");
                option.value = pad(i, 2) + ":" + pad(j * 5, 2);
                option.id = pad(i, 2) + ":" + pad(j * 5, 2);
                var text = document.createTextNode(option.value);
                option.appendChild(text);
                frame.appendChild(option);
            }
        }
        var option = document.createElement("option");
        option.value = "18:00";
        var text = document.createTextNode(option.value);
        option.appendChild(text);
        frame.appendChild(option);
    }

    initHour("s.hora.inicio");
    initHour("s.hora.final");

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
        var value = select.options[select.selectedIndex].value;

        var select2 = document.getElementById('s.hora.final');
        var value2 = select2.options[select2.selectedIndex].value;

        var button = document.getElementById('submit');
        if (value == value2) {
            button.disabled = true;
            return;
        }
        button.disabled = false;
    }

    document.getElementById("s.hora.inicio").onchange = updateButton
    document.getElementById("s.hora.final").onchange = updateButton
    updateButton()
    </script>
</body>

</html>