<?php
include './myassets/library_soap/connect_device.php';
include './myassets/library_soap/lib_soap_x100c.php';
// include '../connect/connect_device.php';
// include '../library_soap/lib_soap_x100c.php';
?>
<center>
    <h1>GET LOG ABSENSI USER</h1>
    <br>
    <h3>
        <a href="../form_page/form_log_db.php">Form Input Log Db</a> |
        <a href="../page/log_data.php">Log Data</a> |
        <a href="../action/clear_log.php">Clear Log</a> |
        <a href="../index.php">Back to Home</a>
    </h3>
    <br>
    <?php
    if ($connect) {
        //choice soap request from library
        $Soap_val = Soap_request("log_absen", 1);
        $newLine = "\r\n";
        fputs($connect, "POST /iWsService HTTP/1.0" . $newLine);
        fputs($connect, "Content-Type: text/xml" . $newLine);
        fputs($connect, "Content-Length: " . strlen($Soap_val) . $newLine . $newLine);
        fputs($connect, $Soap_val . $newLine);

        $buffer = "";
        while ($Response = fgets($connect, 1024)) {
            $buffer = $buffer . $Response;
        }
    }
    // Parsing data buffer dan convert to array
    $buffer = Parse_Data($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
    $buffer = explode("\r\n", $buffer);

    $PIN = "0";
    $Verified = "0";
    $Status = "0";


    for ($i = 1; $i < count($buffer) - 1; $i++) {
        $data = Parse_Data($buffer[$i], "<Row>", "</Row>");
        $PIN = Parse_Data($data, "<PIN>", "</PIN>");
        $DateTime = Parse_Data($data, "<DateTime>", "</DateTime>");
        $Verified = Parse_Data($data, "<Verified>", "</Verified>");
        $Status = Parse_Data($data, "<Status>", "</Status>");
    }

    ?>


    <tr>
        <th scope="col"><?= $i ?></th>
        <br>
        <th scope="col"><?= $PIN ?></th>
        <br>
        <th scope="col"><?= $DateTime ?></th>
        <br>
        <th scope="col"><?= $Verified ?></th>
        <br>
        <th scope="col"><?= $Status ?></th>
        <br>
    </tr>