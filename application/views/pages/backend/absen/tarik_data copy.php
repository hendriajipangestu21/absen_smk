<?php


$IP = "192.168.1.201"; //ip&key sesuaikan dengan mesin
$Key = "0";

$connect = fsockopen($IP, "80", $errno, $errstr, 1);

if (!$connect) {
    die("Connection to Device Failed!");
} else {
}





?>

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

<div class="card">
    <div class="card-header">
        <a href="<?= base_url("admin/jadwal/add") ?>" class="btn btn-outline-primary">Add <i class="fa fa-plus"></i></a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>User ID</th>
                    <th>Tanggal & Jam</th>
                    <th>Vrifikasi</th>
                    <th>Status</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td scope="col"><?= $i ?></td>
                    <td scope="col"><?= $PIN ?></td>
                    <td scope="col"><?= $DateTime ?></td>
                    <td scope="col"><?= $Verified ?></td>
                    <td scope="col"><?= $Status ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>