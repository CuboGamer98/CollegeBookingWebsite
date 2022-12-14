<?php

function emptyInputSignup($name, $email, $pwd, $pwdrepeat) {
    return (empty($name) || empty($email) || empty($pwd) || empty($pwdrepeat));
}

function invalidName($name) {
    return (!preg_match('/^[A-zÀ-ú\s]+$/', $name));
}

function invalidEmail($email) {
    return (!filter_var($email, FILTER_VALIDATE_EMAIL) || substr(strrchr($email, "@"), 1) != "alexia.cnsfatima.es");
}

function pwdMatch($pwd, $pwdrepeat) {
    return ($pwd == $pwdrepeat);
}

function emailExists($conn, $name, $email, $db = "users") {
    $sql = "SELECT * FROM " . $db . " WHERE usersName = ? OR usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $name, $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }

    return false;

    mysqli_stmt_close($stmt);
}

function emailExistsPending($conn, $anme, $email) {
    return emailExists($conn, $anme, $email, "pending_users");
}

function createPendingUser($conn, $name, $email, $pwd) {
    $sql = "INSERT INTO pending_users (usersName, usersEmail, usersPwd) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./signup.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ./signup.php?error=emailstartedpending");
    exit();
}

function emptyInputLogin($name_email, $pwd) {
    return (empty($name_email) || empty($pwd));
}

function loginUser($conn, $name_email, $pwd) {
    $emailExists = emailExists($conn, $name_email, $name_email);

    if ($emailExists === false) {
        $emailExistsP = emailExistsPending($conn, $name_email, $name_email);
        if ($emailExistsP === false) {
            header("location: ./login.php?error=wronglogin");
        } else {
            header("location: ./login.php?error=emailpending");
        }
        exit();
    }

    $pwdHashed = $emailExists["usersPwd"];
    $checkPwd = password_verify($pwd, $pwdHashed);

    if ($checkPwd === true) {
        session_start();
        $_SESSION["userid"] = $emailExists["usersId"];
        $_SESSION["username"] = $emailExists["usersName"];
        $_SESSION["useremail"] = $emailExists["usersEmail"];
        header("location: ./index.php");
        exit();
    }
    header("location: ./login.php?error=passwordwrong");
    exit();
}

function getIsAdmin($conn, $email) {
    $emailExists = emailExists($conn, "", $email);

    if ($emailExists === false) {
        return false;
    }

    return $emailExists["isAdmin"] === 1;
}

function getDataFromTable($conn, $table) {
    $sql = "SELECT * FROM " . $table;
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./index.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    if ($result === false) {
        header("location: ./index.php?error=noresult");
        exit();
    }

    return $result;
}

function getPendingUsers($conn) {
    $result = getDataFromTable($conn, "pending_users");
    $return = array();
    while ($row = $result->fetch_row()) {
        array_push($return, array($row[0], $row[1], $row[2]));
    }

    return $return;
}

function getUsers($conn) {
    $result = getDataFromTable($conn, "users");
    $return = array();
    while ($row = $result->fetch_row()) {
        $state = false;
        if ($row[4] === 1) {
            $state = true;
        }
        array_push($return, array($row[0], $row[1], $row[2], $row[4]));
    }

    return $return;
}

function acceptUser($conn, $email) {
    $result = emailExistsPending($conn, "", $email);
    if ($result === false) {
        header("location: ./admin_panel.php?error=userdonesntexists");
        exit();
    }

    $sql = "INSERT INTO users (usersName, usersEmail, usersPwd) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./admin_panel.php?error=erroraccepting");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $result["usersName"], $result["usersEmail"], $result["usersPwd"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $sql = "DELETE FROM pending_users WHERE usersEmail=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./admin_panel.php?error=erroraccepting");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ./signup.php?error=useraccepted");
    exit();
}

function deleteUser($conn, $email) {
    $result = emailExists($conn, "", $email);
    if ($result === false) {
        header("location: ./admin_panel.php?error=userdonesntexists");
        exit();
    }

    $sql = "DELETE FROM users WHERE usersEmail=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./admin_panel.php?error=erroraccepting");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ./signup.php?error=userdeleted");
    exit();
}

function setAdmin($conn, $email, $value) {
    $result = emailExists($conn, "", $email);
    if ($result === false) {
        header("location: ./admin_panel.php?error=userdonesntexists");
        exit();
    }

    $sql = "UPDATE users SET isAdmin=? WHERE usersEmail=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./admin_panel.php?error=erroraccepting");
        exit();
    }

    $isAdminVar = (int)filter_var($value, FILTER_VALIDATE_BOOLEAN);
    mysqli_stmt_bind_param($stmt, "is", $isAdminVar, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ./signup.php?error=adminadded");
    exit();
}

