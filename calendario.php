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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>

    <script src="/js/cookies.js"></script>
    <script>
        function capitalize(s) {
            return s[0].toUpperCase() + s.slice(1);
        }
        getCookie("election", false, function(cookie) {
            if (cookie != false) {
                cookie = capitalize(cookie);
            } else {
                cookie = "No elegido";
                window.location.href = "../index.php"
            }

            document.title = "Calendario: " + cookie;
        });
    </script>
</head>

<body>
    <?php
    include_once "account.php";
    ?>

    <div class="hero">
        <div id="calendar"></div>
    </div>

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
                const jsondata = JSON.parse(data);
                for (let i = 0; i <= jsondata.length - 1; i++) {
                    $('#calendar').evoCalendar('addCalendarEvent', {
                        id: jsondata[i]["id"],
                        name: "De " + jsondata[i]["start"] + " Hasta " + jsondata[i]["end"],
                        description: jsondata[i].name + " | " + jsondata[i].class + " " +
                            jsondata[i].grade + " | " + capitalize(jsondata[i].book),
                        date: jsondata[i].date,
                        type: 'event'
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
        $(document).ready(function() {
            $("#calendar").evoCalendar({});
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
        </div>
    </div>

    <div id="message" class="message">
        <p>Error</p>
    </div>

    <script>
        let eCookie = false
        let dCookie = false
        getCookie("election", false, function(cookie) {
            eCookie = cookie;
        })
        getCookie("date", false, function(cookie) {
            dCookie = cookie;
        })

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
            var s = document.getElementById("s.hora.inicio").value;
            var e = document.getElementById("s.hora.final").value;

            getCookie("date", false, function(date) {
                if (date !== false) {
                    setCookie("start", s);
                    setCookie("end", e);
                    setCookie("class", a.selectedIndex);
                    setCookie("grade", c.selectedIndex);

                    a = a.options[a.selectedIndex].innerHTML;
                    c = c.options[c.selectedIndex].innerHTML;
                    var id = s + e + date + eCookie;

                    $.ajax({
                        type: 'POST',
                        url: 'includes/bookings.inc.php',
                        data: "action=book&id=" + id + "&start=" + s + "&end=" + e + "&class=" + a + "&grade=" + c + "&book=" + eCookie + "&date=" + date,
                        success: function(data, textStatus, jqXHR) {
                            window.location.href = data;
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                }
            })
        });

        function getSelectsFromPicker(timePicker) {
            const [hour, minute] = timePicker.querySelectorAll(".time-picker__select");

            return {
                hour,
                minute
            };
        }

        function getTimeStringFromPicker(timePicker) {
            const selects = getSelectsFromPicker(timePicker);

            return `${selects.hour.value}:${selects.minute.value}`;
        }

        function numberToOption(number) {
            const padded = number.toString().padStart(2, "0");

            return `<option value="${padded}">${padded}</option>`;
        }

        const hourOptions = [8, 9, 10, 11, 12, 13, 14, 15, 16, 17].map(numberToOption);
        const minuteOptions = [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55].map(numberToOption);

        function getTimePartsFromPickable(timePickable) {
            const pattern = /^(\d+):(\d+)/;
            const [hour, minute] = Array.from(timePickable.value.match(pattern)).splice(1);

            return {
                hour,
                minute
            };
        }

        function updateBookButton() {
            var select = document.getElementById('s.hora.inicio');
            const time0 = getTimePartsFromPickable(select);

            var select2 = document.getElementById('s.hora.final');
            const time1 = getTimePartsFromPickable(select2);

            var button = document.getElementById('submit');
            var disableButton = false;
            if (time0.hour === time1.hour && time0.minute === time1.minute) {
                disableButton = true;
            } else {
                if (time0.hour === time1.hour && time0.minute > time1.minute) {
                    disableButton = true;
                } else if (time0.hour > time1.hour) {
                    disableButton = true;
                }
            }
            button.disabled = disableButton;
        }

        $(".time-picker").each(function() {
            const timePickable = this.previousSibling.previousSibling;
            this.classList.add("time-picker");
            this.innerHTML = `
        <select class="time-picker__select">
			${hourOptions.join("")}
		</select>
		:
		<select class="time-picker__select">
			${minuteOptions.join("")}
		</select>`;

            const selects = getSelectsFromPicker(this);
            selects.hour.addEventListener("change", e => {
                timePickable.value = getTimeStringFromPicker(this);
                updateBookButton();
            });
            selects.minute.addEventListener("change", e => {
                timePickable.value = getTimeStringFromPicker(this);
                updateBookButton();
            });

            if (timePickable.value) {
                const {
                    hour,
                    minute
                } = getTimePartsFromPickable(timePickable);

                selects.hour.value = hour;
                selects.minute.value = minute;
            }

            $(document).ready(function() {
                const urlParams = new URLSearchParams(window.location.search);
                const back = urlParams.get('error')
                if (back !== null) {
                    getCookie("date", false, function(cookie) {
                        if (cookie != false) {
                            $('#calendar').evoCalendar('selectDate', cookie);
                        }
                    })

                    const msg = document.getElementById("message");
                    msg.style.display = "block";
                    const p = msg.firstElementChild;
                    if (back == "bookingcompleted") {
                        p.innerHTML = "👌La reserva se hizo exitosamente."
                    } else {
                        p.innerHTML = "❌Error al tratar de hacer la reserva."
                        p.style.backgroundColor = "rgb(255, 143, 143)";

                        document.getElementById("myModal").style.display = "block"
                        getCookie("start", false, function(cookie) {
                            if (cookie != false) {
                                document.getElementById("s.hora.inicio").value = cookie;
                            }
                        })
                        getCookie("end", false, function(cookie) {
                            if (cookie != false) {
                                document.getElementById("s.hora.final").value = cookie;
                            }
                        })
                        getCookie("class", false, function(cookie) {
                            if (cookie != false) {
                                document.getElementById("asignaturas").selectedIndex = cookie;
                            }
                        })
                        getCookie("grade", false, function(cookie) {
                            if (cookie != false) {
                                document.getElementById("clase-select").selectedIndex = cookie;
                            }
                        })
                    }

                    function fadeOut() {
                        $(".fadeout").fadeToggle(500, "swing", function() {
                            this.remove();
                        });
                    }

                    setTimeout(function() {
                        $(".message").fadeToggle(500, "swing", function() {
                            this.remove();
                        });
                    }, 3000);
                }
            });
        });
    </script>
</body>

</html>