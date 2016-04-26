<?php
/**
* Controller Name : Leave 
* Author Name 	: Lakhvinder Singh
* Description : All the leave action
*
*/	
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Leave extends CI_Controller{
	function __construct(){
	    parent::__construct();
		    $this->load->helper(array('url','form'));
		    $this->load->model('leave_model');
		    $this->load->library('form_validation');
		    $this->load->library('session');	
		    $this->load->library('Datatable_leave');
		    $this->load->library('table');
		
	}
	/**
	* Author Name 	: Lakhvinder Singh
	* Controller Name : Leave 	
	* Method Name  : emp_leave_list
	* Description : To display theLeave list
	*
	*/		
	function  emp_leave_list(){
	    if($this->session->userdata('role') == "admin"){
		    $data['leave_detail']=$this->leave_model->emp_leave_detail_view();
			    $data['counter'] = $this->leave_model->user_counter();
		    $this->load->view("admin/leave",$data);
	    }
	    else{
		    redirect('welcome', 'refresh');
		}	
	
	}
	/**
	* Author Name 	: Lakhvinder Singh
	* Controller Name : Leave 	
	* Method Name  : datatable_leave
	* Description : To display the Leave list in table
	*
	*/		
	function datatable_leave(){
	    $this->datatable_leave->select('leave_trn_hdr.trn_hdr_id')				    
				    ->select('employee_master.employee_name')
				    ->select('leave_trn_hdr.employee_code')
				    ->select('leave_trn_hdr.manager_code')
				    ->select("DATE_FORMAT(leave_trn_hdr.from_date, '%d-%m-%Y') AS from_date", FALSE)
				    ->select("DATE_FORMAT(leave_trn_hdr.to_date, '%d-%m-%Y') AS to_date", FALSE)				
				    ->select('leave_trn_hdr.total_leaves')
				    ->select('leave_trn_hdr.debit_credit')
	    ->where('leave_trn_hdr.status', 1)
	    ->from('employee_master')
	    ->join('leave_trn_hdr','leave_trn_hdr.employee_code = employee_master.employee_code');
	    echo $this->datatable_leave->generate();
	}
	/**
	* Author Name 	: Lakhvinder Singh
	* Controller Name : Leave 	
	* Method Name  : add_leave
	* Description : Action Perform when the First Leave form is submit using ajax
	*
	*/
	function add_leave()	{
	    $employee_name=$_POST['employee_name'];
	    $password=$_POST['password'];
	    $manager_id=$_POST['manager_id'];
	    $role=$_POST['role'];		
	    if($this->session->userdata('logged_in'))		{
		    $data['con']=$this->employee_model->insert_leave($employee_name, $password,$manager_id,$role);	
		    print_r($data['con']);			
	    }
	    else {
		    redirect('welcome', 'refresh');	
	    }
	}
	/**
	* Author Name 	: Lakhvinder Singh
	* Controller Name : Leave 	
	* Method Name  : leave_insert
	* Description : Action Perform when the Second Leave form is submit using ajax
	*
	*/
	public function leave_insert(){
	    $from_date = date('Y-m-d', strtotime($_POST['from_date']));
	    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
	    
	    if(isset($_POST['to_date_half'])){
		    $todate_half=1;
	    }else{
		    $todate_half=0;
	    }
	    
	    if(isset($_POST['from_date_half'])){
		    $fromdate_half=1;
	    }else{
		    $fromdate_half=0;
	    }
	    
	    /*if(isset($_POST['trn_detail']))
	    {
	    $trn_detail=$_POST['trn_detail'];
	    }
	    else{
		    $trn_detail=null;
	    }*/
	    $data =array(
		    'cpl'		=>$_POST['cpl'],
		    'debit_credit'	=>$_POST['debit_credit'],
		    'employee_code'	=>$_POST['employee_code'],
					    
		    'eci'		=>$_POST['esi'],
		    'from_date'	=>$from_date,
		    'from_date_half'=>$fromdate_half,
		    'ml'		=>$_POST['ml'],
		    'others'	=>$_POST['others'],
		    'pl'		=>$_POST['pl'],
		    'reason'	=>$_POST['reason'],
		    'remarks'	=>$_POST['remarks'],
		    'slcl'		=>$_POST['slcl'],
		    'to_date'	=>$to_date,	
		    'to_date_half'	=>$todate_half,
		    'total_leaves'	=>$_POST['total_leaves'],
		    'wp'		=>$_POST['wp'],
		    'manager_code'	=>$_POST['manager_code']
		    );
	    if($_POST['trn_header_update_id'] == 0){
		    $data['con'] = $this->leave_model->leave_insert_model($data);
		    print_r($data['con']);
	    }else{
		    $data['con'] = $this->leave_model->leave_update_model($data,$_POST['trn_header_update_id']);
		    print_r($data['con']);
	    }
	}
	
	public function leave_id_status(){
	    $data =$_POST;
	    $this->leave_model->update_leave_status($data);		
	}
	/**
	* Author Name 	: Lakhvinder Singh
	* Controller Name : Leave 	
	* Method Name  : emp_view
	* Description : Display the leave of particular user
	*
	*/
	public function emp_view($id){
	    if($this->session->userdata('role') == "admin"){		
		    $data['detail']=$this->leave_model->emp_view_mod($id);
		    $data['header']=$this->leave_model->emp_view_mod_hdr($id);
		    print_r(json_encode($data));
	    }else{
		    redirect('welcome', 'refresh');
	    
	    }
	}
	/**
	* Author Name 	: Lakhvinder Singh
	* Controller Name : Leave 	
	* Method Name  : managerid
	*
	*/
	function managerid(){
	    if($this->session->userdata('role') == "admin"){		
		    if (isset($_GET['q'])){
			    $q = strtolower($_GET['q']);
			    $this->leave_model->managerid_mod($q);
		    }
	    }
	    else{
		    redirect('welcome', 'refresh');
	    
	    }
	}
	function employee_exist()
	{
	    $val =$_POST['empvalue'];
	    
	    $val1=$this->leave_model->employee_exist_mod($val);
	    print_r($val1);
	}
	/**
	* Author Name 	: Lakhvinder Singh
	* Controller Name : Leave 	
	* Method Name  : delete
	* Description : to delete particular user leave data
	*/
	function delete($id)
	{
	    if($this->session->userdata('role') == "admin"){
		    $this->leave_model->delete_mod($id);;
		    redirect('leave/emp_leave_list');
	    }else{
		    redirect('welcome', 'refresh');
	    
	    }	
	}
	/**
	* Author Name 	: Lakhvinder Singh
	* Controller Name : Leave 	
	* Method Name  : leave_edit
	* Description : to edit the leave of particular user
	*/	
	function  leave_edit($id){
	    if($this->session->userdata('role') == "admin"){		
		    $a['header']=$this->leave_model->leave_edit_mod($id);
		    //echo json_encode($a);
		    //redirect('employee/employees_lists');
		    $a['detail']=$this->leave_model->leave_edit_mod1($id);
		    echo json_encode($a);
	    }
	    else{
		    redirect('welcome', 'refresh');
	    
	    }
	}
	/**
	* Author Name 	: Lakhvinder Singh
	* Controller Name : Leave 	
	* Method Name  : deleterow
	* Description : to delete the particular that are display on the popup
	*/	
	function deleteRow($id){
	    if($this->session->userdata('role') == "admin"){
			$this->leave_model->deleteRow_mod($id);;
			//redirect('leave/emp_leave_list');
		}else{
			redirect('welcome', 'refresh');
		
		}
	}
}


?>