<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/CommonController.php';
class Chat extends CommonController {
    public function index() {
        $this->checkLogin();
        $sender = $this->db->query("select DISTINCT(sender_id) from chat where receiver_id = '".$this->session->userdata('UserId')."'")->result();
        $receiver = $this->db->query("select DISTINCT(receiver_id) from chat where sender_id = '".$this->session->userdata('UserId')."'")->result();
        $user = array();
        $userList = '';
        if(!empty($sender)){
            foreach($sender as $sndr){
                array_push($user, $sndr->sender_id);
                if($userList == ''){
                    $userList .= $sndr->sender_id;
                }else{
                    $userList .= ','.$sndr->sender_id;
                }
            }
        }
        if(!empty($receiver)){
            foreach($receiver as $rcvr){
                if (!in_array($rcvr->receiver_id, $user)){
                    array_push($user, $rcvr->receiver_id);
                    if($userList == ''){
                        $userList .= $rcvr->receiver_id;
                        }else{
                        $userList .= ','.$rcvr->receiver_id;
                        }
                }
            }
        }
        if(!empty($user)){
            $data['chatList'] = $this->db->query("SELECT u.id, u.name, u.image, u.usernm, up.display_name,(select msg from chat where sender_id = u.id OR receiver_id = u.id order by id desc limit 1) chat, (select created_at from chat where sender_id = u.id OR receiver_id = u.id order by id desc limit 1) time FROM `users` u LEFT JOIN user_preference up ON up.user_id = u.id WHERE u.id in (".$userList.")")->result();
            $html = $this->load->view('frontend/pages/ajax_load', $data, TRUE);
        }else{
            $html = '<span class="no-msg-avlabl">No Chat Available !!!</span>';
        }
        print($html);
    }
    public function fullChatDetails() {
        $this->checkLogin();
        $join[] = ['table' => 'user_preference up', 'on' => 'up.user_id = u.id', 'type' => 'left'];
        $data['sessImg'] = $this->cm->select('users u', array('u.id' => $this->session->userdata('UserId')), 'u.name, u.usernm, u.image, up.display_name', 'u.id', 'desc', $join);
        $data['usrImg'] = $this->cm->select('users u', array('u.id' => $this->input->post('user_id')), 'u.id, u.name, u.usernm, u.image, up.display_name', 'u.id', 'desc', $join);
        $data['fullChat'] = $this->cm->get_chat($this->session->userdata('UserId'), $this->input->post('user_id'));
        $html = $this->load->view('frontend/pages/ajax_load', $data, TRUE);
        print($html);
    }
    public function deleteChat() {
        $this->checkLogin();
        $this->db->query("DELETE FROM `chat` WHERE (sender_id = '".$this->session->userdata('UserId')."' AND receiver_id = '".$this->input->post('user_id')."') OR (receiver_id = '".$this->session->userdata('UserId')."' AND sender_id = '".$this->input->post('user_id')."')");
    }
    public function sendChat(){
        if($this->input->post('chat_msg')){
            $insertData = array(
                "sender_id" => $this->input->post('sender_id'),
                "sender_type" => $this->input->post('sender_type'),
                "receiver_id" => $this->input->post('receiver_id'),
                "receiver_type" => $this->input->post('receiver_type'),
                "msg" => $this->input->post('chat_msg')
            );
            $chat_id = $this->cm->insert('chat', $insertData);
            $join[] = ['table' => 'user_preference up', 'on' => 'up.user_id = u.id', 'type' => 'left'];
            $data['sndrImg'] = $this->cm->select('users u', array('u.id' => $this->input->post('sender_id')), 'u.id, u.name, u.usernm, u.image, up.display_name', 'u.id', 'desc', $join);
            $data['msg'] = $this->input->post('chat_msg');
            $html = $this->load->view('frontend/pages/ajax_load', $data, TRUE);
            print $chat_id.'~~'.$html;
        }
    }
    public function checkNewMsg() {
        $this->checkLogin();
        $chat = $this->db->query("select * from chat where ((sender_id = '".$this->session->userdata('UserId')."' AND receiver_id = '".$this->input->post('rec_id')."') OR (receiver_id = '".$this->session->userdata('UserId')."' AND sender_id = '".$this->input->post('rec_id')."')) AND id > '".$this->input->post('last_chat_id')."' order by id ASC")->result();        
        if(!empty($chat)){
            $data['newChat'] = $chat;
            $html = $this->load->view('frontend/pages/ajax_load', $data, TRUE);
            $data['newChat'] = array();
            $data['newChatTwo'] = $chat;
            $join[] = ['table' => 'user_preference up', 'on' => 'up.user_id = u.id', 'type' => 'left'];
            $data['sessImg'] = $this->cm->select('users u', array('u.id' => $this->session->userdata('UserId')), 'u.name, u.usernm, u.image, up.display_name', 'u.id', 'desc', $join);
            $data['usrImg'] = $this->cm->select('users u', array('u.id' => $this->input->post('rec_id')), 'u.id, u.name, u.usernm, u.image, up.display_name', 'u.id', 'desc', $join);
            $htmlTwo = $this->load->view('frontend/pages/ajax_load', $data, TRUE);
            $last_chat_id = '';
            for($i=0; $i<count($chat); $i++){
                $last_chat_id = $chat[$i]->id;
            }
        }else{
            $html = '';
            $htmlTwo = '';
            $last_chat_id = '';
        }
        print $last_chat_id.'~~'.$html.'~~'.$htmlTwo;
    }
    public function searchUser() {
        $this->checkLogin();
        $usrnm = $this->db->query("select u.id, u.name, up.display_name, u.image, u.usernm from users u left join user_preference up on up.user_id = u.id where (u.name LIKE  '%".$this->input->post('user_sugg')."%' OR u.usernm LIKE '%@".$this->input->post('user_sugg')."%' OR up.display_name LIKE '%".$this->input->post('user_sugg')."%') AND u.status = '1' AND u.login_type = '2' order by u.id desc limit 10")->result();
        if(!empty($usrnm)){
            $data['userSugg'] = $usrnm;
            $html = $this->load->view('frontend/pages/ajax_load', $data, TRUE);
        }else{
            $html = '';
        }
        print $html;
    }
}
