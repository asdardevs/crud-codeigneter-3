<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('crud_model', 'crud');
	}
	public function index()
	{
		$this->load->view('welcome_message');
	}

	function hapus()
	{
		$value = $this->input->post('id');
		$tabel = $this->input->post('tabel');
		$field = $this->input->post('field');

		$data = $this->crud->hapus($tabel, $field, $value);
		if ($data == '1') {
			echo '1';
		} else {
			echo '2';
		}
	}
}
