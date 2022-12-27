<?php

class Pdf extends CI_Controller
{
    protected $table = "data_absen";
    protected $table_kelas = "kelas";
    protected $table_mapel = "mapel";
    protected $table_guru = "guru";
    protected $table_absensi = "absensi";
    protected $table_siswa = "siswa";
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata("is_login") !== true) {
            redirect("auth/out");
        }
        if ($this->session->userdata("level") == "siswa") {
            redirect("siswa/dashboard");
        }
        $this->load->model("GuruModel", "guru");

        include_once APPPATH . '/third_party/fpdf/fpdf.php';
    }

    public function index()
    {



        $pdf = new FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 7, 'REKAP DATA ABSEN GURU', 0, 1, 'C');
        $pdf->Cell(10, 7, '', 0, 1);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 6, 'No', 1, 0, 'C');
        $pdf->Cell(90, 6, 'Nama Pegawai', 1, 0, 'C');
        $pdf->Cell(120, 6, 'Alamat', 1, 0, 'C');
        $pdf->Cell(40, 6, 'Telp', 1, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $this->db->join("$this->table_guru", "$this->table.user_id=$this->table_guru.user_id", "RIGHT");
        if (!empty($where)) {
            $this->db->where('date >=', $where['filter_awal']);
            $this->db->where('date <=', $where['filter_akhir']);
        } else {
            echo "kosong";
        }
        $data_absen = $this->db->order_by("$this->table_guru.user_id", "DESC")->get($this->table)->result();
        $no = 0;
        foreach ($data_absen as $data) {
            $no++;
            $pdf->Cell(10, 6, $no, 1, 0, 'C');
            $pdf->Cell(90, 6, $data->user_id, 1, 0);
            $pdf->Cell(120, 6, $data->date, 1, 0);
            $pdf->Cell(40, 6, $data->date_out, 1, 1);
        }
        $pdf->Output();
    }
}
