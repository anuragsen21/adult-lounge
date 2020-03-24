<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/CommonController.php';
class Videochat extends CommonController {
    public function index() {
        $this->checkAge();
        $this->checkLogin();
        $data['chat'] = $this->db->query("select c.id, c.sender_id, u.usernm sender_unm, c.receiver_id, up.usernm receiver_unm, c.msg, c.created_at from chat c left JOIN users u ON u.id = c.sender_id left JOIN users up ON up.id = c.receiver_id where (c.sender_id = '".$this->session->userdata('UserId')."' AND c.receiver_id = '".$this->session->userdata('vcPerformerId')."') OR (c.sender_id = '".$this->session->userdata('vcPerformerId')."' AND c.receiver_id = '".$this->session->userdata('UserId')."') order by c.id ASC")->result();
        $data['subs'] = $this->cm->get_all('subscribe', array("user_id" => $this->session->userdata('UserId'), "performer_id" => $this->session->userdata('vcPerformerId')));
        $data['usrnm'] = $this->db->query("select u.name, up.display_name from users u left join user_preference up on up.user_id = u.id where u.id = '".$this->session->userdata('UserId')."'")->result();
        $data['header'] = 'two';        
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/video_chat');
        $this->load->view('frontend/layout/footer');
    }
    public function videoChatStart(){
        $this->checkLogin();
        $chk = $this->cm->get_specific('users', array("id" => $this->input->post('performer_id')));
        $chktwo = $this->cm->get_specific('users', array("id" => $this->input->post('performer_id')));
        //$join[] = array();
        //$chktwo = $this->cm->select('video_chat vc', array('vc.performer_id' => $this->input->post('performer_id'), "status" => '1'), 'vc.id', 'vc.id', 'asc', $join);
        $chktwo = $this->db->query("select id from video_chat where performer_id = '".$this->input->post('performer_id')."' AND (status = '0' OR status = '1') order by id asc limit 0, 1")->result();
        if($chk[0]->isLogin == '1' && $chk[0]->hasWebcam == 'Y'){
            if(empty($chktwo)){
                $insertArray = array(
                    "user_id"       => $this->session->userdata('UserId'),
                    "performer_id"  => $this->input->post('performer_id'),
                    "url_hash"      => $this->input->post('url_hash')
                );
                $chat_id = $this->cm->insert('video_chat', $insertArray);
                $this->session->set_userdata('vcPerformerId', $this->input->post('performer_id'));
                $this->session->set_userdata('vcPerformerId', $this->input->post('performer_id'));
                $this->session->set_userdata('vcChatId', $chat_id);
                print 'ok';
            }else{
                print 'busy';
            }
        }else{
            print 'notok';
        }
    }
    public function checkNewVideoChatRequest(){
        $this->checkLogin();
        $chk = $this->cm->get_all('video_chat', array("performer_id" => $this->input->post('performer_id'), "status" => '0'), '1', '0');
        if(!empty($chk)){
            $usrnm = $this->db->query("select u.id, u.name, up.display_name from users u left join user_preference up on up.user_id = u.id where u.id = '".$chk[0]->user_id."'")->result();
            if($usrnm[0]->display_name != ''){
                $nm = $usrnm[0]->display_name;
            }else{
                $nm = $usrnm[0]->name;
            }
            print $chk[0]->url_hash.'~~'.$nm.'~~'.$usrnm[0]->id;
        }else{
            print 'no-request';
        }
    }
    public function cancelVideoChat(){
        $this->checkLogin();
        $this->cm->update('video_chat', array("performer_id" => $this->input->post('performer_id'), "url_hash" => $this->input->post('url_hash'), "user_id" => $this->input->post('user_id')), array("elapsed_time" => '0', "status" => '3'));
    }
    public function acceptVideoChat(){
        $this->checkLogin();
        $this->session->set_userdata('vcUserId', $this->input->post('user_id'));        
        $this->cm->update('video_chat', array("performer_id" => $this->input->post('performer_id'), "url_hash" => $this->input->post('url_hash'), "user_id" => $this->input->post('user_id')), array("status" => '1'));
    }
    public function checkVideoChatStatus(){
        $this->checkLogin();
        $chk = $this->cm->get_specific('video_chat', array("id" => $this->input->post('chat_id')));
        if($chk[0]->status == '3'){
            $usrnm = $this->db->query("select u.name, up.display_name from users u left join user_preference up on up.user_id = u.id where u.id = '".$chk[0]->performer_id."'")->result();
            if($usrnm[0]->display_name != ''){
                $nm = $usrnm[0]->display_name;
            }else{
                $nm = $usrnm[0]->name;
            }
            print 'notok~~'.$chk[0]->performer_id.'~~'.strtolower(str_replace(' ', '_', $nm));
        }
    }
    public function checkVideoChatStatusPerformer(){
        $this->checkLogin();
        $chk = $this->cm->get_specific('video_chat', array("performer_id" => $this->input->post('performer_id'), "url_hash" => $this->input->post('url_hash'), "user_id" => $this->input->post('user_id')));
        if($chk[0]->status == '2'){
            print 'ok';
        }
    }
    public function hangupVideoChat(){
        $this->checkLogin();
        $tmp = $this->input->post('elapsedTime');
        $time = floor(((int)$tmp)/60) .' min '. (((int)$tmp)%60). ' sec';
        $this->cm->update('video_chat', array("id" => $this->input->post('chat_id')), array("elapsed_time" => $time, "status" => '2'));
        $usrnm = $this->db->query("select u.name, up.display_name from users u left join video_chat vc on vc.performer_id = u.id left join user_preference up on up.user_id = u.id where vc.performer_id = '".$this->input->post('vcPerformerId')."'")->result();
        if($usrnm[0]->display_name != ''){
            $nm = $usrnm[0]->display_name;
        }else{
            $nm = $usrnm[0]->name;
        }
        print $this->input->post('vcPerformerId').'~~'.strtolower(str_replace(' ', '_', $nm));
    }
    public function vcSendChat(){
        $this->checkLogin();
        $insertData = array(
            "sender_id"     => $this->input->post('sender_id'),
            "sender_type"   => $this->input->post('sender_type'),
            "receiver_id"   => $this->input->post('receiver_id'),
            "receiver_type" => $this->input->post('receiver_type'),
            "msg"           => $this->input->post('chat_msg')
        );
        $chat_id = $this->cm->insert('chat', $insertData);
        $usrnm = $this->cm->get_specific('users', array("id" => $this->input->post('sender_id')));
        print $chat_id.'~~'.$usrnm[0]->usernm;
    }
    public function vcCheckNewText(){
        $this->checkLogin();
        $vcNewChat = $this->db->query("select c.id, c.sender_id, u.usernm sender_unm, c.receiver_id, up.usernm receiver_unm, c.msg, c.created_at from chat c left JOIN users u ON u.id = c.sender_id left JOIN users up ON up.id = c.receiver_id where ((c.sender_id = '".$this->input->post('receiver_id')."' AND c.receiver_id = '".$this->input->post('sender_id')."') OR (c.sender_id = '".$this->input->post('sender_id')."' AND c.receiver_id = '".$this->input->post('receiver_id')."')) AND c.id > '".$this->input->post('last_id')."' order by c.id ASC")->result();
        if(!empty($vcNewChat)){
            $data['vcNewChat'] = $vcNewChat;
            $html = $this->load->view('frontend/pages/ajax_load', $data, TRUE);
            $last_chat_id = '';
            for($i=0; $i<count($vcNewChat); $i++){
                $last_chat_id = $vcNewChat[$i]->id;
            }
        }else{
            $html = '';
            $last_chat_id = '';
        }
        print $last_chat_id.'~~'.$html;
    }
    public function checkWebcamPerformer(){
        $this->checkLogin();
        $this->cm->update('users', array("id" => $this->input->post('performer_id')), array("hasWebcam" => $this->input->post('hasCamera')));
    }
}
