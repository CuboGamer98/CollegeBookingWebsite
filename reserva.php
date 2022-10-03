<?php
if(isset($_POST["btnenviar"])){
    $id = $_POST["shorainicio"] . $_POST["shorafinal"] . $_COOKIE["date"] . $_COOKIE["election"];
    $new_reserva = array(
        "id" => $id,
        "Hora_inicio" => $_POST["shorainicio"],
        "Hora_final" => $_POST["shorafinal"],
        "Nombre" => $_POST["nombreP"],
        "Asignatura" => $_POST["asignaturas"],
        "Clase" => $_POST["clase"],
        "Elecion" => $_COOKIE["election"],
        "Date" => $_COOKIE["date"]
    );

    foreach($new_reserva as $field){
        if(empty($field)){
            $error = "Ha habido un error al reservar";
            header("Location: calendario.php");
            return;
        }
    }

    if (isset($_COOKIE['date'])) {
        unset($_COOKIE['date']);
    }

    if(filesize("reservas.json") == 0){
        $first_record = array($new_reserva);
        $data_to_save = $first_record;
    }else{
        $old_records = json_decode(file_get_contents("reservas.json"), true);

        $start = str_replace(":", "", $_POST["shorainicio"]);
        $start = intval($start);
        $end = str_replace(":", "", $_POST["shorafinal"]);
        $end = intval($end);

        for ($i = 0; $i < count($old_records); $i++) {
            $d = $old_records[$i];
            if(array_key_exists("Date", $d) && array_key_exists("Date", $new_reserva)){
                if($d["Date"] === $new_reserva["Date"]){
                    if($d["Elecion"] === $new_reserva["Elecion"]){
                        $start2 = str_replace(":", "", $d["Hora_inicio"]);
                        $start2 = intval($start2);
                        $end2 = str_replace(":", "", $d["Hora_final"]);
                        $end2 = intval($end2);
                        if ($start < $start2 && $end > $end2) {
                            $error = "Las horas intermedias estan ocupadas.";
                            header("Location: calendario.php");
                            return;
                        } else if($start > $end) {
                            $error = "Error.";
                            header("Location: calendario.php");
                            return; 
                        }
                    }
                } 
            } 
        }

        array_push($old_records, $new_reserva);

        $data_to_save = $old_records;
    }

    if(!file_put_contents("reservas.json", json_encode($data_to_save, JSON_PRETTY_PRINT), LOCK_EX)){
        $error = "Ha habido un error al reservar";
    }else{
        $succes= "La reserva se ha realizado correctamente";
        header("Location: calendario.php");
    }
}