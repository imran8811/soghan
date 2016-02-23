<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');
class Admin extends CI_Controller {    

    public function __construct() {

        parent::__construct();

        $this->load->model('admin_model');
        $this->load->model('soghan_model');
        $this->load->library('session');

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    public function index() {
                
        $data['title'] = 'Admin Login - Soghan.ae';
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/index');
    }

    public function dashboard() {
        $this->login_check();
        
        $this->load->view('admin/includes/header');
        $this->load->view('admin/dashboard');
    }

    public function login_check() {

        if ($this->session->userdata('user_id') == '' && $this->session->userdata('username') == '') {
            $this->session->set_userdata('msg', 'You are not Logged In, Please Login First !');
            redirect('admin/index');
        }
    }

    public function check_already_loggedin() {

        if ($this->session->userdata('user_id') == TRUE && $this->session->userdata('username') == TRUE) {
            redirect('dashboard');
        }
    }
    
    public function user_login() {        
        
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Admin Login - Soghan.ae';
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/index');
        }
        else{            
            $login_data = array(
                'first_name' => $_POST['username'],
                'password'   => $_POST['password'],
                'status'     => 2
            );

            $check = $this->admin_model->checkRecord('users', $login_data);
            if($check){
              $this->session->set_userdata('username', $check->first_name);
              redirect('dashboard');
            }else{
              $this->session->set_userdata('msg', 'Invalid Username or Password');
              redirect('admin/index');
            }
        }
    }    

    public function logout() 
    {
      $this->session->unset_userdata('user_id');
      $this->session->unset_userdata('username');
      $this->session->sess_destroy();
      redirect('admin/index');
    }    
        
    public function change_password(){
        
        $this->login_check();
        
        $data['title']  = 'Change Password - Soghan.ae';
        $data['slider'] = base_url() . 'assets/images/img1.jpg';
        
        $user = $this->admin_model->checkRecord('users', array('user_id' => $this->session->userdata('user_id')));
        $this->session->set_userdata('password', $user->password);
                
        $this->load->view('includes/header', $data);
        $this->load->view('change_password'); 
    }        
    
    public function check_password(){
        $this->login_check(); 
        
        if($_POST['cur'] != $this->session->userdata('password')){
            echo 'Current Password does not matched !';
        }
        else{
            return 1;
        }
                 
    }        
    
    public function save_password(){       
        $this->login_check();            
        
        $this->form_validation->set_rules('current_password', 'Current Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required');
        
        if ($this->form_validation->run() == FALSE)
        {
            $data['title'] = 'Change Password - Soghan.ae';
            $data['slider'] = base_url() . 'assets/images/img1.jpg';
            
            $this->load->view('includes/header', $data);
            $this->load->view('change_password');
        }
        else{            
            if($this->session->userdata('password') != $_POST['current_password']){
                $this->session->set_userdata('msg', "Current Password doesn't matched!");
                redirect('change_password');
            }
            $result = $this->admin_model->updateRecord('users', 'user_id', $this->session->userdata('user_id'), array('password' => $_POST['new_password']));
            if($result != 0){
                $this->session->unset_userdata('user_id');
                $this->session->unset_userdata('username');
                $this->session->set_userdata('msg', 'Password Successfully Changed, Please Login!'); 
                redirect('login');
            }else{
                $this->session->set_userdata('msg', 'Password could not be Changed !');
                redirect('change_password');
            }
        }
    }    
      
    public function forgot_password(){
        
        if(!isset($_POST['forgot'])){
            $data['title'] = 'Forgot Password - Soghan.ae';
            $data['slider'] = base_url() . 'assets/images/img1.jpg';
            
            $this->load->view('includes/header', $data);
            $this->load->view('forgot_password');
            $this->load->view('includes/footer');
        }
        else{
            $check = $this->admin_model->checkRecord('users', array('email' => $this->input->get_post('email')));
            if($check){
                $this->load->library('encrypt');
                $enc_email = $this->encrypt->encode($this->input->get_post('email'));
                
                $msg = 'Please click the given link to Reset Password<br><a href="'. base_url() .'reset/'.$enc_email.'" targer="blank">Click Here!</a>';
                $this->send_email($this->input->get_post('email'), $check->first_name, 'Soghan Password Reset', $msg);                

                $this->session->set_userdata('msg', 'Email has been sent, Please check your email !');
                redirect('forgot');
            }else{
                $this->session->set_userdata('msg', 'Email not found !');
                redirect('forgot');
            }
        }
    }
    