function changeOption($conn, $option, $value) {
    $sql = "UPDATE params SET paramValue=? WHERE paramName=?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./admin_panel.php?error=erroraccepting");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $value, $option);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ./signup.php?error=changedregisterpermission");
    exit();
}

function getConfiguration($conn, $name) {
    $sql = "SELECT paramValue FROM params WHERE paramName=?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./admin_panel.php?error=erroraccepting");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    if ($result === false) {
        header("location: ./signup.php?error=errorgettingpermission");
        exit();
    }

    if ($row = mysqli_fetch_assoc($result)) {
        return $row["paramValue"];
    }
    return false;
}

function canRegister($conn) {
    return getConfiguration($conn, "canRegister");
}

function getBookings($conn, $bool) {
    $myArray = array();
    $result = getDataFromTable($conn, "bookings");
    while ($row = $result->fetch_assoc()) {
        $myArray[] = $row;
    }
    if ($bool !== true) {
        echo json_encode($myArray);
        exit();
    }
    return $myArray;
}

function getBookingsByYear($conn) {
    $myArray = array();
    $bookings = getBookings($conn, true);
    foreach ($bookings as &$booking) {
        $dateData = explode("/", $booking["date"]);
        if (isset($myArray[$dateData[2]]) === false) {
            $myArray[$dateData[2]] = array();
        }
        $myArray[$dateData[2]][] = $booking;
    }
    return $myArray;
}

function getAutoBook($conn, $bool) {
    $myArray = array();
    $result = getDataFromTable($conn, "autobooks");
    while ($row = $result->fetch_assoc()) {
        $myArray[] = $row;
    }
    if ($bool !== true) {
        echo json_encode($myArray);
        exit();
    }
    return $myArray;
}

function addBooking($conn, $email, $id, $start, $end, $class, $grade, $book, $date, $return = false) {
    $result = emailExists($conn, "", $email);
    if ($result === false) {
        if ($return == true) {
            return "calendario.php?error=userdonesntexists";
        } else {
            echo "calendario.php?error=userdonesntexists";
            exit();
        }
    }

    $array = array();
    $start0 = explode(":", $start);
    $end0 = explode(":", $end);
    $old_records = getBookings($conn, true);
    for ($i = 0; $i < count($old_records); $i++) {
        $d = $old_records[$i];
        if (array_key_exists("date", $d)) {
            if ($d["date"] === $date) {
                if ($d["book"] === $book) {
                    $start1 = explode(":", $d["start"]);
                    $end1 = explode(":", $d["end"]);

                    $hour = intval($start1[0]);
                    $minute = intval($start1[1]);
                    $done = false;
                    while ($done === false) {
                        $minute += 5;
                        if ($minute === 60) {
                            $minute = 0;
                            $hour += 1;
                        }
                        array_push($array, $hour . ":" . $minute);

                        if ($hour == intval($end1[0]) && $minute == intval($end1[1])) {
                            $done = true;
                        }
                    }
                }
            }
        }
    }

    $hour = intval($start0[0]);
    $minute = intval($start0[1]);
    $error = false;
    $done = false;
    while ($done === false) {
        $minute += 5;
        if ($minute === 60) {
            $minute = 0;
            $hour += 1;
        }
        if (in_array($hour . ":" . $minute, $array)) {
            $done = true;
            $error = true;
        }

        if ($hour == intval($end0[0]) && $minute == intval($end0[1])) {
            $done = true;
        }
    }

    if ($error === true) {
        if ($return == true) {
            return "calendario.php?error=hoursbussy";
        } else {
            echo "calendario.php?error=hoursbussy";
            exit();
        }
    }

    $sql = "INSERT INTO bookings (id, start, end, name, class, grade, book, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        if ($return == true) {
            return "calendario.php?error=erroraccepting";
        } else {
            echo "calendario.php?error=erroraccepting";
            exit();
        }
    }

    $hashid = hash("ripemd160", $id);
    mysqli_stmt_bind_param($stmt, "ssssssss", $hashid, $start, $end, $result["usersName"], $class, $grade, $book, $date);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($return == true) {
        return "calendario.php?error=bookingcompleted";
    } else {
        echo "calendario.php?error=bookingcompleted";
        exit();
    }
}

