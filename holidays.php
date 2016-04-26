<?php
    /**
	 * Controller :Holidays
     * Author Name : Lakhvinder Singh
     * Description: addholidays calender and  add addholidays ,edit addholidays
     */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Holidays extends CI_Controller{
	
	function __construct()	{
		parent::__construct();
			$this->load->helper(array('url','form'));
			$this->load->model('admin_model');
                        $this->load->model('holidays_model');
			$this->load->library('form_validation');
			$this->load->library('session');	
			$this->load->library('table');
			$this->load->library('Holidays_table');
			
	}
    /**
	 * Controller :Holidays
     * Author Name : Lakhvinder Singh
     * Method name: index
     * Description: Calender
     * 
     */
	function index(){		
		if($this->session->userdata('role') == "admin"){
			$session_data = $this->session->userdata('employee_name');
			$data['employee_name'] = $session_data;
			$data['counter'] = $this->admin_model->user_counter();
			$data_holiday=$this->holidays_model->holidaysview_mod();
			$data1['val']=$data_holiday;
			$this->load->view('admin/header',$data);
			$this->load->view('admin/sidebar');	    
			$this->load->view('common/holidaysCalender',$data1);
			$this->load->view('admin/footer');
	   }
	   elseif($this->session->userdata('role') == "employee"){		
			$session_data = $this->session->userdata('employee_name');			
			$data['employee_name'] = $session_data;
			$data['counter'] = $this->admin_model->user_counter();
			$data_holiday=$this->holidays_model->holidaysview_mod();
			$data1['val']=$data_holiday;
			$this->load->view('employee/header',$data);
			$this->load->view('employee/sidebar');	    
			$this->load->view('common/holidaysCalender',$data1);
			$this->load->view('employee/footer');
	   }
	   else{
		   redirect('welcome', 'refresh');
	   }	
	}
    /**
	 * Controller :Holidays
     * Author Name : Lakhvinder Singh
     * Method name: holidaysList
     * Description: List of holiday
     * 
     */	
	function holidaysList(){
		 if($this->session->userdata('role') == "admin"){		
			$session_data = $this->session->userdata('employee_name');			
			$data['employee_name'] = $session_data;
			$data['counter'] = $this->admin_model->user_counter();			
			$this->load->view('admin/header',$data);
			$this->load->view('admin/sidebar');	    
			$this->load->view('admin/addholidays');
			$this->load->view('admin/footer');
		}
		else{
			redirect('welcome', 'refresh');
		}
		
	}
    /**
	 * Controller :Holidays
     * Author Name : Lakhvinder Singh
     * Method name: addholidays
     * Description: Add holiday
     * 
     */	
	function addholidays($id=null){
		if($_POST){
			$start1=$_POST['date'];
			$timestamp = strtotime($start1);
			$start= date("y-m-d", $timestamp);
			$title=$_POST['title'];
			$holiday_data=array('start'=>$start,'title'=>$title);
			//$test=$this->holidays_model->addholidays_model($holiday_data);			
			if(!empty($id)){
					$data['con']=$this->holidays_model->updateholidays_model($holiday_data,$id);					
					print_r($data['con']);
				}
			else{
				$data['con']=$this->holidays_model->addholidays_model($holiday_data,$id);
				print_r($data['con']);
			}			
		}
	}
    /**
	 * Controller :Holidays
     * Author Name : Lakhvinder Singh
     * Method name: holiday_edit
     * Description: edit Particular holiday
     * 
     */	
	function  holiday_edit($id){
		if($this->session->userdata('role') == "admin"){		
			$a=$this->holidays_model->holiday_edit_mod($id);
			echo json_encode($a);
			//redirect('employee/employees_lists');
		}
		else{
			redirect('welcome', 'refresh');
		
		}
	}
    /**
	 * Controller :Holidays
     * Author Name : Lakhvinder Singh
     * Method name: holidaysview 
     */	
	function holidaysview(){
		$data=$this->holidays_model->holidaysview_mod();
		$data1['val']=json_encode($data);		
		print_r(json_encode($data));
	}
    /**
	 * Controller :Holidays
     * Author Name : Lakhvinder Singh
     * Method name: holidays_table
     * Description: Holiday list
     * 
     */	
	function holidays_table(){		
		if($this->session->userdata('role') == "admin"){		
			$this->holidays_table->select('id,start,title')
			//->unset_column('product_id')
			->from('holiday_list');
			echo $this->holidays_table->generate();
		}
		else{			
			redirect('welcome', 'refresh');		
		}
	}
    /**
	 * Controller :Holidays
     * Author Name : Lakhvinder Singh
     * Method name: dateExist
     * Description: 
     * 
     */	
	function dateExist($date){ 
		$this->holidays_model->dateExist_mod($date);	  
	}
    /**
	 * Controller :Holidays
     * Author Name : Lakhvinder Singh
     * Method name: delete
     * Description: Delete the holidays
     * 
     */	
	function delete($id){
		if($this->session->userdata('role') == "admin"){
			$this->holidays_model->delete_mod($id);
			redirect('holidays/holidaysList');
		}else{
			redirect('welcome', 'refresh');
		
		}
	}
}