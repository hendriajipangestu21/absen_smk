<?php
include_once APPPATH . '/third_party/fpdf/fpdf.php';
$pdf = new FPDF('L', 'mm', 'Letter');
$pdf->AddPage();
$pdf->Image(base_url('myassets/logo.png'), 20, 5, -700);
$pdf->SetFont('Arial', '', 16);
$pdf->Cell(0, 6, 'YAYASAN HILMI SHADIQA DEWA', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 6, 'SMK BHAKTI ADI HUSODO', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 6, '"Terakreditasi A" BAN S/M', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 6, 'Jl. Raya Bayongbong KM. 10 Saung Cendol Desa Ciela', 0, 1, 'C');
$pdf->Cell(0, 6, 'Kec. Bayongbong - Garut Telp. (0262) 2808863 email smksbah@hotmail.com', 0, 1, 'C');
$pdf->SetLineWidth(1);
$pdf->Line(10, 43, 270, 43);
$pdf->SetLineWidth(0);
$pdf->Line(10, 44, 270, 44);

$pdf->Cell(10, 7, '', 0, 1);


$pdf->Cell(0, 6, 'DAFTAR REKAP ABSEN SISWA', 0, 1, 'C');

$pdf->Cell(10, 4, '', 0, 1);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(10, 6, 'No', 1, 0, 'C');
$pdf->Cell(50, 6, 'Nama Siswa', 1, 0, 'C');
$pdf->Cell(25, 6, 'Kelas', 1, 0, 'C');
$pdf->Cell(25, 6, 'Tanggal Masuk', 1, 0, 'C');
$pdf->Cell(25, 6, 'Jam Masuk', 1, 0, 'C');
$pdf->Cell(25, 6, 'Scan Masuk', 1, 0, 'C');
$pdf->Cell(25, 6, 'Terlambat', 1, 0, 'C');
$pdf->Cell(25, 6, 'Jam Pulang', 1, 0, 'C');
$pdf->Cell(25, 6, 'Scan Pulang', 1, 0, 'C');
$pdf->Cell(25, 6, 'Pulang Cepat', 1, 1, 'C');
$pdf->SetFont('Arial', '', 10);

$no = 0;
foreach ($data_rekap as $data) {

	$jam_masuk_siswa = "08.00.00";
	$jam_pulang_siswa = "16.00.00";


	$time_in = date_create($jam_masuk_siswa);
	$time_out = date_create($jam_pulang_siswa);
	$no++;
	$pdf->Cell(10, 6, $no, 1, 0, 'C');
	$pdf->Cell(50, 6, $data->nama, 1, 0);
	$pdf->Cell(25, 6, $data->nama_kelas, 1, 0, 'C');
	$pdf->Cell(25, 6, $data->date, 1, 0, 'C');
	$pdf->Cell(25, 6, date_format($time_in, 'H:i:s'), 1, 0, 'C');
	$pdf->Cell(25, 6, $data->time, 1, 0, 'C');

	date_add($time_in, date_interval_create_from_date_string('30 minutes'));
	$jam_masuk_tambah = date_format($time_in, 'H:i:s');
	if ($data->time > $jam_masuk_tambah) {
		$input_masuk = new DateTime($data->time);
		$masuk = new DateTime($jam_masuk_siswa);
		$jam_masuk_tambah = $input_masuk->diff($masuk);
		$terlambat = $jam_masuk_tambah->format("%H:%I:%S");
		$pdf->Cell(25, 6, $terlambat, 1, 0, 'C');
	} else {
		$pdf->Cell(25, 6, "-", 1, 0, 'C');
	}

	$pdf->Cell(25, 6, date_format($time_out, 'H:i:s'), 1, 0, 'C');
	$pdf->Cell(25, 6, $data->time_out, 1, 0, 'C');

	if ($jam_pulang_siswa > $data->time_out) {
		$input_keluar = new DateTime($data->time_out);
		$keluar = new DateTime($jam_pulang_siswa);
		$jam_pulang_cepat = $input_keluar->diff($keluar);
		$pulang_cepat = $jam_pulang_cepat->format("%H:%I:%S");
		$pdf->Cell(25, 6, $pulang_cepat, 1, 1, 'C');
	} else {
		$pdf->Cell(25, 6, "-", 1, 1, 'C');
	}
}
$pdf->Output();
