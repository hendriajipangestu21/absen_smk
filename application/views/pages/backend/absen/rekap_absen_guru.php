<div class="card">
	<div class="card-header">
		List Absensi Hari ini : <b><?= $this->input->get("hari") ? getDay($this->input->get("hari")) :  getDay(date("l")) ?>, <?= $this->input->get("hari") ? "" : date("d-M-Y") ?></b>
		<hr>
		<div class="row">
			<div class="col-md-6">
				Tanggal Mulai : <input class="form-control " type="date" name="tanggal_awal" id="filter_awal">
				<br>
				Tanggal Akhir : <input class="form-control " type="date" name="tanggal_akhir" id="filter_akhir">

				<br>
				<input class="btn btn-primary" type="button" value="Filter" onclick="filter()">

				<!-- <select name="hari" id="filter" class="form-control select2 w-25 float-right" onchange="filter()" required>
					<option value="">Filter Hari</option>
					<?php $hari = (object)array("Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"); ?>
					<?php foreach ($hari as $value) : ?>
						<option value="<?= $value ?>"><?= $value ?></option>
					<?php endforeach; ?>
				</select> -->
			</div>
			<!-- <div class="col-md-6">
				<a href="<?= base_url("admin/absen/tarik_data") ?>" class="btn btn-outline-primary"><i class="fa fa-plus"></i> Tarik Data</a>
			</div> -->
		</div>


	</div>
	<div class="card-body table-responsive">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama Guru</th>
					<th>Tangal Masuk</th>
					<th>Jam Masuk</th>
					<th>Scan Masuk</th>
					<th>Terlambat</th>
					<th>Tanggal Pulang</th>
					<th>Jam Pulang</th>
					<th>Scan Pulang</th>
					<th>Pulang Cepat</th>

					<!-- <th>Action</th> -->
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($contents["data_rekap"] as $key => $value) :
					$time_in = date_create($value->jam_masuk);
					$time_out = date_create($value->jam_pulang);

				?>
					<tr>
						<td><?= ($key + 1) ?></td>
						<td><?= $value->nama_guru ?></td>
						<td><?= $value->date ?></td>
						<td><?= date_format($time_in, 'H:i:s'); ?></td>
						<td><?= $value->time ?></td>
						<?php
						date_add($time_in, date_interval_create_from_date_string('30 minutes'));
						$jam_masuk_tambah = date_format($time_in, 'H:i:s');
						if ($value->time > $jam_masuk_tambah) {
							$input_masuk = new DateTime($value->time);
							$masuk = new DateTime($value->jam_masuk);

							$jam_masuk_tambah = $input_masuk->diff($masuk);
							$terlambat = $jam_masuk_tambah->format("%H:%I:%S");

						?>
							<td><?= $terlambat; ?></td>
						<?php } else {
						?>
							<td><?= "-"; ?></td>
						<?php } ?>
						<td><?= $value->date_out ?></td>
						<td><?= date_format($time_out, 'H:i:s'); ?></td>
						<td><?= $value->time_out ?></td>

						<?php if ($value->jam_pulang > $value->time_out) {

							$input_keluar = new DateTime($value->time_out);
							$keluar = new DateTime($value->jam_pulang);

							$jam_pulang_cepat = $input_keluar->diff($keluar);
							$pulang_cepat = $jam_pulang_cepat->format("%H:%I:%S");

						?>


							<td><?= $pulang_cepat; ?></td>
						<?php } else {
						?>
							<td><?= "-"; ?></td>
						<?php } ?>
						<!-- <td>
							<a href="<?= base_url("admin/jadwal/isi_absensi/$value->jadwal_id/$value->kelas_id/$value->mapel_id") ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Isi Absensi</a>&emsp;
						</td> -->
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<script>
	function filter() {
		var filter_awal = document.getElementById("filter_awal").value;
		var filter_akhir = document.getElementById("filter_akhir").value;
		if (filter != "") {
			window.location = "<?= base_url("admin/absen_guru/rekap_absen_guru") ?>?filter_awal=" + filter_awal + "&&filter_akhir=" + filter_akhir;
		}
	}
</script>