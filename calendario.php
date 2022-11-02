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

    <div class="loading-msg-main" id="big-msg">
        <div class="loading-msg-centered">Loading...</div>
    </div>

    <div class="hero">
        <div><button class="b0" id="butt">></button>
            <button class="b1" id="bott">
                << /button>
        </div>
        <div id="calendar"></div>
    </div>

    <script>
        function trash_button_onClick(b) {
            const main = b.parentElement.getElementsByClassName("event-info")[0];
            const tittle = main.getElementsByClassName("event-title")[0];
            const desc = main.getElementsByClassName("event-desc")[0];
            const t = tittle.innerHTML.trim().split(/\s+/);
            const descA = desc.innerHTML.trim().split("|");
            const otherInf = descA[1].trim().split(/\s+/);

            getCookie("date", false, function(cookie) {
                $.ajax({
                    type: 'POST',
                    url: 'includes/button_functions.inc.php',
                    data: "action=removebooking&id=" + main.parentElement.getAttribute("data-event-index") + "&start=" + t[1] + "&end=" + t[3] + "&name=" + descA[0].trim().split(" ").join(" ") + "&class=" + otherInf[0] + "&grade=" + otherInf[1] + " " + otherInf[2] + " " + otherInf[3] + "&book=" + descA[2].trim().split(" ").join(" ") + "&date=" + cookie,
                    success: function(data, textStatus, jqXHR) {
                        location.reload();
                        console.log(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            })
        }
    </script>
    <script src="../js/evo-calendar.min.js"></script>
    <script>
        function capitalize(s) {
            return s[0].toUpperCase() + s.slice(1);
        }

        function run() {
            getCookie("election", false, function(cookie) {
                $.ajax({
                    type: 'POST',
                    url: 'includes/bookings.inc.php',
                    data: "action=getbookings",
                    success: function(data, textStatus, jqXHR) {
                        const jsondata = JSON.parse(data);
                        for (let i = 0; i <= jsondata.length - 1; i++) {
                            if (cookie == jsondata[i].book) {
                                $('#calendar').evoCalendar('addCalendarEvent', {
                                    id: jsondata[i]["id"],
                                    name: "De " + jsondata[i]["start"] + " hasta " + jsondata[i]["end"],
                                    description: jsondata[i].name + " | " + jsondata[i].class + " " +
                                        jsondata[i].grade + " | " + capitalize(jsondata[i].book),
                                    date: jsondata[i].date,
                                    showTrash: jsondata[i].name === document.getElementById("username").innerHTML ? true : false,
                                    type: 'event'
                                });
                            }
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            }, true);

            getCookie("date", false, function(cookie) {
                $('#calendar').evoCalendar('selectDate', cookie)
            }, true);

            $(document).ready(function() {
                $("#calendar").evoCalendar({});
                updateCButtons();
            });

            document.getElementById("big-msg").style.display = "none"
        }

        (async () => {
            console.log(Date.now());
            var date = false,
                election = false
            while (date !== false)
                getCookie("date", false, function(cookie) {
                    date = cookie
                })
            await new Promise(resolve => setTimeout(resolve, 1000));

            while (election !== false)
                getCookie("election", false, function(cookie) {
                    election = cookie
                })
            await new Promise(resolve => setTimeout(resolve, 1000));
            console.log(Date.now());
            run()
        })();
    </script>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h1 class="tittle">Nueva reserva: </h1>
            <label id="Asignatura">Asignatura:</label>
            <select id="asignaturas" name="asignaturas">
                <option value="None" disabled>-- Selecciona --</option>
                <?php
                require_once "includes/dbh.inc.php";
                require_once "includes/functions.inc.php";
                $classes = getClasses($conn, true);

                foreach ($classes as &$class) {
                    echo '<option value="' . $class["name"] . '" id="' . $class["name"] . '">' . $class["name"] . '</option>';
                }
                ?>
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
        window.mobileAndTabletCheck = function() {
            let check = false;
            (function(a) {
                if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true;
            })(navigator.userAgent || navigator.vendor || window.opera);
            return check;
        };

        function updateCButtons() {
            const display = window.innerWidth > 1096 ? "none" : "block";
            $('.b0').css({
                "display": display
            });
            $('.b1').css({
                "display": display
            });

            if (display === "none") {
                document.getElementById("butt").innerHTML = "&lt;";
                document.getElementById("bott").innerHTML = "&gt;";
                document.getElementsByClassName("calendar-events")[0].style.transform = "translateX(0)";
                document.getElementsByClassName("calendar-sidebar")[0].style.transform = "unset";
            } else {
                document.getElementById("butt").innerHTML = "&gt;";
                document.getElementById("bott").innerHTML = "&lt;";
                document.getElementsByClassName("calendar-sidebar")[0].style.transform = "translateX(-100%)";
                document.getElementsByClassName("calendar-events")[0].style.transform = "translateX(100%)";
            }
        }
        $(window).resize(function() {
            updateCButtons()
        });

        let eCookie = false
        let dCookie = false
        getCookie("election", false, function(cookie) {
            eCookie = cookie;
        })
        getCookie("date", false, function(cookie) {
            dCookie = cookie;
        })

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

        document.getElementById("butt").addEventListener("click", function() {
            if (this.innerHTML == "&gt;") {
                this.innerHTML = "&lt;"
                document.getElementsByClassName("calendar-sidebar")[0].style.transform = "unset";
            } else {
                this.innerHTML = "&gt;"
                document.getElementsByClassName("calendar-sidebar")[0].style.transform = "translateX(-100%)";
            }
        });
        document.getElementById("bott").addEventListener("click", function() {
            if (this.innerHTML == "&lt;") {
                this.innerHTML = "&gt;"
                document.getElementsByClassName("calendar-events")[0].style.transform = "translateX(0)";
            } else {
                this.innerHTML = "&lt;"
                document.getElementsByClassName("calendar-events")[0].style.transform = "translateX(100%)";
            }
        });
    </script>
</body>

</html>