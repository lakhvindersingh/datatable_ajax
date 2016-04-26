<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Employee extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
			$this->load->helper(array('url','form'));
			$this->load->model('employee_model');
			$this->load->library('form_validation');
			$this->load->library('session');
	
			$this->load->library('Datatables');

			$this->load->library('table');
		
	}

	function index()
	{
		
		if($this->session->userdata('role') == "employee"){
			$session_data = $this->session->userdata('employee_name');
			$data['employee_name'] = $session_data;
			$this->load->view('employee/employee', $data);
		}
		else{
			redirect('welcome', 'refresh');
		}
	

	}	
			
	function logout(){
		$this->session->unset_userdata('employee_name');
		$this->session->unset_userdata('role');
		// session_destroy();
		redirect('welcome', 'refresh');
	}

}

/* End of file welcome.php */
/* Location: .//capplicationontrollers/welcome.php */
?>