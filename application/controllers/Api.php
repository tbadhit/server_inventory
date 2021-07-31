<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

    // Ini Api Untuk Panggil data ya di insert

    public function getBarang()
    {

        $hasil = $this->db->get("tb_barang");

        // cek kondisi ada datanya apa gak
        if ($hasil->num_rows() > 0) {
            // Bikin respones k emobile
            $data["pesan"] = "datanya ada";
            $data["sukses"] = true;
            $data["barang"] = $hasil->result();
        } else {
            $data["pesan"] = "datanya gak ada bang";
            $data["sukses"] = false;
        }

        echo json_encode($data);

    }

    public function updateBarang()
    {

        // Variabel inputan mobile Kopas
        $nama = $this->input->post("nama");
        $jumlah = $this->input->post("jumlah");
        $gambar = $this->input->post("gambar");
        $id = $this->input->post("id");

        $this->db->where('barang_id', $id);
        $getId = $this->db->get('tb_barang');

        if ($getId->num_rows() == 0) {
            $data['sukses'] = false;
            $data['pesan'] = "produk belum ada bang";
        } else {

            $this->db->where('barang_id', $id);

            $update['barang_nama'] = $nama;
            $update['barang_jumlah'] = $jumlah;
            $update['barang_gambar'] = $gambar;

            // Query Update
            $status = $this->db->update('tb_barang', $update);

            // Cek
            if ($status) {
                $data['sukses'] = true;
                $data['pesan'] = "Update berhasil";
            } else {
                $data['sukses'] = false;
                $data['pesan'] = "Update tidak berhasil";
            }
        }

        echo json_encode($data);

    }

    // Untuk mengambil data barang berdasarkan id nya
    public function getDetailBarang($id)
    {

        $this->db->where('barang_id', $id);
        $hasil = $this->db->get("tb_barang");

        // cek kondisi ada datanya apa gak
        if ($hasil->num_rows() > 0) {
            // Bikin respones k emobile
            $data["pesan"] = "datanya ada";
            $data["sukses"] = true;
            $data["barang"] = $hasil->row();
        } else {
            $data["pesan"] = "datanya tidak ditemukan";
            $data["sukses"] = false;
        }

        echo json_encode($data);

    }

    public function deleteBarang()
    {

        $id = $this->input->post("id");

        $this->db->where('barang_id', $id);
        $getId = $this->db->get('tb_barang');

        if ($getId->num_rows() == 0) {
            $data['sukses'] = false;
            $data['pesan'] = "produk belum ada bang Gak Bisa Hapus";
        } else {

            $this->db->where('barang_id', $id);
            $hasil = $this->db->delete('tb_barang');

            if ($hasil) {
                // Bikin respones ke mobile
                $data["pesan"] = "Berhasil Hapus Data Bang";
                $data["sukses"] = true;
            } else {
                $data["pesan"] = "datanya gak bisa dihapus";
                $data["sukses"] = false;
            }
        }

        echo json_encode($data);

    }

    public function insertGambar()
    {

        $gambar = $this->cek_foto('gambar');

        // d implementasi nama field databasenya

        $simpan = array();
        $simpan["barang_gambar"] = $gambar;

        // Using quoery for insert database

        $status = $this->db->insert("tb_barang", $simpan);

        $data = array();
        // Cek berhasil apa gak
        if ($status) {
            $data['sukses'] = true;
            $data['pesan'] = "Insert Berhasil";
        } else {
            $data['sukses'] = false;
            $data['pesan'] = "Insert tidak berhasil";
        }

        echo json_encode($data);
    }

    public function insertBarang()
    {
        $data = array();

        if ($this->input->post() != null) {

            // Variabel inputan mobile
            $nama = $this->input->post("nama");
            $jumlah = $this->input->post("jumlah");
            $gambar = $this->input->post("gambar");
            // $gambar = $this->cek_foto('gambar');

            // echo json_encode($foto);
            // exit;

            // d implementasi nama field databasenya
            $simpan = array();
            $simpan["barang_nama"] = $nama;
            $simpan["barang_jumlah"] = $jumlah;
            $simpan["barang_gambar"] = $gambar;

            // Using quoery for insert database

            $status = $this->db->insert("tb_barang", $simpan);

            // Cek berhasil apa gak
            if ($status) {
                $data['sukses'] = true;
                $data['pesan'] = "Insert Berhasil";
                $data['last_id'] = $this->db->insert_id();
            } else {
                $data['sukses'] = false;
                $data['pesan'] = "Insert tidak berhasil";
            }

            
        }else{
            $data['sukses'] = false;
            $data['pesan'] = "Insert tidak berhasil";
        }
        echo json_encode($data);
    }

    public function cek_foto($nama_foto)
    { //parameternya name file upload
        if (!empty($_FILES[$nama_foto]['name'])) {
            $config['upload_path'] = './image_server_resto_ios';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['encrypt_name'] = true;
            $config['overwrite'] = false;

            $this->load->library('upload', $config);
            $this->upload->do_upload($nama_foto);
            $data[$nama_foto] = $this->upload->data('file_name');
            return $data[$nama_foto];
        } else {
            return false;
        }
    }

    //  ++++++++++++++++++ Buat Fungsi Register +++++++++++++++++++++

    public function register()
    {

        //variable untuk ambil inputan dari mobile
        $name = $this->input->post("name");
        $email = $this->input->post("email");
        $password = $this->input->post("password");
        $hp = $this->input->post("hp");

        $this->db->where("user_email", $email);
        $check = $this->db->get("tb_user");

        if ($check->num_rows() > 0) {

            $data["sukses"] = false;
            $data["pesan"] = "email udah ke register,silahkan login";

        } else {

            //d implementasi nama field database nya
            $simpan = array();
            $simpan["user_nama"] = $name;
            $simpan["user_password"] = md5($password);
            $simpan["user_hp"] = $hp;
            $simpan["user_email"] = $email;

            //using query for insert database
            $status = $this->db->insert("tb_user", $simpan);

            $data = array();
            //check insertnya berhasil apa enggak
            if ($status) {
                $data["sukses"] = true;
                $data["pesan"] = "register berhasil";

            } else {
                $data["sukses"] = false;
                $data["pesan"] = "register failed,try again";

            }
        }

        echo json_encode($data);

    }

    public function login()
    {

        $email = $this->input->post("email");
        $password = $this->input->post("password");

        $this->db->where("user_email", $email);
        $this->db->where("user_password", md5($password));

        $hasil = $this->db->get("tb_user");

        //check query ada datanya apa enggak
        if ($hasil->num_rows() > 0) {

            //bikin response k mobile
            $data['pesan'] = "login berhasil";
            $data['sukses'] = true;
            $data["user_data"] = $hasil->row();
        } else {
            $data['pesan'] = "email atau password salah";
            $data['sukses'] = false;
        }

        echo json_encode($data);
    }

    public function getUser()
    {

        $hasil = $this->db->get("tb_user");

        // cek kondisi ada datanya apa gak
        if ($hasil->num_rows() > 0) {
            // Bikin respones k emobile
            $data["pesan"] = "datanya ada";
            $data["sukses"] = true;
            $data["barang"] = $hasil->result();
        } else {
            $data["pesan"] = "datanya gak ada bang";
            $data["sukses"] = false;
        }

        echo json_encode($data);

    }
}
