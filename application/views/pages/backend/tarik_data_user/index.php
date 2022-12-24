<?php


$IP = "192.168.1.201"; //ip&key sesuaikan dengan mesin
$Key = "0";

$connect = fsockopen($IP, "80", $errno, $errstr, 1);

if (!$connect) {
	die("Connection to Device Failed!");
} else {
}
if ($connect) {
	//choice soap request from library
	$Soap_val =  "<GetAllUserInfo>
                            <ArgComKey Xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey>
                        </GetAllUserInfo>";
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
$buffer = Parse_Data($buffer, "<GetAllUserInfoResponse>", "</GetAllUserInfoResponse>");
$buffer = explode("\r\n", $buffer);
?>

<div class="card">
	<div class="card-header">
		<a href="<?= base_url("admin/tarik_data/tarik_data_user_db") ?>" class="btn btn-outline-primary">Tambahkan ke database <i class="fa fa-plus"></i></a>
	</div>
	<div class="card-body">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>No</th>
					<th>User ID</th>
					<th>Nama</th>
					<th>Password</th>
					<th>Group</th>
					<th>Privilege</th>
					<th>Card</th>
					<th>ID 2</th>
					<th>T1</th>
					<th>T2</th>
					<th>T3</th>

				</tr>
			</thead>
			<tbody>
				<?php
				for ($i = 1; $i < count($buffer) - 1; $i++) {
					$data = Parse_Data($buffer[$i], "<Row>", "</Row>");
					$PIN = Parse_Data($data, "<PIN>", "</PIN>");
					$Name = Parse_Data($data, "<Name>", "</Name>");
					$Password = Parse_Data($data, "<Password>", "</Password>");
					$Group = Parse_Data($data, "<Group>", "</Gro>");
					$Privilege = Parse_Data($data, "<Privilege>", "</Privilege>");
					$Card = Parse_Data($data, "<Card>", "</Card>");
					$PIN2 = Parse_Data($data, "<PIN2>", "</PIN2>");
					$TZ1 = Parse_Data($data, "<TZ1>", "</TZ1>");
					$TZ2 = Parse_Data($data, "<TZ2>", "</TZ2>");
					$TZ3 = Parse_Data($data, "<TZ3>", "</TZ3>");
				?>
					<tr>
						<th><?= $i ?></th>
						<th><?= $PIN ?></th>
						<th><?= $Name ?></th>
						<th><?= $Password ?></th>
						<th><?= $Group ?></th>
						<th><?= $Privilege ?></th>
						<th><?= $Card ?></th>
						<th><?= $PIN2 ?></th>
						<th><?= $TZ1 ?></th>
						<th><?= $TZ2 ?></th>
						<th><?= $TZ3 ?></th>
					</tr>
				<?php	} ?>
			</tbody>
		</table>
	</div>
</div>
<tr>