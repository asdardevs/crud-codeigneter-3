<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('crud_model', 'crud');
    }

    public function index()
    {
        $data['page'] = 'mahasiswa_v';
        $this->load->view('layout/main', $data);
    }
    public function get()
    {
        $query = $this->db->select(array('a.id', 'a.nim', 'a.nama', 'a.jurusan'))->from('mahasiswa a');
        // ->join("user_role b", "a.role_id = b.id", "left"); //field yang ada di table user dan nama database dan join

        $column_order = array(null, 'a.id', 'a.nim', 'a.nama', 'a.jurusan'); //set column field database for datatable orderable
        $column_search = array('a.id', 'a.nim', 'a.nama', 'a.jurusan'); //set column field database for datatable searchable 
        $order = array('a.id' => 'asc'); // default order




        $list = $this->crud->get_datatables($query, $column_order, $column_search, $order);
        $data = array();
        $no   = $_POST['start'];
        foreach ($list['result'] as $rowi) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $rowi->nim;
            $row[] = $rowi->nama;
            $row[] = $rowi->jurusan;


            $row[] = '<div class="d-flex justify-content-around">
                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-success" onClick="edit(\'' . $rowi->id . '\'); return false;" ><i class="fas fa-pen fa-sm"></i></button>
                            <button type="button" class="btn btn-danger" onClick="hapus(\'' . $rowi->id . '\'); return false;" ><i class="fas fa-trash fa-sm"></i></button>
                        </div>
                        </div>
                    ';

            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->crud->count_all($query),
            "recordsFiltered" => $list['count_filtered'],
            "data" => $data
        );
        echo json_encode($output);
    }

    function edit()
    {


        $id = $this->input->post('id');
        $data = $data = $this->crud->get_edit($id, 'id', 'mahasiswa');
        $result = array();
        foreach ($data as $resulte) {
            $result  = array(
                'nim' => $resulte->nim,
                'nama' => $resulte->nama,
                'jurusan' => $resulte->jurusan,

            );
        }
        echo json_encode($result);
    }

    function simpan()
    {

        $field     = $this->input->post('kode', true);
        $aksi     = $this->input->post('rcstat', true);
        $hari_ini = date("Y-m-d H:i:s");

        $data_valid = array('success' => false, 'messages' => array());
        $this->form_validation->set_rules('nama', 'nama', 'required|trim');
        $this->form_validation->set_rules('nim', 'nim', 'required|trim|numeric|max_length[10]');
        $this->form_validation->set_rules('jurusan', 'jurusan', 'required|trim');

        if ($this->form_validation->run()) {

            $data = array(
                'nama'    => $this->input->post('nama', true),
                'nim'    => $this->input->post('nim', false),
                'jurusan'    => $this->input->post('jurusan', false),
            );
            if ($aksi == 1) {
                $data['created_at'] = $hari_ini;
            } else {
                $data['updated_at'] = $hari_ini;
            }
            /*==================== simpan=================== */
            if ($this->crud->hsave('mahasiswa', $data, $field, $aksi, 'id') == true) {
                $return = array(
                    'success'    => true,
                    'message'    => 'Data berhasil disimpan..',
                );
            } else {
                $return = array(
                    'success'    => false,
                    'message'    => 'Terjadi kesalahan..',
                );
            }
            echo json_encode($return);
        } else {
            foreach ($_POST as $key => $value) {
                $data_valid['messages'][$key] = form_error($key);
            }
            echo json_encode($data_valid);
        }
    }
}

/* End of file Mahasiswa.php */