function removeBooking($conn, $id, $start, $end, $class, $grade, $book, $name, $date, $return = false) {
    $sql = "DELETE FROM bookings WHERE id=? AND start=? AND end=? AND book=? AND class=? AND grade=? AND name=? AND date=?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        if ($return == true) {
            return "admin_panel.php?error=erroraccepting";
        } else {
            echo "admin_panel.php?error=erroraccepting";
            exit();
        }
    }

    mysqli_stmt_bind_param($stmt, "ssssssss", $id, $start, $end, $book, $class, $grade, $name, $date);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($return == true) {
        return "admin_panel.php?error=removedbook";
    } else {
        echo "admin_panel.php?error=removedbook";
        exit();
    }
};

function removeBookingsFromYear($conn, $year) {
    $myArray = array();
    $bookings = getBookings($conn, true);
    foreach ($bookings as &$booking) {
        $dateData = explode("/", $booking["date"]);
        if ($dateData[2] == $year) {
            removeBooking($conn, $booking["id"], $booking["start"], $booking["end"], $booking["class"], $booking["grade"], $booking["book"], $booking["name"], $booking["date"], true);
        }
    }
    return $myArray;
};

function addAutoBook($conn, $email, $weekday, $start, $end, $class, $grade, $book) {
    $result = emailExists($conn, "", $email);
    if ($result === false) {
        header("admin_panel.php?error=userdonesntexists");
        exit();
    }

    $sql = "INSERT INTO autobooks (weekday, start, end, book, class, grade, email) VALUES (?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("admin_panel.php?error=erroraccepting");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sssssss", $weekday, $start, $end, strtolower($book), $class, $grade, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("admin_panel.php?error=addedautobook");
    exit();
}

function removeAutoBook($conn, $email, $weekday, $start, $end, $class, $grade, $book) {
    $sql = "DELETE FROM autobooks WHERE weekday=? AND start=? AND end=? AND book=? AND class=? AND grade=? AND email=?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "admin_panel.php?error=erroraccepting";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sssssss", $weekday, $start, $end, $book, $class, $grade, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo "admin_panel.php?error=removedautobook";
    exit();
}

function getWeekDays($y, $month, $weekday) {
    $days = array();

    $selectedmonth = $month . "-" . $y;
    $dat = date("m", strtotime($selectedmonth));
    if (date('N', $dat) > 1) {
        $previousmonth = date('F Y', strtotime($selectedmonth . "-1 month"));
        $firstMonday = strtotime("last " . $weekday . " of " . $previousmonth);
    } else {
        $firstMonday = strtotime("first " . $weekday . " of " . $selectedmonth);
    }
    $temp = $firstMonday;
    array_push($days, date("Y-m-d", $firstMonday));
    $lastmonday = strtotime("last " . $weekday . " of " . $selectedmonth);
    while ($temp != $lastmonday) {
        $temp = strtotime(date("d F Y", $temp) . "+1 week");
        array_push($days, date("Y-m-d", $temp));
    }

    return $days;
}

function makeBookInMass($conn, $month, $y) {
    $weekdayConvert = array(
        "Lunes" => "monday",
        "Martes" => "tuesday",
        "Miércoles" => "wednesday",
        "Jueves" => "thursday",
        "Viernes" => "friday",
        "Sábado" => "saturday",
        "Domingo" => "sunday",
    );

    $monthConvert = array(
        "Enero" => "january",
        "Febrero" => "february",
        "Marzo" => "march",
        "Abril" => "april",
        "Marzo" => "may",
        "Junio" => "june",
        "Julio" => "july",
        "Agosto" => "august",
        "Septiembre" => "september",
        "Octubre" => "october",
        "Noviembre" => "november",
        "Diciembre" => "december",
    );

    $errorsinbooking = 0;
    $intmonth = date("m", strtotime($monthConvert[$month] . "-" . $y));
    foreach (getAutoBook($conn, true) as $book) {
        foreach (getWeekDays($y, $monthConvert[$month], $weekdayConvert[$book["weekday"]]) as $day) {
            $date = strtotime($day);
            if (date('m', $date) === $intmonth) {
                $date = date('m/d/Y', $date);
                $start = $book["start"];
                $end = $book["end"];
                $booking = $book["book"];
                try {
                    $output = addBooking($conn, $book["email"], $start . $end . $date . $booking, $start, $end, $book["class"], $book["grade"], $booking, $date, true);
                    if ($output !== "calendario.php?error=bookingcompleted") {
                        $errorsinbooking += 1;
                    }
                } catch (Exception $e) {
                    $errorsinbooking += 1;
                };
            }
        }
    }

    if ($errorsinbooking !== 0) {
        header("admin_panel.php?error=errorwhilebooking");
        exit();
    }
    header("admin_panel.php?error=massbookingcompleted");
    exit();
}