    public function reset_password(){
        $data['title'] = 'Reset Password - Soghan.ae';
        $data['slider'] = base_url() . 'assets/images/img1.jpg';
        
        if(!isset($_POST['reset'])){
                        
            $enc_email = str_replace('reset/', '', $this->uri->uri_string());
            
            $this->load->library('encrypt');
            $email = $this->encrypt->decode($enc_email);
            $this->session->set_userdata('email', $email);
            
            $this->load->view('includes/header', $data);
            $this->load->view('reset_password');
            $this->load->view('includes/footer');
        }
        else{
            $this->form_validation->set_rules('password', 'Password', 'trim|required');

            if ($this->form_validation->run() == FALSE) {

                $this->load->view('includes/header', $data);
                $this->load->view('reset_password');
                $this->load->view('includes/footer');
            }
            else{
                $result = $this->admin_model->updateRecord('users', 'email', $this->session->userdata('email'), array('password' => $_POST['password']));                
                if ($result) {
                    $this->session->unset_userdata('email');
                    $this->session->set_userdata('msg', 'Password Successfully Reset, Please Login!');
                    redirect('login');
                } else {
                    $this->session->set_userdata('msg', 'Some Error Occurred !');
                    redirect('reset');
                }
            }
        }        
    }
    
    
    public function event_form($id=''){
        
        if(!empty($id)){
            
            $data['title'] = 'Edit Event - Soghan.ae';
            $result['event'] = $this->admin_model->getEvent($id);
            
            $this->session->set_userdata('edit_id', $id);
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/add_event', $result);
        }
        else{
            $data['title'] = 'Add Event - Soghan.ae';
            $this->load->view('admin/includes/header');
            $this->load->view('admin/add_event');
        }
    }
    
    public function save_event(){       
        $this->login_check();            
        
        $this->form_validation->set_rules('title', 'Title (english)', 'required');
        $this->form_validation->set_rules('arabic_title', 'Title (arabic)', 'required');
        $this->form_validation->set_rules('date', 'Event Date', 'required');
        $this->form_validation->set_rules('location', 'Location', 'required');
        if(!isset($_POST['all_day'])){
            $this->form_validation->set_rules('start', 'Start Time', 'required');
            $this->form_validation->set_rules('end', 'End Time', 'required');
        }

        $data = array(
            'name' => $_POST['event_name'],
            'arabic_name' => $_POST['arabic_event_name'],
            'title' => $_POST['title'],
            'arabic_title' => $_POST['arabic_title'],
            'event_location' => $_POST['location'],
            'arabic_event_location' => $_POST['arabic_location'],
            'timezone' => $_POST['timezone'],
            'event_date' => $_POST['date'],
            'start_time' => $_POST['date'] . ' ' . $_POST['start'],
            'end_time' => $_POST['date'] . ' ' . $_POST['end'],
            'all_day' => $_POST['all_day'],
            'addedto_local_calendar' => $_POST['calendar'],
            'notes' => $_POST['note'],
            'arabic_notes' => $_POST['arabic_note']
        );

        if ($this->form_validation->run() == FALSE)
        {
            $result['event'] = (object)$data;
            $data['title'] = 'Add Event - Soghan.ae';            
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/add_event', $result);
        }
        else{             
            if(isset($_POST['add'])){
                $res = $this->admin_model->saveRecord('events', $data);
            }
            else{
                $res = $this->admin_model->updateRecord('events', 'event_id', $this->session->userdata('edit_id'), $data);
            }
            
            if($res != 0){
                $this->session->set_userdata('msg', 'Event successfully saved !');
                redirect('view_events');
            }
            else{
                $this->session->set_userdata('msg', 'Event could not be saved !');
                $data['title'] = 'Event - Soghan.ae';
                $result['event'] = (object)$data;
                $this->load->view('admin/includes/header', $data);
                $this->load->view('admin/add_event', $result);
            }
        }
    } 

    public function get_events(){

        $data['title'] = 'View Events - Soghan.ae';
        
        $total = $this->admin_model->getAllEvents();
        $per_pg = 10;
        $offset = $this->uri->segment(2);

        $this->load->library('pagination');
        
        $config['base_url'] = base_url().'view_events/';
        $config['total_rows'] = count($total);
        $config['per_page'] = $per_pg;
        $config['uri_segment'] = 2;
        $config['full_tag_open'] = '<nav class="pagination-wrap"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>'; 
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $result['links'] = $this->pagination->create_links();
        
        $result['events'] = $this->admin_model->getAllEvents($per_pg, $offset);
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/view_events', $result);
    }
    
    public function delete_event($id){
        $res = $this->admin_model->deleteRecord('events', 'event_id', $id);
        if($res){
            $this->session->set_userdata('msg', 'Event Succesfully Deleted !');
        }else{
            $this->session->set_userdata('msg', 'Event could not be Deleted !');
        }
        redirect('view_events');
    }
    
