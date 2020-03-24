<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model(array('Common_model' => 'cm'));
        $this->data = array(
            'menu' => 'dashboard'
        );
    }
    /*
        Load login page
        */
    public function index() {        
        if($this->session->userdata('user_logged_in')){
            redirect(base_url().'admin/dashboard');
        }
        $this->load->view('admin/login');	
    }
    /*
        Admin Login 
        */
    public function doLogin(){
        if (!empty($this->input->post())) {         
            $username = trim($this->input->post('logUsername'));
            $password = trim($this->input->post('logPassword'));
            $data['active_username'] = $username;
            $this->form_validation->set_rules("logUsername", "Email", "trim|required|valid_email");
            $this->form_validation->set_rules("logPassword", "Password", "trim|required|min_length[6]|max_length[12]");
            //check ci form validation 
            if ($this->form_validation->run() == FALSE) {                
                $this->load->view('admin/login', $data);
            } else {
                // If validation ok then send to user model to fetch userdata
                $UserLoginData = $this->cm->select_row('users', [
                    'email' => strtolower($username),
                    'login_password' => md5($password),
                    'status' => 1,
                    'login_type' => 0
                ],'id,email,name,created_at');
                //If userdata found 
                if (!empty($UserLoginData)) {                                  
                    $in_array = [
                        'id' => $UserLoginData['id'],
                        'name' => $UserLoginData['name'],
                        'created' => $UserLoginData['created_at']
                    ];
                    $this->session->set_userdata('user_logged_in', $in_array);                       
                    redirect('admin/dashboard');
                } else {                    
                    $this->session->set_flashdata('error_msg', 'Wrong Username or Password.');
                    $this->load->view('admin/login', $data);
                }
            }
        }
    }
    /*
        Admin Dashboard
    */
    public function dashboard(){
        if(!$this->session->userdata('user_logged_in')){
            redirect(base_url().'admin');
        }
        
        $data = array();
        $data = $this->data;
        $data['sub_menu'] = 'dashboard';
        $data['title'] = 'Admin | Dashboard';
        $this->load->view('admin/dashboard', $data);
    }
    /*
        Admin Profile */
    public function profile(){
        if(!$this->session->userdata('user_logged_in')){
            redirect(base_url().'admin');
        }
        $data = array();
        $data = $this->data;
        $data['sub_menu'] = '';
        $data['title'] = 'Admin | Profile';
        /*
            Update profile data
            */
        if($this->input->post()){
            $update = $this->cm->update('users', ['id'=> $this->getUserSessionId()], ['name'=> $this->input->post('name'), 'email'=> $this->input->post('email')]);
            if($update){
                $this->session->set_flashdata('success_msg', 'Successfully update profile data.');
                redirect(base_url().'admin/profile');
            }
        }
        $data['edit_data'] = $this->cm->select_row('users', ['id' => $this->getUserSessionId(), 'login_type'=> 0], 'id,name,email');
        $this->load->view('admin/edit_profile', $data);
    }
    /*
        Admin Change Password
    */
    public function change_password(){
        if(!$this->session->userdata('user_logged_in')){
            redirect(base_url().'admin');
        }
        $data = $this->data;
        $data['sub_menu'] = '';
        $data['title'] = 'Admin | Change Password';        
        $this->load->view('admin/change_password', $data);
    }
    public function doChangePassword(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');
            $con_new_password = $this->input->post('con_new_password');
            if(strlen($new_password) < 6){
                echo json_encode(['success'=> false, 'message'=> "New password must be 6 digits"]); die;
            }            
            if($new_password !== $con_new_password){
                echo json_encode(['success'=> false, 'message'=> "New password and confirm password do not same"]); die;
            }
            
            if($this->cm->select_row('users', ['id' => $this->getUserSessionId(), 'login_password' => md5($old_password)],'id')){
                if($old_password == $new_password){
                    echo json_encode(['success'=> false, 'message'=> "New password must be different from old"]); die;
                }
                
                $update = $this->cm->update('users', ['id'=> $this->getUserSessionId()], ['login_password'=> md5($con_new_password)]);
                if($update){
                    echo json_encode(['success'=> true, 'message'=> 'Password Change Successfully.']); die;
                }
            } else {
                echo json_encode(['success'=> false, 'message'=> "Old password do not match"]); die;
            }
        }
    }
    /*
        Forget password
        */
    public function forget_password(){
        if($this->session->userdata('user_logged_in')){
            redirect(base_url().'admin/dashboard');
        }
        $data = array();
        if($this->input->post()){
            $exist_data = $this->cm->select_row('users', ['email' => $this->input->post('register_email')], 'id');
            if($exist_data){
                $update_password = $this->cm->update('users', ['id'=> $this->getUserSessionId(), 'login_type'=> 0], ['login_password'=> md5(123456)]);
                if($update_password){
                    $subject = 'Forgot Password!';
                    $message = '<p>Your new password is: <b>123456</b></p>';
                    $message .= '<p>Please change your password on first login. It is a good idea for you to change your password the first time you log in to the admin panel.</p>';
                    $message .= '<p>Thank you.</p>'; 
                    $this->cm->send_email($this->input->post('register_email'), siteSettingsData()['site_sender_email'],'','', $subject, $message,'','','');

                    $this->session->set_flashdata('success_msg', 'Please check your email - we have sent a new password to your email');
                    redirect(base_url().'admin/forgot/password');
                } else {
                    $this->session->set_flashdata('error_msg', 'An error occurred, please try again.');
                    redirect(base_url().'admin/forgot/password');
                }
            } else {
                $this->session->set_flashdata('error_msg', 'The e-mail does not exist.');
                redirect(base_url().'admin/forgot/password');
            }
        }
        $this->load->view('admin/forget_password', $data);
    }
    public function getUserSessionId(){        
        return $this->session->userdata('user_logged_in')['id'];
    }
    /*
        Admin logout
    */
    public function doLogout(){
        $this->session->userdata('user_logged_in', '');
        $this->session->unset_userdata('user_logged_in');
        redirect(base_url().'admin');
    }
}
