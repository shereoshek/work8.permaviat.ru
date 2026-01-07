<?php
    require_once("../../settings/connect_datebase.php");
    
    $sql= "SELECT * FROM `logs` ORDER BY `Date` DESC";
    $query = $mysqli->query($sql);

    $events = array();

    while($Read = $query->fetch_assoc()){
        $Status = "";

        $SqlSession = "SELECT * FROM `session` WHERE `IdUser`={$Read["IdUser"]} ORDER BY `DateStart` DESC";
        $QuerySession= $mysqli->query($SqlSession);
        if($QuerySession->num_rows > 0){
            $ReadSession = $QuerySession->fetch_assoc(); //задать вопрос почему два раза в видосе не сказано

            $TimeEnd = strtotime($ReadSession["DateNow"]) + 1*60;//минута в онлайне
            $TimeNow = time();

            if($TimeEnd > $TimeNow){
                $Status = "online";
            }else{
                $TimeEnd = strtotime($ReadSession["DateNow"]);
                $TimeDelta = round(($TimeNow - $TimeEnd)/60);
                $Status =  "Был в сети: {$TimeDelta} минут назад";
            }

        }

        $event = array(
            "Id" => $Read["Id"],
            "Ip" => $Read["Ip"],
            "Date" => $Read["Date"],
            "TimeOnline" => $Read["TimeOnline"],
            "Status" => $Status,
            "Event" => $Read["Event"]
        );
        array_push($events, $event);
    }

    echo json_encode($events, JSON_UNESCAPED_UNICODE);
?>