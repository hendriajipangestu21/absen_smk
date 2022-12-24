<?php

class Absen_siswa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata("is_login") !== true) {
            redirect("auth/out");
        }
        if ($this->session->userdata("level") == "siswa") {
            redirect("siswa/dashboard");
        }
        $this->load->model("JadwalModel", "jadwal");
        $this->load->model("RekapAbsenModel", "rekap_absen");
        $this->load->model("KelasModel", "kelas");
        $this->load->model("MapelModel", "mapel");
        $this->load->model("GuruModel", "guru");
        $this->load->model("AbsenModel", "absen");
        $this->load->model("SiswaModel", "siswa");
        include "./myassets/library_soap/lib_soap_x100c.php";
        date_default_timezone_set('Asia/Jakarta');
    }

    public function tarik_data()
    {
        $data["active"] = "absensi_siswa";
        $data["title"] = "Tarik data absen siswa";
        $data["tarik_data"] = $this->absen->all();
        $data["page"] = "absen/tarik_data";
        $this->load->view("layouts/backend", array("contents" => $data));
    }

    public function rekap_absen_siswa()
    {
        if (($this->input->get("filter_awal") != "") && ($this->input->get("filter_akhir") != "")) {
            $filter_awal = $this->input->get("filter_awal");
            $filter_akhir = $this->input->get("filter_akhir");
        } else {
            $filter_awal = date("Y-m-d");
            $filter_akhir = date("Y-m-d");
        }
        $data["active"] = "rekap_absen_siswa";
        $data["title"] = "Rekap Absensi Siswa";
        $data["data_rekap"] = $this->rekap_absen->rekap_absen_siswa(array("filter_awal" => $filter_awal, "filter_akhir" => $filter_akhir));
        $data["page"] = "absen/rekap_absen_siswa";
        $this->load->view("layouts/backend", array("contents" => $data));
    }

    public function cetak_rekap()
    {

        if (($this->input->get("filter_awal") != "") && ($this->input->get("filter_akhir") != "")) {
            $filter_awal = $this->input->get("filter_awal");
            $filter_akhir = $this->input->get("filter_akhir");
        } else {
            $filter_awal = date("Y-m-d");
            $filter_akhir = date("Y-m-d");
        }
        $data["active"] = "rekap_absensi_guru";
        $data["title"] = "Cetak Rekap Absensi Guru";
        $data["data_rekap"] = $this->rekap_absen->rekap_absen_siswa(array("filter_awal" => $filter_awal, "filter_akhir" => $filter_akhir));
        $data["page"] = "absen/cetak_rekap_absen_siswa";
        $this->load->view("pages/backend/absen/cetak_rekap_absen_siswa", $data);


        // $pdf = new FPDF('P', 'mm', 'Letter');
        // $pdf->AddPage();
        // $pdf->SetFont('Arial', 'B', 16);
        // $pdf->Cell(0, 7, 'REKAP DATA ABSEN GURU', 0, 1, 'C');
        // $pdf->Cell(10, 7, '', 0, 1);
        // $pdf->SetFont('Arial', 'B', 10);
        // $pdf->Cell(10, 6, 'No', 1, 0, 'C');
        // $pdf->Cell(90, 6, 'Nama Pegawai', 1, 0, 'C');
        // $pdf->Cell(120, 6, 'Alamat', 1, 0, 'C');
        // $pdf->Cell(40, 6, 'Telp', 1, 1, 'C');
        // $pdf->SetFont('Arial', '', 10);
        // $this->db->join("$this->table_guru", "$this->table.user_id=$this->table_guru.user_id", "RIGHT");
        // if (!empty($where)) {
        //     $this->db->where('date >=', $where['filter_awal']);
        //     $this->db->where('date <=', $where['filter_akhir']);
        // } else {
        //     echo "kosong";
        // }
        // $data_absen = $this->db->order_by("$this->table_guru.user_id", "DESC")->get($this->table)->result();
        // $no = 0;
        // foreach ($data_absen as $data) {
        //     $no++;
        //     $pdf->Cell(10, 6, $no, 1, 0, 'C');
        //     $pdf->Cell(90, 6, $data->user_id, 1, 0);
        //     $pdf->Cell(120, 6, $data->date, 1, 0);
        //     $pdf->Cell(40, 6, $data->date_out, 1, 1);
        // }
        // $pdf->Output();
    }


    public function absensi_siswa()
    {
        if ($this->input->get("hari") != "") {
            $hari = $this->input->get("hari");
        } else {
            $getDay = date("l");
            $hari = getDay($getDay);
        }
        $data["active"] = "absensi_siswa";
        $data["title"] = "Rekap Absensi Siswa";
        $data["jadwal"] = $this->jadwal->all(array("hari" => $hari));
        $data["page"] = "jadwal_siswa/absensi";
        $this->load->view("layouts/backend", array("contents" => $data));
    }

    public function isi_absensi($jadwalID = null, $kelas_id = null, $mapel_id = null)
    {
        $getDay = date("l");
        $hari = getDay($getDay);
        $data["active"] = "absensi";
        $data["title"] = "Isi Absensi";
        $data["absensi"] = $this->jadwal->absensi(array("hari" => $hari, "jadwal_id" => $jadwalID, "jadwal.kelas_id" => $kelas_id));
        $data["detail_absensi"] = $this->jadwal->getAbsensi(array("jadwal_id" => $jadwalID));
        if (empty($data["detail_absensi"])) {
            $data["edit"] = TRUE;
            $data["detail_absensi"] = $this->siswa->all(array("siswa.kelas_id" => $kelas_id));
        }
        $data["page"] = "jadwal/isi_absensi";
        $this->load->view("layouts/backend", array("contents" => $data));
    }

    public function save_absensi()
    {
        $siswa_id = $this->input->post("siswa_id");
        $absensi = $this->input->post("absensi");
        $kelas_id = $this->input->post("kelas_id");
        $mapel_id = $this->input->post("mapel_id");
        $jadwal_id = $this->input->post("jadwal_id");
        foreach ($siswa_id as $key => $value) {
            $params = array(
                "jadwal_id" => $jadwal_id,
                "kelas_id" => $kelas_id,
                "mapel_id" => $mapel_id,
                "siswa_id" => $siswa_id[$key],
                "absensi" => $absensi[$key],
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => $this->session->userdata("username")
            );
            if ($this->input->post("simpan_sms") == "simpan_sms") {
                $absensiText = $absensi[$key] == "H" ? "mengikuti" : ($absensi[$key] == "I" ? "IZIN tidak mengikuti" : "TIDAK mengikuti");
                $nama_siswa = $this->input->post("nama")[$key];
                $mapel = $this->input->post("mapel");
                $no_hp = $this->input->post("no_hp")[$key];
                $message = "Kpd Yth Bpk/ibu wali murid dari $nama_siswa, $absensiText Mata Pelajaran $mapel";
                SendAPI_SMS(urlencode($no_hp), urlencode($message));
            }
            $this->jadwal->save_absensi($params);
        }
        $this->session->set_flashdata("success", "Anda telah berhasil melakukan absensi");
        redirect("admin/jadwal/absensi");
    }

    public function update_absensi()
    {
        $siswa_id = $this->input->post("siswa_id");
        $absensi = $this->input->post("absensi");
        $kelas_id = $this->input->post("kelas_id");
        $mapel_id = $this->input->post("mapel_id");
        $jadwal_id = $this->input->post("jadwal_id");
        foreach ($siswa_id as $key => $value) {
            $params = array(
                "jadwal_id" => $jadwal_id,
                "kelas_id" => $kelas_id,
                "mapel_id" => $mapel_id,
                "siswa_id" => $siswa_id[$key],
                "absensi" => $absensi[$key],
                "created_by" => $this->session->userdata("username")
            );
            $where = array("absensi_id" => $this->input->post("absensi_id")[$key]);
            if ($this->input->post("simpan_sms") == "simpan_sms") {
                $absensiText = $absensi[$key] == "H" ? "mengikuti" : ($absensi[$key] == "I" ? "IZIN tidak mengikuti" : "TIDAK mengikuti");
                $nama_siswa = $this->input->post("nama")[$key];
                $mapel = $this->input->post("mapel");
                $no_hp = $this->input->post("no_hp")[$key];
                $message = "Kpd Yth Bpk/ibu wali murid dari $nama_siswa, $absensiText Mata Pelajaran $mapel";
                SendAPI_SMS(urlencode($no_hp), urlencode($message));
            }
            $this->jadwal->update_absensi($params, $where);
        }
        $this->session->set_flashdata("success", "Anda telah berhasil melakukan Edit absensi");
        redirect("admin/jadwal/absensi");
    }

    public function add()
    {
        $data["active"] = "jadwal";
        $data["title"] = "jadwal";
        $data["kelas"] = $this->kelas->all();
        $data["mapel"] = $this->mapel->all();
        $data["guru"] = $this->guru->all();
        $data["page"] = "jadwal/add";
        $this->load->view("layouts/backend", array("contents" => $data));
    }

    public function create()
    {
        $cek = $this->jadwal->edit(array("kelas_id" => $this->input->post("kelas_id"), "mapel_id" => $this->input->post("mapel_id")));

        if (!empty($cek)) {
            $this->session->set_flashdata("error", "Mapel di kelas terpilih sudah ada.");
            redirect("admin/jadwal");
        }

        $awalj = $this->input->post("awalj");
        $akhirm = $this->input->post("akhirm");
        $jam_awal = $awalj . ":" . $akhirm;

        $awalj2 = $this->input->post("awalj2");
        $akhirm2 = $this->input->post("akhirm2");
        $jam_akhir = $awalj2 . ":" . $akhirm2;
        $params = array(
            "kelas_id" => $this->input->post("kelas_id"),
            "mapel_id" => $this->input->post("mapel_id"),
            "guru_id" => $this->input->post("guru_id"),
            "hari" => $this->input->post("hari"),
            "awal" => $jam_awal,
            "akhir" => $jam_akhir,
        );
        if ($this->jadwal->save($params) === true) {
            $this->session->set_flashdata("success", "jadwal successfully created");
        } else {
            $this->session->set_flashdata("error", "jadwal failed to create");
        }

        redirect("admin/jadwal");
    }

    public function edit($id = null)
    {
        $data["active"] = "jadwal";
        $data["title"] = "jadwal Edit";
        $data["jadwal"] = $this->jadwal->edit(array("jadwal_id" => $id));
        $data["kelas"] = $this->kelas->all();
        $data["mapel"] = $this->mapel->all();
        $data["guru"] = $this->guru->all();
        $data["page"] = "jadwal/jadwal_edit";
        if (empty($data['jadwal'])) {
            redirect("admin/jadwal");
        }
        $this->load->view("layouts/backend", array("contents" => $data));
    }

    public function update()
    {

        $awalj = $this->input->post("awalj");
        $akhirm = $this->input->post("akhirm");
        $jam_awal = $awalj . ":" . $akhirm;

        $awalj2 = $this->input->post("awalj2");
        $akhirm2 = $this->input->post("akhirm2");
        $jam_akhir = $awalj2 . ":" . $akhirm2;
        $params = array(
            "kelas_id" => $this->input->post("kelas_id"),
            "mapel_id" => $this->input->post("mapel_id"),
            "guru_id" => $this->input->post("guru_id"),
            "hari" => $this->input->post("hari"),
            "awal" => $jam_awal,
            "akhir" => $jam_akhir,
        );

        $where = array("jadwal_id" => $this->input->post("jadwal_id"));

        if ($this->jadwal->update($params, $where) === true) {
            $this->session->set_flashdata("success", "jadwal changed successfully.");
        } else {
            $this->session->set_flashdata("error", "jadwal failed to changed.");
        }
        redirect("admin/jadwal");
    }

    public function delete($id = null)
    {
        $where = array("jadwal_id" => $id);
        if ($this->jadwal->delete($where) === true) {
            $this->session->set_flashdata("success", "jadwal deleted successfully.");
        } else {
            $this->session->set_flashdata("error", "jadwal failed to deleted.");
        }
        redirect("admin/jadwal");
    }

    public function sms()
    {
        $no_hp_tujuan = urlencode("081324275549"); // pisahkan dengan koma bila lebih dari 1 nomer tujuan
        $isi_pesan = urlencode("Coba kirim SMS");

        $result = SendAPI_SMS($no_hp_tujuan, $isi_pesan);
        echo $result;
    }
}
