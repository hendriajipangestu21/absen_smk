<div class="card">
	<div class="card-header">
		<a href="<?= base_url("admin/jadwal/add") ?>" class="btn btn-outline-primary">Add <i class="fa fa-plus"></i></a>
	</div>
	<div class="card-body">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th scope="col">No</th>
					<th scope="col">User ID</th>
					<th scope="col">Tanggal & Jam</th>
					<th scope="col">Verifikasi</th>
					<th scope="col">Status</th>>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($contents["jadwal"] as $key => $value) : ?>
					<tr>
						<td><?= ($key + 1) ?></td>
						<td><?= $value->nama_kelas ?></td>
						<td><?= $value->nama_mapel ?></td>
						<td><?= $value->nama_guru ?></td>
						<td><?= $value->hari ?></td>
						<td><?= date("H:i", strtotime($value->awal)) ?> - <?= date("H:i", strtotime($value->akhir)) ?> </td>
						<td>
							<a href="<?= base_url("admin/jadwal/edit/$value->jadwal_id/") ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>&emsp;
							<a href="<?= base_url("admin/jadwal/delete/$value->jadwal_id/") ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ?')"><i class="fa fa-trash"></i></a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>