function changeIncidentEmail($conn, $email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("location: ./admin_panel.php?error=invalidemail");
        exit();
    }

    changeOption($conn, "incidenceEmail", $email);
    //mail($email,"PHP email function Test", "This is PHP email function Test.", "From: noreply@colegiofatima.com");

    header("location: ./admin_panel.php?error=emailchanged");
    exit();
}

function getIncidences($conn, $bool) {
    $myArray = array();
    $result = getDataFromTable($conn, "incidences");
    while ($row = $result->fetch_assoc()) {
        $myArray[] = $row;
    }
    if ($bool !== true) {
        echo json_encode($myArray);
        exit();
    }
    return $myArray;
}

function sanitize_xss($value) {
    return htmlspecialchars(strip_tags($value));
}

function sendIncidentEmail($conn, $text) {
    session_start();
    $name = $_SESSION["username"];
    $email = $_SESSION["useremail"];
    $day = date("d/m/Y");
    $time = date("H:i");
    $timewsec = date("H:i:s");

    $id = $name . $email . $day . $timewsec;
    $hashid = hash("ripemd160", $id);

    $emailto = getConfiguration($conn, "incidenceEmail");

    $text = sanitize_xss($text);

    $sql = "INSERT INTO incidences (`id`, `by`, `hour`, `day`, `sendto`, `msg`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("incidences.php?error=erroraccepting");
        exit();
    }

    $DEFAULT_STATUS = "En espera";
    mysqli_stmt_bind_param($stmt, "sssssss", $hashid, $name, $time, $day, $emailto, $text, $DEFAULT_STATUS);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $body = "Nueva incidencia fue registrada por " . $name . ":<br>" . $text . "<br><br>Revisar la incidencia.";
    $subject    = "Nueva incidencia registrada";;
    mail($emailto, $subject, $body);

    header("incidences.php?error=addedincident");
    exit();
}

function UpdateIncidence($conn, $id, $status) {
    $sql = "UPDATE incidences SET status=? WHERE id=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./admin_panel.php?error=erroraccepting");
        exit();
    }

    $status = sanitize_xss($status);

    mysqli_stmt_bind_param($stmt, "ss", $status, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ./admin_panel.php?error=updatedincidence");
    exit();
}

function addNewClass($conn, $name) {
    $sql = "INSERT INTO classes (name) VALUES (?);";
    $stmt = mysqli_stmt_init($conn);

    $name = sanitize_xss($name);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("admin_panel.php?error=erroraccepting");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("admin_panel.php?error=addedclass");
    exit();
}

function removeClass($conn, $name) {
    $sql = "DELETE FROM classes WHERE name=?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "admin_panel.php?error=erroraccepting";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo "admin_panel.php?error=removedclass";
    exit();
}

function getClasses($conn, $bool) {
    $myArray = array();
    $result = getDataFromTable($conn, "classes");
    while ($row = $result->fetch_assoc()) {
        $myArray[] = $row;
    }
    if ($bool !== true) {
        echo json_encode($myArray);
        exit();
    }
    return $myArray;
}

function getBookTypes($conn, $bool) {
    $myArray = array();
    $result = getDataFromTable($conn, "book_types");
    while ($row = $result->fetch_assoc()) {
        $myArray[] = $row;
    }
    if ($bool !== true) {
        echo json_encode($myArray);
        exit();
    }
    return $myArray;
}

function removeBookType($conn, $name) {
    $sql = "DELETE FROM book_types WHERE name=?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "admin_panel.php?error=erroraccepting";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo "admin_panel.php?error=removedbooktype";
    exit();
}

function getBookingsNow($conn, $booktypeselected) {
    $booktypeselected = strtolower($booktypeselected);
    $date = date("m/d/Y");
    $now = date("Hi");

    $booking = null;
    $old_records = getBookings($conn, true);
    for ($i = 0; $i < count($old_records); $i++) {
        $d = $old_records[$i];
        if (array_key_exists("date", $d)) {
            if ($d["date"] === $date) {
                if ($d["book"] === $booktypeselected) {
                    $start1 = explode(":", $d["start"]);
                    $end1 = explode(":", $d["end"]);

                    $start = $start1[0].$start1[1];
                    $end = $end1[0].$end1[1];
                    $start = intval($start);
                    $end = intval($end);

                    if ($now >= $start && $now < $end) {
                        $booking = $d;
                        break;
                    }
                }
            }
        }
    }

    if ($booking !== null) {
        echo json_encode($booking);
    }
    exit();
}