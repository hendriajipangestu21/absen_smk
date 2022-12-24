<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-lg-4 col-md-4">
				<!-- small card -->
				<div class="small-box bg-gradient-success">
					<div class="inner">
						<h3><?= $jml_siswa ?></h3>
						<p>Jumlah Siswa</p>
					</div>
					<div class="icon">
						<i class="fas fa-user-plus"></i>
					</div>
					<a href="<?= base_url("admin/siswa") ?>" class="small-box-footer">
						More info <i class="fas fa-arrow-circle-right"></i>
					</a>
				</div>
			</div>
			<div class="col-lg-4 col-md-4">
				<!-- small card -->
				<div class="small-box bg-info">
					<div class="inner">
						<h3><?= $jml_guru ?></h3>
						<p>Jumlah Guru</p>
					</div>
					<div class="icon">
						<i class="fas fa-book-reader"></i>
					</div>
					<a href="<?= base_url("admin/guru") ?>" class="small-box-footer">
						More info <i class="fas fa-arrow-circle-right"></i>
					</a>
				</div>
			</div>

		</div>
	</div>
</div>