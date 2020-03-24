<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/CommonController.php';
class Login extends CommonController {
    public function index(){
        $this->checkAge();
        if($this->session->userdata('UserId')){
            redirect(base_url());
        }
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/login');
        $this->load->view('frontend/layout/footer');
    }
    public function setAge(){
        $this->session->set_userdata('setAge', 'Done');
    }
    public function signUp() {
        $this->checkAge();
        if($this->session->userdata('UserId')){
            redirect(base_url());
        }
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/signup');
        $this->load->view('frontend/layout/footer');
    }
    public function doRegistration(){
        $chk = $this->cm->get_all('users', array("email" => $this->input->post('reg_email')));
        if(empty($chk)){
            if($this->input->post('reg_type') == 1){
                $status = 1;
                $verified = 'Yes';
            }else{
                $status = 0;
                $verified = 'No';
            }
            $in_array = [
                'name'              => $this->input->post('reg_name'),
                'email'             => $this->input->post('reg_email'),
                'login_type'        => $this->input->post('reg_type'),
                'login_password'    => md5($this->input->post('reg_pwd')),
                'gender'            => $this->input->post('reg_gender'),
                'status'            => $status,
                'account_verified'  => $verified
            ];
            $insert_id = $this->cm->insert('users', $in_array);
            if($insert_id){
                $this->cm->insert('user_preference', array("user_id" => $insert_id));
                print "ok";
            }else{
                print "notok";
            }
        }else{
            print 'Sorry !!! User already exists with this email !!!';
        }
    }
    public function doLogin(){
        $chk = $this->cm->get_all('users', array("email" => $this->input->post('login_email'), "login_password" => md5($this->input->post('login_pwd'))));
        if(empty($chk)){
            print 'Sorry !!! Email & Password mismatch !!!';   
        }else{
            if($chk[0]->status == 0){
                print 'Sorry '.$chk[0]->name.'!!! Your account is not activated yet !!!';
            }else{
                $this->cm->update('users', array("id" => $chk[0]->id), array("isLogin" => 1, "login_time" => date('Y-m-d H:i:s')));
                $this->session->set_userdata('UserId', $chk[0]->id);
                $this->session->set_userdata('UserName', $chk[0]->name);
                $this->session->set_userdata('UserType', $chk[0]->login_type);
                $this->session->set_userdata('AccountVerified', $chk[0]->account_verified);
                print 'ok';   
            }
        }
    }
    public function logOut(){
        $this->cm->update('users', array("id" => $this->session->userdata('UserId')), array("isLogin" => 0));
        $this->session->set_userdata('UserId', '');
        $this->session->set_userdata('UserName', '');
        $this->session->set_userdata('UserType', '');
        $this->session->set_userdata('AccountVerified', '');
        $this->session->unset_userdata('UserId');
        $this->session->unset_userdata('UserName');
        $this->session->unset_userdata('UserType');
        $this->session->unset_userdata('AccountVerified');
        redirect(base_url());
    }
}
