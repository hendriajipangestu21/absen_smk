<?php

class Tarik_data extends CI_Controller
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
        $this->load->model("KelasModel", "kelas");
        $this->load->model("MapelModel", "mapel");
        $this->load->model("GuruModel", "guru");
        $this->load->model("AbsenModel", "absen");
        $this->load->model("SiswaModel", "siswa");
        include "./myassets/library_soap/lib_soap_x100c.php";
    }

    public function tarik_data_user()
    {
        $data["active"] = "tarik data user";
        $data["title"] = "Tarik data user";
        $data["tarik_data"] = $this->absen->all();
        $data["page"] = "tarik_data_user/index";
        $this->load->view("layouts/backend", array("contents" => $data));
    }



    public function tarik_data_user_db()
    {
        $data["active"] = "tarik data user";
        $data["title"] = "Tarik data user";
        $data["page"] = "tarik_data/tarik_data_user";

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

        $input = 0;

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

            $query_siswa = $this->db->get_where('siswa', ['user_id' => $PIN])->row();
            $query_guru = $this->db->get_where('guru', ['user_id' => $PIN])->row();

            if (!$query_siswa && !$query_guru) {
                $input++;
                if ($Privilege === "14" || $Privilege === "2") {
                    $data = [
                        'user_id' => $PIN,
                        'nama_guru' => $Name,
                        'status' => $Privilege
                    ];
                    $save = $this->db->insert('guru', $data);
                } else {
                    $data = [
                        'user_id' => $PIN,
                        'nama' => $Name,
                        'status' => $Privilege
                    ];
                    $save = $this->db->insert('siswa', $data);
                }
            }
        }
        if ($save && $input > 0) {
            $this->session->set_flashdata("success", "Data behasil diinput sebanyak : " . $input);
            redirect("admin/tarik_data/tarik_data_user");
        } else {
            $this->session->set_flashdata("error", "Tidak ada pembaharuan data / semua data sudah di input ke database : " . $input);
            redirect("admin/tarik_data/tarik_data_user");
        }
        // $data["kelas"] = $this->kelas->all();
        // $this->load->view("layouts/backend", array("contents" => $data));
    }


    public function tarik_data_absen()
    {
        $data["active"] = "tarik data absen";
        $data["title"] = "Tarik data absen";
        $data["tarik_data"] = $this->absen->all();
        $data["page"] = "tarik_data_absen/index";
        $this->load->view("layouts/backend", array("contents" => $data));
    }


    public function tarik_data_absen_db()
    {
        $data["active"] = "tarik data absen";
        $data["title"] = "Tarik data absen";
        $data["page"] = "tarik_data/tarik_data_absen";

        $IP = "192.168.1.201"; //ip&key sesuaikan dengan mesin
        $Key = "0";

        $connect = fsockopen($IP, "80", $errno, $errstr, 1);

        if (!$connect) {
            die("Connection to Device Failed!");
        } else {
        }

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
        $input = 0;

        for ($i = 1; $i < count($buffer) - 1; $i++) {
            $data = Parse_Data($buffer[$i], "<Row>", "</Row>");
            $PIN = Parse_Data($data, "<PIN>", "</PIN>");
            $DateTime = Parse_Data($data, "<DateTime>", "</DateTime>");
            $Verified = Parse_Data($data, "<Verified>", "</Verified>");
            $Status = Parse_Data($data, "<Status>", "</Status>");


            $date1 = $DateTime;
            $date = new DateTime($date1);
            $tanggal = $date->format("Y-m-d");
            $waktu = $date->format("H:i:s");



            $query_absen = $this->db->get_where('data_absen', ['user_id' => $PIN, 'verified' => $Verified, 'date' => $tanggal])->row();

            // $date1 = $DateTime;
            // $date = new DateTime($date1);
            // $tanggal = $date->format("Y-m-d");

            if (!$query_absen) {
                if ($Status === '0') {
                    $input++;
                    $data = [
                        'user_id' => $PIN,
                        'verified' => $Verified,
                        'date' => $tanggal,
                        'time' => $waktu,
                        'status' => $Status
                    ];
                    $save = $this->db->insert('data_absen', $data);
                }
            } else {
                if ($Status === '1' && ($query_absen->time < $waktu)) {
                    echo "update";
                    $data = [
                        'date_out' => $tanggal,
                        'time_out' => $waktu,
                        'status' => $Status
                    ];

                    $where = [
                        'user_id' => $PIN,
                    ];

                    $query_absen_pulang = $this->db->get_where('data_absen', ['user_id' => $PIN, 'verified' => $Verified, 'date' => $tanggal, 'status' => '1'])->row();

                    if (!$query_absen_pulang) {
                        $input++;
                        $this->db->update('data_absen', $data, $where);
                    }
                }
            }
        }

        if ($input > 0) {
            $this->session->set_flashdata("success", "Data absen behasil diinput sebanyak : " . $input);
            redirect("admin/tarik_data/tarik_data_absen");
        } else {
            $this->session->set_flashdata("error", "Tidak ada pembaharuan data / semua data sudah di input ke database : " . $input);
            redirect("admin/tarik_data/tarik_data_absen");
        }
    }



    public function index()
    {
        $data["active"] = "absensi_siswa";
        $data["title"] = "Tarik data absen siswa";
        $data["tarik_data"] = $this->absen->all();
        $data["page"] = "absen/tarik_data";
        $this->load->view("layouts/backend", array("contents" => $data));
    }
}