    public function vendor_form($id=''){
        
        if(!empty($id)){
            
            $data['title'] = 'Edit Vendor - Soghan.ae';
            $result['vendor'] = $this->admin_model->getVendor($id);
                        
            $this->session->set_userdata('edit_id', $id);
            $this->session->set_userdata('image', $result['vendor']->vendor_mobile_image);
            $this->session->set_userdata('image1', $result['vendor']->vendor_image);
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/add_vendor', $result);
        }
        else{
            $data['title'] = 'Add Vendor - Soghan.ae';
            $this->load->view('admin/includes/header');
            $this->load->view('admin/add_vendor');
        }
    }
    
    public function save_vendor(){       
        $this->login_check();            
        
        $this->form_validation->set_rules('type', 'Vendor Title (arabic)', 'required');
        $this->form_validation->set_rules('english_type', 'Vendor Title (english)', 'required');
        
        $data = array(
            'vendor_type' => $_POST['type'],
            'english_vendor_type' => $_POST['english_type']
        );

        if ($this->form_validation->run() == FALSE)
        {
            $result['vendor'] = (object)$data;
            $data['title'] = 'Add Vendor - Soghan.ae';            
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/add_vendor', $result);
        }
        else{             
            if(isset($_POST['add'])){
                
                if (!empty($_FILES['mobile_logo']['name'])) {

                    $ext = explode('.', $_FILES['mobile_logo']['name']);
                    $imageName = time() . '.' . end($ext);

                    $config['upload_path'] = 'assets/uploads';
                    $config['file_name'] = $imageName;
                    $config['overwrite'] = false;
                    $config["allowed_types"] = 'jpg|jpeg|png';
//                    $config["max_size"] = 5024;
//                    $config["max_width"] = 1024;
//                    $config["max_height"] = 1000;
                    $this->load->library('upload', $config);
                    $this->upload->do_upload('mobile_logo');

                    $data['vendor_mobile_image'] = base_url().'assets/uploads/' . $imageName;
                }
                
                if (!empty($_FILES['web_logo']['name'])) {

                    $ext = explode('.', $_FILES['web_logo']['name']);
                    $imageName = time() . '1.' . end($ext);

                    $config['upload_path'] = 'assets/uploads';
                    $config['file_name'] = $imageName;
                    $config['overwrite'] = false;
                    $config["allowed_types"] = 'jpg|jpeg|png';
//                    $config["max_size"] = 5024;
//                    $config["max_width"] = 1024;
//                    $config["max_height"] = 1000;
                    $this->load->library('upload', $config);
                    $this->upload->do_upload('web_logo');

                    $data['vendor_image'] = base_url().'assets/uploads/' . $imageName;
                }
                
                $res = $this->admin_model->saveRecord('vendors', $data);
            }
            else{
                if (!empty($_FILES['mobile_logo']['name'])) {

                    $ext = explode('.', $_FILES['mobile_logo']['name']);
                    $imageName = time() . '.' . end($ext);

                    $config['upload_path'] = 'assets/uploads';
                    $config['file_name'] = $imageName;
                    $config['overwrite'] = false;
                    $config["allowed_types"] = 'jpg|jpeg|png';
//                    $config["max_size"] = 5024;
//                    $config["max_width"] = 1024;
//                    $config["max_height"] = 1000;
                    $this->load->library('upload', $config);
                    $this->upload->do_upload('mobile_logo');

                    $data['vendor_mobile_image'] = base_url().'assets/uploads/' . $imageName;
                }
                else{
                    $data['vendor_mobile_image'] = $this->session->userdata('image');
                }
                
                if (!empty($_FILES['web_logo']['name'])) {

                    $ext = explode('.', $_FILES['web_logo']['name']);
                    $imageName = time() . '1.' . end($ext);

                    $config['upload_path'] = 'assets/uploads';
                    $config['file_name'] = $imageName;
                    $config['overwrite'] = false;
                    $config["allowed_types"] = 'jpg|jpeg|png';
//                    $config["max_size"] = 5024;
//                    $config["max_width"] = 1024;
//                    $config["max_height"] = 1000;
                    $this->load->library('upload', $config);
                    $this->upload->do_upload('web_logo');

                    $data['vendor_image'] = base_url().'assets/uploads/' . $imageName;
                }
                else{
                    $data['vendor_image'] = $this->session->userdata('image1');
                }
                
                $res = $this->admin_model->updateRecord('vendors', 'vendor_id', $this->session->userdata('edit_id'), $data);
            }
            
            if($res != 0){
                $this->session->set_userdata('msg', 'Vendor successfully saved !');
                redirect('view_vendors');
            }
            else{
                $this->session->set_userdata('msg', 'Vendor could not be saved !');
                $data['title'] = 'Vendor - Soghan.ae';
                $result['vendor'] = (object)$data;
                $this->load->view('admin/includes/header', $data);
                $this->load->view('admin/add_vendor', $result);
            }
        }
    }
    
