<!DOCTYPE html>
<html>
<title>Reserva Dispositivos Informaticos</title>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="assets/styles.css" />
    <link rel="stylesheet" href="assets/admin_panel.css" />
    <script src="https://code.jquery.com/jquery-3.4.0.js"></script>
</head>

<body>
    <?php
    include_once "account.php";
    ?>
    <div class="main-table">
        <div class="inside-main-table">
            <h2>Opciones</h2>
            <div class="options">
                <div class="option">
                    <p>Pueden registrarse nuevos usuarios</p>
                    <?php
                    require_once "includes/dbh.inc.php";
                    require_once "includes/functions.inc.php";

                    $checked = "";
                    if (canRegister($conn) === "true") {
                        $checked = "Checked";
                    }

                    echo '<input class="checkbox" type="checkbox" value="canRegister" ' . $checked . '>';
                    ?>
                </div>
                <div class="option">
                    <p>Email al cual enviar las incidencias</p>
                    <?php
                    require_once "includes/dbh.inc.php";
                    require_once "includes/functions.inc.php";

                    echo '<span role="textbox" class="email-text" id="email-text">' . getConfiguration($conn, "incidenceEmail") . '</span>';
                    ?>
                    <button id="edit-button"><img src="images/edit.svg" id="edit-button"></button>
                </div>
            </div>
            <h2>Cuentas activas</h2>
            <div class="sub-table">
                <div class="sub-table-scroll">
                    <table class="users">
                        <tr class="tr-sticky">
                            <th>Id de usuario</th>
                            <th>Nombre de usuario</th>
                            <th>Email de usuario</th>
                            <th>Es admin</th>
                            <th>Acciones</th>
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
            </div>
            <h2>Pendientes por revisión</h2>
            <div class="sub-table">
                <div class="sub-table-scroll">
                    <table class="pusers">
                        <tr class="tr-sticky">
                            <th>Id de usuario</th>
                            <th>Nombre de usuario</th>
                            <th>Email de usuario</th>
                            <th>Acciones</th>
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
            </div>
            <div class="tittle-bookings">
                <h2 class="text">Reservas</h2><button id="button-search"><img src="images/search.svg" id="button-search"></button>
                <div style="width: 0%;" id="search-div" data-search><input type="text" id="search"></input></div>
            </div>

            <?php
            require_once "includes/dbh.inc.php";
            require_once "includes/functions.inc.php";

            $bookingsbyyear = getBookingsByYear($conn);;
            foreach ($bookingsbyyear as $year => $bookings) {
                echo '<div class="arrow-down sub-table"><p>' . $year . '</p><button id="button-arrow-down"><img src="images/arrow.svg"></button><button id="button-trash"><img src="images/trash.svg" id="button-trash"></button></div>';
                echo '<div class="sub-table" style="display:none"><div class="sub-table-scroll"><table class="pusers"><tbody>';
                echo '<tr class="tr-sticky"><th>Id</th><th>Empieza a</th><th>Termina a</th><th>Profesor</th><th>Asignatura</th><th>Curso</th><th>Reserva</th><th>Dia</th><th>Acciones</th></tr>';
                foreach ($bookings as $booking) {
                    echo "<tr id='" . $booking["id"] . "'><th class='th-id' title='" . $booking["id"] . "'>" . $booking["id"] . "</th><th>" . $booking["start"] . "</th><th>" . $booking["end"] . "</th><th>" . $booking["name"] . "</th><th>" . $booking["class"] . "</th><th>" . $booking["grade"] . "</th><th>" . $booking["book"] . "</th><th>" . $booking["date"] . "</th><th><button name='accept' id='removebooking'>Eliminar</button></th></tr>";
                }
                echo '</tbody></table></div></div>';
            }
            ?>

            <div class="tittle-autobook">
                <h2 class="text">Reservas en masa</h2><button id="addautobook">Añadir reserva a un dia de la semana</button>
            </div>
            <div class="sub-table">
                <div class="sub-table-scroll">
                    <table class="users">
                        <tr class="tr-sticky">
                            <th>Día de la semana</th>
                            <th>Email de a quien reservar</th>
                            <th>Empiza a</th>
                            <th>Termina a</th>
                            <th>Reserva</th>
                            <th>Asignatura</th>
                            <th>Curso</th>
                            <th>Acciones</th>
                        </tr>
                        <tr id="addnewautobook" style="display: none;">
                            <td>
                                <select id="weekday" name="clase">
                                    <option value="None" disabled="">-- Selecciona --</option>
                                    <option value="None">Lunes</option>
                                    <option value="None">Martes</option>
                                    <option value="None">Miércoles</option>
                                    <option value="None">Jueves</option>
                                    <option value="None">Viernes</option>
                                    <option value="None">Sábado</option>
                                    <option value="None">Domingo</option>
                                </select>
                            </td>
                            <td>
                                <select id="email-select" name="clase">
                                    <option value="None" disabled="">-- Selecciona --</option>
                                    <?php
                                    require_once "includes/dbh.inc.php";
                                    require_once "includes/functions.inc.php";
                                    $users = getUsers($conn);

                                    foreach ($users as &$user) {
                                        echo '<option value="None">' . $user[2] . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td><input type="text" id="start-select" name="clase" class="text"></td>
                            <td><input type="text" id="end-select" name="clase" class="text"></td>
                            <td>
                                <select id="book-select" name="clase">
                                    <option value="None" disabled="">-- Selecciona --</option>
                                    <option value="None">Ordenador</option>
                                    <option value="None">Chromebook</option>
                                    <option value="None">Tablet Carro 1</option>
                                    <option value="None">Tablet Carro 2</option>
                                    <option value="None">Capilla</option>
                                    <option value="None">Biblioteca</option>
                                </select>
                            </td>
                            <td>
                                <select id="class-select" name="clase">
                                    <option value="None" disabled="">-- Selecciona --</option>
                                    <?php
                                    require_once "includes/dbh.inc.php";
                                    require_once "includes/functions.inc.php";
                                    $classes = getClasses($conn, true);

                                    foreach ($classes as &$class) {
                                        echo '<option value="'.$class["name"].'" id="'.$class["name"].'">'.$class["name"].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select id="grade-select" name="clase">
                                    <option value="None" disabled="">-- Selecciona --</option>
                                </select>
                            </td>
                            <th class="th">
                                <button name="Guardar" value="save" id="savenewautobook" class="interaction">Guardar</button>
                                <button name="Cancelar" value="cancel" id="cancelnewautobook" class="interaction">Cancelar</button>
                            </th>
                        </tr>
                        <?php
                        require_once "includes/dbh.inc.php";
                        require_once "includes/functions.inc.php";
                        $autobooks = getAutoBook($conn, true);

                        foreach ($autobooks as &$autobook) {
                            echo '<tr><th>' . $autobook["weekday"] . '</th><th>' . $autobook["email"] . '</th><th>' . $autobook["start"] . '</th><th>' . $autobook["end"] . '</th><th>' . $autobook["book"] . '</th><th>' . $autobook["class"] . '</th><th>' . $autobook["grade"] . '</th><th><button name="delete" id="deleteautobook">Eliminar</button></th></tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>
            <div style="margin-top:1vh; overflow: hidden;">
                <div class="options" style="float: right;">
                    <div class="option">
                        Agregar las reservas a
                        <select id="month" name="clase" class="month-select">
                            <option value="None" disabled="">-- Selecciona --</option>
                            <option value="None">Enero</option>
                            <option value="None">Febrero</option>
                            <option value="None">Marzo</option>
                            <option value="None">Abril</option>
                            <option value="None">Mayo</option>
                            <option value="None">Junio</option>
                            <option value="None">Julio</option>
                            <option value="None">Agosto</option>
                            <option value="None">Septiembre</option>
                            <option value="None">Octubre</option>
                            <option value="None">Noviembre</option>
                            <option value="None">Diciembre</option>
                        </select>
                        <select id="year" name="clase" class="year-select">
                            <option value="None" disabled="">-- Selecciona --</option>
                            <?php
                            $y = date("Y");
                            echo '<option value="None">' . $y . '</option>';
                            echo '<option value="None">' . ($y + 1) . '</option>';
                            echo '<option value="None">' . ($y + 2) . '</option>';
                            ?>
                        </select>
                        <button name="Hacer" value="save" id="makeautobook" class="interaction">Aceptar</button>
                    </div>
                </div>
            </div>
            <h2>Incidencias</h2>
            <div class="sub-table">
                <div class="sub-table-scroll">
                    <table class="users">
                        <tr class="tr-sticky">
                            <th>Id</th>
                            <th>Por</th>
                            <th>Hora</th>
                            <th>Dia</th>
                            <th>Enviado a</th>
                            <th>Mensaje</th>
                            <th>Estado</th>
                        </tr>
                        <?php
                        require_once "includes/dbh.inc.php";
                        require_once "includes/functions.inc.php";
                        $incidences = getIncidences($conn, true);

                        foreach ($incidences as &$incidence) {
                            echo '<tr><th class="th-id id-text" title="' . $incidence["id"] . '">' . $incidence["id"] . '</th><th>' . $incidence["by"] . '</th><th>' . $incidence["hour"] . '</th><th>' . $incidence["day"] . '</th><th>' . $incidence["sendto"] . '</th><th class="th-id" title="' . $incidence["msg"] . '">' . $incidence["msg"] . '</th><th><select id="year" name="clase" class="status-select">';
                            echo '<option value="None" disabled="">-- Selecciona --</option><option value="None" ' . ($incidence["status"] === "En espera" ? "selected" : "") . '>En espera</option><option value="None" ' . ($incidence["status"] === "En solución" ? "selected" : "") . '>En solución</option><option value="None" ' . ($incidence["status"] === "Resuelto" ? "selected" : "") . '>Resuelto</option></select></th>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>

            <h2>Customizaciones</h2>
            <div class="sub-table-two">
                <div class="sub-table">
                    <h2>Cosas a reservar</h2>
                    <div class="sub-table-scroll">
                        <table class="users">
                            <tr class="tr-sticky">
                                <th></th>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Acciones</th>
                                <th></th>
                            </tr>
                            <tr id="add-new-book-type-tr" style="display:none">
                                <th></th>
                                <th class="pick-file">
                                    <input type="file" class="new-book-img" id="new-book-img">
                                    <div class="fake-file-button">Seleccionar archivo</div>
                                </th>
                                <th><input type="text" class="new-book-text-input" id="new-book-text-input" maxlength="64"></th>
                                <th><button id="add-new-book-type-button">Agregar</button><button id="add-new-book-type-cancel">Cancelar</button></th>
                                <th></th>
                            </tr>
                            <tr id="add-new-book-type-tr2">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><button id="add-new-book-type">Agregar</button></th>
                                <th></th>
                            </tr>

                            <?php
                            /*require_once "includes/dbh.inc.php";
                        require_once "includes/functions.inc.php";
                        $autobooks = getClasses($conn, true);

                        foreach ($autobooks as &$autobook) {
                            echo '<tr>
                            <th></th>
                            <th><img src="'.''.'"></th>
                            <th>'.''.'</th>
                            <th><button id="remove-book-type">Eliminar</button></th>
                            <th></th>
                        </tr>';
                        }*/
                            ?>

                        </table>
                    </div>
                </div>

                <div class="sub-table">
                    <h2>Asignaturas</h2>
                    <div class="sub-table-scroll">
                        <table class="users">
                            <tr class="tr-sticky">
                                <th></th>
                                <th>Nombre</th>
                                <th>Acciones</th>
                                <th></th>
                            </tr>
                            <tr id="add-new-class-tr" style="display:none">
                                <th></th>
                                <th>
                                    <input type="text" class="new-class-text-input" id="new-class-text-input" maxlength="64">
                                </th>
                                <th><button id="add-new-class-button">Agregar</button><button id="add-new-class-cancel">Cancelar</button></th>
                                <th></th>
                            </tr>
                            <tr id="add-new-class-tr2">
                                <th></th>
                                <th></th>
                                <th><button id="add-new-class">Agregar</button></th>
                                <th></th>
                            </tr>
                            <?php
                            require_once "includes/dbh.inc.php";
                            require_once "includes/functions.inc.php";
                            $classes = getClasses($conn, true);

                            foreach ($classes as &$class) {
                                echo '<tr id="' . $class["name"] . '"><th></th><th>' . $class["name"] . '</th><th><button id="remove-class">Eliminar</button></th><th></th></tr>';
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="big-msg-amin" style="display:none" id="big-msg">
        <div class="big-msg-centered" id="big-msg-text"></div>
    </div>

    <script>
        function mobileAndTabletCheck() {
            let check = false;
            (function(a) {
                if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i
                    .test(a) ||
                    /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i
                    .test(a.substr(0, 4))) check = true;
            })(navigator.userAgent || navigator.vendor || window.opera);
            return check;
        };

        if (mobileAndTabletCheck() == true) {
            $('.main-table').css({
                "left": "0",
                "width": "min-content",
                "overflow-x": "scroll",
                "transform": "unset",
                "height": "100%"
            });
            $('#topbar').css({
                "float": "left"
            });
        }
        function addCurses(max, siglas) {
            const frameclases = document.getElementById("grade-select");
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

        function handleFileSelect() {
            if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
                alert('The File APIs are not fully supported in this browser.');
                return;
            }

            var input = document.getElementById('fileinput');
            if (!input) {
                alert("Um, couldn't find the fileinput element.");
            } else if (!input.files) {
                alert("This browser doesn't seem to support the `files` property of file inputs.");
            } else if (!input.files[0]) {
                alert("Please select a file before clicking 'Load'");
            } else {
                var file = input.files[0];
                var fr = new FileReader();
                fr.onload = receivedText;
                fr.readAsDataURL(file);
            }
        }

        $('button').click(e => {
            if (e.target.id === "addautobook") {
                document.getElementById("addnewautobook").style.display = "table-row";
                document.getElementById("addautobook").style.display = "none";
            } else if (e.target.id === "cancelnewautobook") {
                document.getElementById("addnewautobook").style.display = "none";
                document.getElementById("addautobook").style.display = "block";
            } else if (e.target.id === "savenewautobook") {
                $weekday = document.getElementById("weekday");
                $email = document.getElementById("email-select");
                $start = document.getElementById("start-select").value;
                $end = document.getElementById("end-select").value;
                $book = document.getElementById("book-select");
                $class = document.getElementById("class-select");
                $grade = document.getElementById("grade-select");

                //---------------------------------------------------

                $weekday = $weekday.options[$weekday.selectedIndex].innerHTML;
                $email = $email.options[$email.selectedIndex].innerHTML;
                $book = $book.options[$book.selectedIndex].innerHTML;
                $class = $class.options[$class.selectedIndex].innerHTML;
                $grade = $grade.options[$grade.selectedIndex].innerHTML;

                //--------------------------------------------------------------

                $.ajax({
                    type: 'POST',
                    url: 'includes/button_functions.inc.php',
                    data: "action=addautobook&email=" + $email + "&weekday=" + $weekday + "&start=" + $start + "&end=" + $end + "&class=" + $class + "&grade=" + $grade + "&book=" + $book,
                    success: function(data, textStatus, jqXHR) {
                        location.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            } else if (e.target.id === "deleteautobook") {
                const elem = e.target.parentElement.parentElement.getElementsByTagName("th");
                $weekday = elem[0].innerHTML;
                $email = elem[1].innerHTML;
                $start = elem[2].innerHTML
                $end = elem[3].innerHTML;
                $book = elem[4].innerHTML;
                $class = elem[5].innerHTML;
                $grade = elem[6].innerHTML

                $.ajax({
                    type: 'POST',
                    url: 'includes/button_functions.inc.php',
                    data: "action=deleteautobook&email=" + $email + "&weekday=" + $weekday + "&start=" + $start + "&end=" + $end + "&class=" + $class + "&grade=" + $grade + "&book=" + $book,
                    success: function(data, textStatus, jqXHR) {
                        location.reload();
                        console.log(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            } else if (e.target.id === "makeautobook") {
                $month = document.getElementById("month");
                $year = document.getElementById("year");
                $.ajax({
                    type: 'POST',
                    url: 'includes/button_functions.inc.php',
                    data: "action=makeautobook&month=" + $month.options[$month.selectedIndex].innerHTML + "&year=" + $year.options[$year.selectedIndex].innerHTML,
                    success: function(data, textStatus, jqXHR) {
                        location.reload();
                        console.log(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            } else if (e.target.id === "removebooking") {
                const c = e.target.parentElement.parentElement.children;
                $.ajax({
                    type: 'POST',
                    url: 'includes/button_functions.inc.php',
                    data: "action=removebooking&id=" + c[0].innerHTML + "&start=" + c[1].innerHTML + "&end=" + c[2].innerHTML + "&name=" + c[3].innerHTML + "&class=" + c[4].innerHTML + "&grade=" + c[5].innerHTML + "&book=" + c[6].innerHTML + "&date=" + c[7].innerHTML,
                    success: function(data, textStatus, jqXHR) {
                        location.reload();
                        console.log(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            } else if (e.target.id === "button-arrow-down") {
                if (e.target.value === "down") {
                    e.target.value = "up"
                    e.target.getElementsByTagName("img")[0].style = "transform: rotate(0deg)";
                    e.target.parentElement.nextElementSibling.style = "display:none";
                } else {
                    e.target.value = "down"
                    e.target.getElementsByTagName("img")[0].style = "transform: rotate(180deg)";
                    e.target.parentElement.nextElementSibling.style = "display:block";
                }
            } else if (e.target.id === "button-trash") {
                $.ajax({
                    type: 'POST',
                    url: 'includes/button_functions.inc.php',
                    data: "action=removeallbookingsfromyear&year=" + e.target.parentElement.getElementsByTagName("p")[0].innerHTML,
                    success: function(data, textStatus, jqXHR) {
                        location.reload();
                        console.log(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            } else if (e.target.id === "button-search") {
                const el = e.target.parentElement.parentElement.getElementsByTagName("div")[0]
                el.style.width = el.style.width === "100%" ? "0%" : "100%";
                //el.style.display = el.style.display === "block" ? "none" : "block";
            } else if (e.target.id === "edit-button") {
                const input = document.getElementById("email-text");
                if (input.hasAttribute("contentEditable") !== true) {
                    input.setAttribute("contentEditable", "true")
                    e.target.src = "images/check.svg";
                } else {
                    input.contentEditable = false;
                    input.removeAttribute("contentEditable")
                    e.target.src = "images/edit.svg";
                    $.ajax({
                        type: 'POST',
                        url: 'includes/button_functions.inc.php',
                        data: "action=changeincidentemail&newemail=" + input.innerHTML,
                        success: function(data, textStatus, jqXHR) {
                            location.reload();
                            console.log(data);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                }
            } else if (e.target.id === "add-new-book-type") {
                const tr = document.getElementById("add-new-book-type-tr");
                tr.style.display = "";
                e.target.parentElement.parentElement.style.display = "none";
            } else if (e.target.id === "add-new-class") {
                const tr = document.getElementById("add-new-class-tr");
                tr.style.display = "";
                e.target.parentElement.parentElement.style.display = "none";
            } else if (e.target.id === "add-new-book-type-button") {
                const elem = document.getElementById("new-book-text-input");
                const files = document.getElementById("new-book-img").files;
                if (files.length > 0) {
                    var form_data = new FormData();
                    form_data.append('file', files[0]);
                    form_data.append('name', elem.value);
                    form_data.append('action', "trytoadd-booktype");
                    $.ajax({
                        type: 'POST',
                        url: 'includes/booktype.inc.php',
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(data, textStatus, jqXHR) {
                            //location.reload();
                            console.log(data);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                }
            } else if (e.target.id === "add-new-class-button") {
                const elem = document.getElementById("new-class-text-input");
                $.ajax({
                    type: 'POST',
                    url: 'includes/button_functions.inc.php',
                    data: "action=addclass&name=" + elem.value,
                    success: function(data, textStatus, jqXHR) {
                        location.reload();
                        console.log(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            } else if (e.target.id === "add-new-book-type-cancel") {
                const tr = document.getElementById("add-new-book-type-tr2");
                tr.style.display = "";
                e.target.parentElement.parentElement.style.display = "none";
            } else if (e.target.id === "add-new-class-cancel") {
                const tr = document.getElementById("add-new-class-tr2");
                tr.style.display = "";
                e.target.parentElement.parentElement.style.display = "none";
            } else if (e.target.id === "remove-class") {
                $.ajax({
                    type: 'POST',
                    url: 'includes/button_functions.inc.php',
                    data: "action=removeclassfromlist&name=" + e.target.parentElement.parentElement.id,
                    success: function(data, textStatus, jqXHR) {
                        location.reload();
                        console.log(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'includes/button_functions.inc.php',
                    data: "action=" + e.target.value + "&email=" + e.target.parentNode.parentNode.getElementsByTagName("th")[2].innerHTML,
                    success: function(data, textStatus, jqXHR) {
                        location.reload();
                        console.log(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            }
        });

        $('.status-select').each(function() {
            this.addEventListener("change", e => {
                const id = e.target.parentElement.parentElement.getElementsByTagName("th")[0]
                $.ajax({
                    type: 'POST',
                    url: 'includes/button_functions.inc.php',
                    data: "action=updateincidencestatus&id=" + id.innerHTML + "&status=" + e.target.children[e.target.selectedIndex].innerHTML,
                    success: function(data, textStatus, jqXHR) {
                        location.reload();
                        console.log(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            });
        });

        $('.checkbox').each(function() {
            this.addEventListener("change", e => {
                if (this.parentElement.tagName.toLowerCase() == "th") {
                    $.ajax({
                        type: 'POST',
                        url: 'includes/button_functions.inc.php',
                        data: "action=" + e.target.value + "&email=" + e.target.parentNode
                            .parentNode.getElementsByTagName("th")[2].innerHTML + "&value=" + e
                            .target.checked,
                        success: function(data, textStatus, jqXHR) {
                            location.reload();
                            console.log(data);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                } else {
                    $.ajax({
                        type: 'POST',
                        url: 'includes/button_functions.inc.php',
                        data: "action=options&option=" + this.value + "&value=" + e.target
                            .checked,
                        success: function(data, textStatus, jqXHR) {
                            location.reload();
                            console.log(data);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                }
            });
        });

        var bookings = {};
        $.ajax({
            type: 'POST',
            url: 'includes/bookings.inc.php',
            data: "action=getbookings",
            success: function(data, textStatus, jqXHR) {
                bookings = JSON.parse(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });

        document.querySelector("[data-search]").addEventListener("input", e => {
            const val = e.target.value;
            console.log(val !== "")
            bookings.forEach(booking => {
                var acceptable = false;

                if (val !== "") {
                    for (const property in booking) {
                        if (booking[property].includes(val)) {
                            acceptable = true;
                            break;
                        }
                    }
                } else {
                    acceptable = true;
                }

                const tr = document.getElementById(booking["id"]);
                tr.style.display = acceptable ? "" : "none";
            })
        })


        $('button').click(e => {
            if (e.target.id === "sendincidence") {
                $.ajax({
                    type: 'POST',
                    url: 'includes/button_functions.inc.php',
                    data: "action=sendincidence&text=" + escape(document.getElementById("msg").value),
                    success: function(data, textStatus, jqXHR) {
                        console.log(data);
                        location.reload();
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