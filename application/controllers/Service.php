<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/CommonController.php';
class Service extends CommonController {
    public function index() {
        $this->checkAge();
        $this->checkLogin();
    }
    public function subscribe(){
        /*$credit = $this->db->query("select credit from users where id = '".$this->input->post('user_id')."'")->result();
        if($credit[0]->credit == 0){
            print 'notok~~Sorry !!! You\'ve No Credit to Subscribe !!!';
            die;
        }*/
        $chk = $this->cm->get_all('subscribe', array("user_id" => $this->input->post('user_id'), "performer_id" => $this->input->post('performer_id')));
        if(empty($chk)){
            $insertData = array(
                "user_id" => $this->input->post('user_id'),
                "performer_id" => $this->input->post('performer_id')
            );
            $this->cm->insert('subscribe', $insertData);
            print 'ok~~Successfully subscribed !!!~~Unsubscribe';
        }else{
            if($chk[0]->status == 0){
                $updateData = array(
                    "status" => 1,
                    "updated_at" => date('Y-m-d H:i:s')
                );
                $this->cm->update('subscribe', array("user_id" => $this->input->post('user_id'), "performer_id" => $this->input->post('performer_id')), $updateData);
                print 'ok~~Successfully subscribed !!!~~Unsubscribe';
            }else{
                $updateData = array(
                    "status" => 0,
                    "updated_at" => date('Y-m-d H:i:s')
                );
                $this->cm->update('subscribe', array("user_id" => $this->input->post('user_id'), "performer_id" => $this->input->post('performer_id')), $updateData);
                print 'ok~~Successfully un-subscribed !!!~~Subscribe';
            }
        }
    }
    public function vote(){
        if(empty($this->cm->get_all('vote', 
                                    array(
                                        "user_id" => $this->session->userdata('UserId'),
                                        "performer_id" => $this->input->post('performer_id')
                                    )))){
            $insertData = array(
                "user_id" => $this->session->userdata('UserId'),
                "performer_id" => $this->input->post('performer_id')
            );
            $this->cm->insert('vote', $insertData);
            print 'ok~~Thank you for your vote !!!';
        }else{
            print "notok~~You've already given vote to this performer !!!";
        }
    }
    public function subscriptionsList(){
        $this->checkAge();
        $this->checkLogin();
        $join = array();
        $join[] = ['table' => 'user_preference up', 'on' => 'up.user_id = s.performer_id', 'type' => 'left'];
        $join[] = ['table' => 'users u', 'on' => 's.performer_id = u.id', 'type' => 'left'];
        $data['subs'] = $this->cm->select('subscribe s', array('s.user_id' => $this->session->userdata('UserId'), 's.status' => 1), 'u.id, u.name, u.image, up.display_name, u.usernm', 's.id', 'desc', $join);
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/my_subscriptions');
        $this->load->view('frontend/layout/footer');
    }
    public function subsSuggestion(){
        $query = "select u.id, u.name, u.image, u.usernm, up.display_name from subscribe s left join user_preference up ON up.user_id = s.performer_id left join users u ON s.performer_id = u.id where s.user_id = '".$this->session->userdata('UserId')."' AND s.status = 1";
        if($this->input->post('subs_search') != 'tmp'){
            $query .= " AND (up.display_name LIKE '%".$this->input->post('subs_search')."%' OR u.name LIKE '%".$this->input->post('subs_search')."%')";
        }
        $query .= " order by s.id desc limit 10";
        $data['subs'] = $this->db->query($query)->result_array();
        if(!empty($data['subs'])){
            $html = $this->load->view('frontend/pages/ajax_load', $data, TRUE);
            print $html;
        }else{
            print '<h3 class="no-subs">No Such Performer Found !!!</h3>';
        }
    }
    public function awards(){
        $this->checkAge();
        $this->checkLogin();
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/awards');
        $this->load->view('frontend/layout/footer');
    }
    public function mySubscriptions(){
        $this->checkAge();
        $this->checkLogin();
        $join[] = ['table' => 'users u', 'on' => 's.user_id = u.id', 'type' => 'left'];
        $join[] = ['table' => 'user_preference up', 'on' => 'up.user_id = u.id', 'type' => 'left'];
        $data['mySubs'] = $this->cm->select('subscribe s', array('s.performer_id' => $this->session->userdata('UserId'), 's.status' => 1), 'u.id, u.name, u.image, up.display_name, u.usernm', 's.id', 'desc', $join);
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/my-subscriptions');
        $this->load->view('frontend/layout/footer');
    }
    public function myShows(){
        $this->checkAge();
        $this->checkLogin();
        if($this->session->userdata('UserType') == 1){
            $join[] = ['table' => 'users u', 'on' => 'vc.performer_id = u.id', 'type' => 'left'];
            $join[] = ['table' => 'user_preference up', 'on' => 'up.user_id = vc.performer_id', 'type' => 'left'];
            $data['myShows'] = $this->cm->select('video_chat vc', array('vc.user_id' => $this->session->userdata('UserId'), 'vc.status' => 2), 'u.id, u.name, u.image, up.display_name, u.usernm, vc.show_type, vc.created_at, vc.elapsed_time', 'vc.id', 'desc', $join);
        }else{
            $join[] = ['table' => 'users u', 'on' => 'vc.user_id = u.id', 'type' => 'left'];
            $join[] = ['table' => 'user_preference up', 'on' => 'up.user_id = u.id', 'type' => 'left'];
            $data['myShows'] = $this->cm->select('video_chat vc', array('vc.performer_id' => $this->session->userdata('UserId'), 'vc.status' => 2), 'u.id, u.name, u.image, up.display_name, u.usernm, vc.show_type, vc.created_at, vc.elapsed_time', 'vc.id', 'desc', $join);
        }
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/my-shows');
        $this->load->view('frontend/layout/footer');
    }
    
    public function content(){
        $this->checkAge();
        $this->checkLogin();
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/content');
        $this->load->view('frontend/layout/footer');
    }
    public function manageUsers(){
        $this->checkAge();
        $this->checkLogin();
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/manageUsers');
        $this->load->view('frontend/layout/footer');
    }
    public function financial(){
        $this->checkAge();
        $this->checkLogin();
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/financial');
        $this->load->view('frontend/layout/footer');
    }
    public function myNetwork(){
        $this->checkAge();
        $this->checkLogin();
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/myNetwork');
        $this->load->view('frontend/layout/footer');
    }
    public function loyalty(){
        $this->checkAge();
        $this->checkLogin();
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/loyalty');
        $this->load->view('frontend/layout/footer');
    }
    public function help(){
        $this->checkAge();
        $this->checkLogin();
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/help');
        $this->load->view('frontend/layout/footer');
    } 
}