    public function get_vendors(){
        
        $data['title'] = 'View Vendors - Soghan.ae';
        
        $total = $this->admin_model->getAllVendors();
        $per_pg = 10;
        $offset = $this->uri->segment(2);

        $this->load->library('pagination');
        
        $config['base_url'] = base_url().'view_vendors/';
        $config['total_rows'] = count($total);
        $config['per_page'] = $per_pg;
        $config['uri_segment'] = 2;
        $config['full_tag_open'] = '<nav class="pagination-wrap"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>'; 
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $result['links'] = $this->pagination->create_links();
        
        $result['vendors'] = $this->admin_model->getAllVendors($per_pg, $offset);        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/view_vendors', $result);
    }    
    
    public function delete_vendor($id){
        $res = $this->admin_model->deleteRecord('vendors', 'vendor_id', $id);
        if($res){
            $this->session->set_userdata('msg', 'Vendor Succesfully Deleted !');
        }else{
            $this->session->set_userdata('msg', 'Vendor could not be Deleted !');
        }
        redirect('view_vendors');
    }
    
        
    
    public function vendor_details_form($id=''){
        
        $result['vendors'] = $this->admin_model->getAllVendors();
        
        if(!empty($id)){
            
            $data['title'] = 'Edit Vendor Details - Soghan.ae';
            $result['detail'] = $this->admin_model->getVendorDetails($id);
                        
            $this->session->set_userdata('edit_id', $id);
            $this->session->set_userdata('image', $result['detail']->image);
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/add_vendor_details', $result);
        }
        else{
            $data['title'] = 'Add Vendor Details - Soghan.ae';
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/add_vendor_details', $result);
        }
    }
    
    public function save_vendor_details(){       
        $this->login_check();            
        
        $this->form_validation->set_rules('vendor', 'Vendor', 'required');
        $this->form_validation->set_rules('company', 'Company Name (arabic)', 'required');
        $this->form_validation->set_rules('english_company', 'Company Name (english)', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('mobile', 'mobile', 'required');
        
        $data = array(
            'contact_name' => $_POST['contact'],
            'english_contact_name' => $_POST['english_contact'],
            'company_name' => $_POST['company'],
            'english_company_name' => $_POST['english_company'],
            'email'        => $_POST['email'],
            'phone'        => $_POST['phone'],
            'mobile'       => $_POST['mobile'],
            'latitude'     => $_POST['latitude'],
            'longitude'    => $_POST['longitude'],
            'description'  => $_POST['description'],            
            'vendor_id'    => $_POST['vendor'],            
            'created_date' => date('Y-m-d H:i:s')            
        );

        if ($this->form_validation->run() == FALSE)
        {
            $result['detail'] = (object)$data;
            $result['vendors'] = $this->admin_model->getAllVendors();
            $data['title'] = 'Add Vendor Details - Soghan.ae';
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/add_vendor_details', $result);
        }
        else{             
            if(isset($_POST['add'])){
                
                if (!empty($_FILES['picture']['name'])) {

                    $ext = explode('.', $_FILES['picture']['name']);
                    $imageName = time() . '.' . end($ext);

                    $config['upload_path'] = 'assets/uploads';
                    $config['file_name'] = $imageName;
                    $config['overwrite'] = false;
                    $config["allowed_types"] = 'jpg|jpeg|png';
//                    $config["max_size"] = 5024;
//                    $config["max_width"] = 1024;
//                    $config["max_height"] = 1000;
                    $this->load->library('upload', $config);
                    $this->upload->do_upload('picture');

                    $data['image'] = base_url().'assets/uploads/' . $imageName;
                }
                else{
                    $data['image'] = base_url().'assets/uploads/no-image.png';
                }
                
                $res = $this->admin_model->saveRecord('vendor_details', $data);
            }
            else{
                if (!empty($_FILES['picture']['name'])) {

                    $ext = explode('.', $_FILES['picture']['name']);
                    $imageName = time() . '.' . end($ext);

                    $config['upload_path'] = 'assets/uploads';
                    $config['file_name'] = $imageName;
                    $config['overwrite'] = false;
                    $config["allowed_types"] = 'jpg|jpeg|png';
//                    $config["max_size"] = 5024;
//                    $config["max_width"] = 1024;
//                    $config["max_height"] = 1000;
                    $this->load->library('upload', $config);
                    $this->upload->do_upload('picture');

                    $data['image'] = base_url().'assets/uploads/' . $imageName;
                }
                else {
                    $data['image'] = $this->session->userdata('image');
                }
                
                $res = $this->admin_model->updateRecord('vendor_details', 'vendor_detail_id', $this->session->userdata('edit_id'), $data);
            }
            
            if($res != 0){
                $this->session->set_userdata('msg', 'Vendor Details successfully saved !');
                redirect('view_vendor_details');
            }
            else{
                $this->session->set_userdata('msg', 'Vendor Details could not be saved !');
                $data['title']    = 'Vendor Details - Soghan.ae';
                $result['detail'] = (object)$data;
                $this->load->view('admin/includes/header', $data);
                $this->load->view('admin/add_vendor_details', $result);
            }
        }
    }
        
    public function get_vendor_details(){
        
        $data['title'] = 'View Vendor Details - Soghan.ae';
        
        $total = $this->admin_model->getAllVendorDetails();
        $per_pg = 10;
        $offset = $this->uri->segment(2);

        $this->load->library('pagination');
        
        $config['base_url'] = base_url().'view_vendor_details/';
        $config['total_rows'] = count($total);
        $config['per_page'] = $per_pg;
        $config['uri_segment'] = 2;
        $config['full_tag_open'] = '<nav class="pagination-wrap"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>'; 
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $result['links'] = $this->pagination->create_links();
        
        $result['details'] = $this->admin_model->getAllVendorDetails($per_pg, $offset);        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/view_vendor_details', $result);
    } 
    
    public function delete_vendor_details($id){
        $res = $this->admin_model->deleteRecord('vendor_details', 'vendor_detail_id', $id);
        if($res){
            $this->session->set_userdata('msg', 'Vendor Succesfully Deleted !');
        }else{
            $this->session->set_userdata('msg', 'Vendor could not be Deleted !');
        }
        redirect('view_vendor_details');
    }
    
        
    
    public function links_form($id=''){
        
        if(!empty($id)){
            
            $data['title'] = 'Edit Links - Soghan.ae';
            $result['link'] = $this->admin_model->getLink($id);
                        
            $this->session->set_userdata('edit_id', $id);
            $this->session->set_userdata('image', $result['link']->image);
            $this->session->set_userdata('image1', $result['link']->detail_image);
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/add_links', $result);
        }
        else{
            $data['title'] = 'Add Links - Soghan.ae';
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/add_links');
        }
    }
    
    public function save_links(){       
        $this->login_check();            
        
        // $this->form_validation->set_rules('contact', 'Contact Name (arabic)', 'required');
        // $this->form_validation->set_rules('company', 'Company Name (arabic)', 'required');
        // $this->form_validation->set_rules('email', 'Email', 'required');
        // if (empty($_FILES['picture']['name']) && isset($_POST['add']))
        // {
            // $this->form_validation->set_rules('picture', 'Small Picture', 'required');
        // }
        // if (empty($_FILES['detail_picture']['name']) && isset($_POST['add']))
        // { 
            // $this->form_validation->set_rules('detail_picture', 'Large Picture', 'required');
        // }
        
        $data = array(
            'username' => $_POST['contact'],
            'english_username' => $_POST['english_contact'],
            'company'  => $_POST['company'],
            'english_company'  => $_POST['english_company'],
            'email'    => $_POST['email'],
            'phone'    => $_POST['phone'],
            'location' => $_POST['location'],         
            'url' => $_POST['url']           
        );

        // if ($this->form_validation->run() == FALSE)
        // {
            // $result['link'] = (object)$data;
            // $result['vendors'] = $this->admin_model->getAllVendors();
            // $data['title'] = 'Add Vendor Details - Soghan.ae';
            // $this->load->view('admin/includes/header', $data);
            // $this->load->view('admin/add_links', $result);
        // }
        // else{             
            if(isset($_POST['add'])){
                
                if (!empty($_FILES['picture']['name'])) {

                    $ext = explode('.', $_FILES['picture']['name']);
                    $imageName = time() . '.' . end($ext);

                    $config['upload_path'] = 'assets/uploads';
                    $config['file_name'] = $imageName;
                    $config['overwrite'] = false;
                    $config["allowed_types"] = 'jpg|jpeg|png';
//                    $config["max_size"] = 5024;
//                    $config["max_width"] = 1024;
//                    $config["max_height"] = 1000;
                    $this->load->library('upload', $config);
                    $this->upload->do_upload('picture');

                    $data['image'] = base_url().'assets/uploads/' . $imageName;
                }
                if (!empty($_FILES['detail_picture']['name'])) {

                    $ext = explode('.', $_FILES['detail_picture']['name']);
                    $imageName = time() . '1.' . end($ext);

                    $config['upload_path'] = 'assets/uploads';
                    $config['file_name'] = $imageName;
                    $config['overwrite'] = false;
                    $config["allowed_types"] = 'jpg|jpeg|png';
//                    $config["max_size"] = 5024;
//                    $config["max_width"] = 1024;
//                    $config["max_height"] = 1000;
                    $this->load->library('upload', $config);
                    $this->upload->do_upload('detail_picture');

                    $data['detail_image'] = base_url().'assets/uploads/' . $imageName;
                }
                
                $res = $this->admin_model->saveRecord('links', $data);
            }
            else{
                if (!empty($_FILES['picture']['name'])) {

                    $ext = explode('.', $_FILES['picture']['name']);
                    $imageName = time() . '.' . end($ext);

                    $config['upload_path'] = 'assets/uploads';
                    $config['file_name'] = $imageName;
                    $config['overwrite'] = false;
                    $config["allowed_types"] = 'jpg|jpeg|png';
//                    $config["max_size"] = 5024;
//                    $config["max_width"] = 1024;
//                    $config["max_height"] = 1000;
                    $this->load->library('upload', $config);
                    $this->upload->do_upload('picture');

                    $data['image'] = base_url().'assets/uploads/' . $imageName;
                }                
                else {
                    $data['image'] = $this->session->userdata('image');
                }
                
                if (!empty($_FILES['detail_picture']['name'])) {

                    $ext = explode('.', $_FILES['detail_picture']['name']);
                    $imageName = time() . '.' . end($ext);

                    $config['upload_path'] = 'assets/uploads';
                    $config['file_name'] = $imageName;
                    $config['overwrite'] = false;
                    $config["allowed_types"] = 'jpg|jpeg|png';
//                    $config["max_size"] = 5024;
//                    $config["max_width"] = 1024;
//                    $config["max_height"] = 1000;
                    $this->load->library('upload', $config);
                    $this->upload->do_upload('detail_picture');

                    $data['detail_image'] = base_url().'assets/uploads/' . $imageName;
                }
                else {
                    $data['detail_image'] = $this->session->userdata('image1');
                }
                
                $res = $this->admin_model->updateRecord('links', 'link_id', $this->session->userdata('edit_id'), $data);
            }
            
            if($res){
                $this->session->set_userdata('msg', 'Links successfully saved !');
                redirect('view_links');
            }
            else{
                $this->session->set_userdata('msg', 'Links could not be saved !');
                $data['title']   = 'Links - Soghan.ae';
                $result['link'] = (object)$data;
                $this->load->view('admin/includes/header', $data);
                $this->load->view('admin/add_links', $result);
            }
        // }
    }
    
    public function get_links(){
        
        $data['title'] = 'View Links - Soghan.ae';
        
        $total = $this->admin_model->getAllLinks();
        $per_pg = 10;
        $offset = $this->uri->segment(2);

        $this->load->library('pagination');
        
        $config['base_url'] = base_url().'view_links/';
        $config['total_rows'] = count($total);
        $config['per_page'] = $per_pg;
        $config['uri_segment'] = 2;
        $config['full_tag_open'] = '<nav class="pagination-wrap"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>'; 
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $result['links'] = $this->pagination->create_links();
        
        $result['link'] = $this->admin_model->getAllLinks($per_pg, $offset);        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/view_links', $result);
    } 
    
    public function delete_links($id){
        $res = $this->admin_model->deleteRecord('links', 'link_id', $id);
        if($res){
            $this->session->set_userdata('msg', 'Link Succesfully Deleted !');
        }else{
            $this->session->set_userdata('msg', 'Link could not be Deleted !');
        }
        redirect('view_links');
    }
    
    
    
    
    public function get_cities_by_country(){
        
        $result['cities'] = $this->admin_model->getCitiesByCountry($_POST['c'], $_POST['s']);
        
        if(!empty($_POST['c'])){
            return $this->load->view('admin/partials/cities', $result);
        }
        else{
            return $this->load->view('admin/partials/default_cities', $result);
        }
    }
    
    public function maidans_form($id=''){
        
        $result['countries'] = $this->admin_model->getAllCountries();
        
        if(!empty($id)){
            
            $data['title'] = 'Edit Maidan - Soghan.ae';
            $result['maidan'] = $this->admin_model->getMaidan($id);
            $result['cities'] = $this->admin_model->getCitiesByCountry($result['maidan']->country_id);
                        
            $this->session->set_userdata('edit_id', $id);
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/add_maidans', $result);
        }
        else{
            $data['title'] = 'Add Maidan - Soghan.ae';
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/add_maidans', $result);
        }
    }
    
    public function save_maidans(){       
        $this->login_check();            
        
        $this->form_validation->set_rules('title', 'Maidan Title', 'required');
        
//        $country = (!empty($_POST['city'])) ? '0' : $_POST['country'];
        
        $data = array(
            'maidan_title' => $_POST['title'],        
            'country_id'   => $_POST['country'],        
            'city_id'      => $_POST['city']        
        );

        if ($this->form_validation->run() == FALSE)
        {
            $result['maidan'] = (object)$data;
            $result['countries'] = $this->admin_model->getAllCountries();
            $data['title'] = 'Add Maidan - Soghan.ae';
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/add_maidans', $result);
        }
        else{             
            if(isset($_POST['add'])){                
                $res = $this->admin_model->saveRecord('maidans', $data);
            }
            else{                
                $res = $this->admin_model->updateRecord('maidans', 'maidan_id', $this->session->userdata('edit_id'), $data);
            }
            
            if($res != 0){
                $this->admin_model->updateRecord('countries', 'country_id', $_POST['country'], array('maidan' => 1));
                $this->session->set_userdata('msg', 'Maidan successfully saved !');
                redirect('view_maidans');
            }
            else{
                $this->session->set_userdata('msg', 'Maidan could not be saved !');
                $data['title']   = 'Links - Soghan.ae';
                $result['maidan'] = (object)$data;
                $this->load->view('admin/includes/header', $data);
                $this->load->view('admin/add_maidans', $result);
            }
        }
    }
    
    public function get_maidans(){
        
        $data['title'] = 'View Maidans - Soghan.ae';
        
        $total = $this->admin_model->getAllMaidans();
        $per_pg = 10;
        $offset = $this->uri->segment(2);

        $this->load->library('pagination');
        
        $config['base_url'] = base_url().'view_maidans/';
        $config['total_rows'] = count($total);
        $config['per_page'] = $per_pg;
        $config['uri_segment'] = 2;
        $config['full_tag_open'] = '<nav class="pagination-wrap"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>'; 
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $result['links'] = $this->pagination->create_links();
        
        $result['maidans'] = $this->admin_model->getAllMaidans($per_pg, $offset);        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/view_maidans', $result);
    } 
    
    public function delete_maidans($id){
        $res = $this->admin_model->deleteRecord('maidans', 'maidan_id', $id);
        if($res){
            $this->session->set_userdata('msg', 'Maidan Succesfully Deleted !');
        }else{
            $this->session->set_userdata('msg', 'Maidan could not be Deleted !');
        }
        redirect('view_maidans');
    }
    
    public function push_form(){        
        $this->load->view('admin/includes/header');
        $this->load->view('admin/push_notification');
        $this->load->view('admin/includes/footer');
    }
    
     public function product_notification(){
                   
        $alert = array('title' => $_POST['title'], 'body' => $_POST['msg']);
        $data['channels'] = array('CHSoghanDev');
        $data['data'] = array(
            "alert" => $alert, 
            "badge" => "increment",
            "sound" => "default"
        );
        
        $data_string = json_encode($data);
        
        $result = file_get_contents('https://api.parse.com/1/push', null, stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/json' . "\r\n"
                . 'X-Parse-Application-Id: 4IKj8uLTuWueCuSnKl1TasBNmypA00EFVJY07tyl' . "\r\n"
                . 'X-Parse-REST-API-Key: QdzndZTRudXcK1oM5JtVZ8jR9c2ev8zxHpeY9OGZ'. "\r\n",
                'content' => $data_string,
            ),
        )));
        
//        header('Content-Type: application/json');
//        echo ($result);  
        
        $a = json_decode($result);
        if($a->result == 'true'){            
            $this->session->set_userdata('error', 'Successfully Sent !');
            redirect('push_form');
        }        
        
        //------------ APNS from Our Server Start ----------------\\
//        $file = 'assets/pro_ck.pem';          
//        $passphrase = '1234';
//        $ctx = stream_context_create();
//        stream_context_set_option($ctx, 'ssl', 'local_cert', $file);
//        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
//
//        $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
//
//        if (!$fp)
//            exit("Failed to connect: $err $errstr" . PHP_EOL);
//
//        echo 'Connected to APNS' . PHP_EOL;
//
//        $deviceToken = $token;
//
//        $alert = array('title' => 'Sayarti Alert', 'body' => 'Sayarti Message - 1');
//        $payload['aps'] = array(
//            'badge' => 1,
//            'alert' => $alert,
//            'sound' => 'default'
//        );
//        $payload = json_encode($payload);
//
//        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
//        $result = fwrite($fp, $msg, strlen($msg));
//
//        if (!$result){
//            echo $noti_msg = 'Message not delivered';
////                $this->session->set_userdata('msg', 'Error, Not Delivered');
////                redirect('push');
//        }
//        else{
//            echo $noti_msg = 'Message successfully delivered'.'<br>';
////                $this->session->set_userdata('msg', 'Successfully Delivered');
////                redirect('push');
//
//        }
//        @socket_close($fp);
//        fclose($fp);
        //------------ APNS from Our Server End ----------------\\
    }


    public function ad_form(){        
        $this->load->view('admin/includes/header');
        $this->load->view('admin/add_ad');
        $this->load->view('admin/includes/footer');
    }
    
    public function edit_ad($id){
        
        $result['ad'] = $this->admin_model->checkRecord('ads', array('ad_id' => $id));
                
        $this->load->view('admin/includes/header');
        $this->load->view('admin/edit_ad', $result);
        $this->load->view('admin/includes/footer');
    }
    
    public function del_ad($id){
        $res = $this->admin_model->deleteRecord('ads', 'ad_id', $id);
        if($res == 1){
            $this->session->set_userdata('success', 'Successfully Deleted!');
            redirect('view_adverts');            
        }else{
            $this->session->set_userdata('error', 'Successfully could not be Deleted!');
            redirect('view_adverts');            
        }
    }
    
    public function save_ad(){
        
        if(isset($_POST['btn_update'])){
            if (!empty($_FILES['picture']['name'])) {

                $ext = explode('.', $_FILES['picture']['name']);
                $imageName = time() . '.' . end($ext);

                $config['upload_path'] = 'assets/uploads';
                $config['file_name'] = $imageName;
                $config['overwrite'] = false;
                $config["allowed_types"] = 'jpg|jpeg|png';
                $config["max_width"] = 750;
                $config["max_height"] = 240;
                $this->load->library('upload', $config);
                
                if(!$this->upload->do_upload('picture')){                     
                    $this->session->set_userdata('error', $this->upload->display_errors());
                    redirect('edit_advert/'.$_POST['ad_id']);
                }

                $img = base_url().'assets/uploads/'.$imageName;
            }
            else{
                $img = $_POST['old_picture'];
            }
            
            $data = array('ad_image' => $img, 'url' => $_POST['url'], 'sort' => $_POST['sort']);
            $ad = $this->admin_model->updateRecord('ads', 'ad_id', $_POST['ad_id'], $data);
            if($ad){
                $this->session->set_userdata('error', "Advert Successfully Saved");
                redirect('view_adverts');
            }else{
                $this->session->set_userdata('error', "Advert couldn't be Added");
                redirect('edit_advert/'.$_POST['ad_id']);
            }
        }
        else{            
            if (!empty($_FILES['picture']['name'])) {

                $ext = explode('.', $_FILES['picture']['name']);
                $imageName = time() . '.' . end($ext);

                $config['upload_path'] = 'assets/uploads';
                $config['file_name'] = $imageName;
                $config['overwrite'] = false;
                $config["allowed_types"] = 'jpg|jpeg|png';
                $config["max_width"] = 750;
                $config["max_height"] = 240;
                $this->load->library('upload', $config);
                
                if(!$this->upload->do_upload('picture')){                     
                    $this->session->set_userdata('error', $this->upload->display_errors());
                    redirect('add_advert');
                }

                $img = base_url().'assets/uploads/'.$imageName;
            }
            else{
                $this->session->set_userdata('error', "Advert Image is required");
                redirect('add_advert');
            }        
                
            $data = array('ad_image' => $img, 'url' => $_POST['url'], 'sort' => $_POST['sort']);
            $ad = $this->admin_model->saveRecord('ads', $data);

            if($ad){
                $this->session->set_userdata('error', "Advert Successfully Added");
                redirect('view_adverts');
            }else{
                $this->session->set_userdata('error', "Advert couldn't be Added");
                redirect('add_advert');
            }
        }
    }    
    
    public function view_ads(){
        
        $result['ads'] = $this->admin_model->getAllAds();
        $this->load->view('admin/includes/header');
        $this->load->view('admin/view_ads', $result);
        $this->load->view('admin/includes/footer');
    }

    public function get_market_places(){

        $data['title'] = 'View Market Places - Soghan.ae';

        $total = $this->admin_model->getPosts();
        $per_pg = 10;
        if($this->uri->segment(2)){
            $offset = $this->uri->segment(2);
        }else{
            $offset = 0;
        }

        $this->load->library('pagination');

        $config['base_url'] = base_url().'view_market_places/';
        $config['total_rows'] = count($total);
        $config['per_page'] = $per_pg;
        $config['uri_segment'] = 2;
        $config['full_tag_open'] = '<nav class="pagination-wrap"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $result['links'] = $this->pagination->create_links();

        $result['posts'] = $this->admin_model->getPosts($offset, $per_pg);
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/view_market_places', $result);
    }

    public function delete_market_place(){

        $post_id = $_POST['chk_del'];
        if(count($post_id) > 0){
            for($i=0; $i<count($post_id); $i++){
                $res = $this->soghan_model->deletePost($post_id[$i]);
            }
            if($res){
                $this->session->set_userdata('msg', 'Post Successfully Deleted !');
            }else{
                $this->session->set_userdata('msg', 'Post could not be Deleted !');
            }
        }else{
            $this->session->set_userdata('msg', 'No Post Selected !');
        }
        redirect('view_market_places');
    }
  
}