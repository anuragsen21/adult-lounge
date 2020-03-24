<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/CommonController.php';
class Home extends CommonController {
    public function index() {
        if($this->session->userdata('setAge')){
            if($this->session->userdata('UserType') && $this->session->userdata('UserType') == '2'){
                $data['header'] = 'two';
                $data['user'] = $this->getPerformerDetails('', $this->session->userdata('UserId'));
                $data['vote'] = $this->getVoteDetails($this->session->userdata('UserId'));
                $join = array();
                $join[] = ['table' => 'users u', 'on' => 'u.id = s.user_id', 'type' => 'left'];
                $join[] = ['table' => 'user_preference up', 'on' => 'up.user_id = u.id', 'type' => 'left'];
                $data['newSubs'] = $this->cm->select('subscribe s', array('s.performer_id' => $this->session->userdata('UserId')), 'u.name, u.usernm, u.image, up.display_name', 's.id', 'desc', $join, '10', '0');
                $join = array();
                $join[] = ['table' => 'users u', 'on' => 'vc.user_id = u.id', 'type' => 'left'];
                $join[] = ['table' => 'user_preference up', 'on' => 'up.user_id = u.id', 'type' => 'left'];
                $data['showHistory'] = $this->cm->select('video_chat vc', array('vc.performer_id' => $this->session->userdata('UserId')), 'vc.user_id, vc.elapsed_time, vc.show_type, vc.created_at, u.image, u.name, u.usernm, up.display_name', 'vc.id', 'desc', $join, '10', '0');
            }else{
                $data['header'] = 'one';
                $data['performer'] = $this->getPerformerDetails('Yes');
                $data['user'] = $this->getUserDetails($this->session->userdata('UserId'));
            }
            $menu = $this->getCommonMenu();            
            $data['show'] = $menu['show'];
            $data['service'] = $menu['service'];
            $data['age'] = $menu['age'];
            $data['categories'] = $menu['categories'];
            $data['will'] = $menu['will'];
            $data['appearence'] = $menu['appearence'];            
            $this->load->view('frontend/layout/header', $data);
            $this->load->view('frontend/pages/index');
            $this->load->view('frontend/layout/footer');
        }else{
            $data['header'] = 'two';
            $this->load->view('frontend/layout/header');
            $this->load->view('frontend/pages/startup');
            $this->load->view('frontend/layout/footer');
        }
    }
    public function profile(){
        $this->checkAge();
        $this->checkLogin();
        $data['user'] = $this->getUserDetails($this->session->userdata('UserId'));
        $menu = $this->getCommonMenu();            
        $data['show'] = $menu['show'];
        $data['service'] = $menu['service'];
        $data['categories'] = $menu['categories'];
        $data['will'] = $menu['will'];
        $data['appearence'] = $menu['appearence'];
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/profile');
        $this->load->view('frontend/layout/footer');
    }
    public function profileUpdate(){
        $unm_chk = $this->db->query("select id from users where usernm = '@".$this->input->post('usernm_edit')."' AND id != '".$this->input->post('editpro_id')."'")->result();
        if(!empty($unm_chk)){
            print 'notok~~Sorry !!! Username Already Exists!!!';
            die;
        }
        $pro_image = '';
        $files = $_FILES;
        if (!empty($files) && $files['editpro_image']['name'] != '') {
            $pro_image = $this->commonFileUpload('assets/profile_image/', $files['editpro_image']['name'], 'editpro_image', $this->input->post('old_editpro_image'));
        }else{
            $pro_image = $this->input->post('old_editpro_image');
        }
        $updateArray = array(
            'name' => $this->input->post('name_edit'),
            'usernm' => '@'.$this->input->post('usernm_edit'),
            'sexual_pref' => $this->input->post('editpro_sexual_pref'),
            'age' => $this->input->post('editpro_age'),
            'image' => $pro_image,
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->cm->update('users', array("id" => $this->input->post('editpro_id')), $updateArray);
        $this->session->set_userdata('UserName', $this->input->post('name_edit'));
        $category = $this->input->post('editpro_category');
        $attribute = $this->input->post('editpro_attr');
        $feature = $this->input->post('editpro_ftr');
        $willing = $this->input->post('editpro_will');
        $appearence = $this->input->post('editpro_aprnc');
        $cat = '';
        $attr = '';
        $featr = '';
        $will = '';
        $apnc = '';
        if(!empty($category)){
            foreach($category as $categry){
                $cat .= $categry.',';
            }
        }
        if(!empty($attribute)){
            foreach($attribute as $attribut){
                $attr .= $attribut.',';
            }
        }
        if(!empty($feature)){
            foreach($feature as $featur){
                $featr .= $featur.',';
            }
        }
        if(!empty($willing)){
            foreach($willing as $willin){
                $will .= $willin.',';
            }
        }
        if(!empty($appearence)){
            foreach($appearence as $aprnc){
                $apnc .= $aprnc.',';
            }
        }
        $cat = trim($cat, ",");
        $attr = trim($attr, ",");
        $featr = trim($featr, ",");
        $will = trim($will, ",");
        $apnc = trim($apnc, ",");
        $updateArrayTwo = array(
            'display_name'  => $this->input->post('display_name_edit'),
            'height'        => $this->input->post('editpro_height'),
            'weight'        => $this->input->post('editpro_weight'),
            'hair'          => $this->input->post('editpro_hair'),
            'eye'           => $this->input->post('editpro_eye'),
            'zodiac'        => $this->input->post('editpro_zodiac'),
            'build'         => $this->input->post('editpro_build'),
            'chest'         => $this->input->post('editpro_chest'),
            'burst'         => $this->input->post('editpro_burst'),
            'cup'           => $this->input->post('editpro_cup'),
            'pubic_hair'    => $this->input->post('editpro_pubic_hair'),
            'penis'         => $this->input->post('editpro_penis'),
            'description'   => $this->input->post('editpro_description'),
            'category'      => $cat,
            'attribute'     => $attr,
            'willingness'   => $will,
            'appearance'    => $apnc,
            'feature'       => $featr
        );
        $chkTwo = $this->cm->get_all('user_preference', array("user_id" => $this->input->post('editpro_id')));
        if($this->session->userdata('UserType') == 2 && count($files['gallery']['name'])> 0){
            $upPathTwo = FCPATH . 'assets/performer_gallery/';
            if (!file_exists($upPathTwo)) {
                mkdir($upPathTwo, 0777, true);
            }
            $config = array(
                'upload_path' => $upPathTwo,
                'allowed_types' => "gif|jpg|png|jpeg|JPEG|JPG|GIF|PNG",
                'overwrite' => TRUE,
                'max_size' => "8192000",
                'max_height' => "1536",
                'max_width' => "2048",
                'encrypt_name' => TRUE
            );
            for($p = 0; $p<count($files['gallery']['name']); $p++){
                if($files['gallery']['name'][$p] !='' ){
                    $_FILES['file']['name']=$files['gallery']['name'][$p];
                    $_FILES['file']['type']=$files['gallery']['type'][$p];
                    $_FILES['file']['tmp_name']=$files['gallery']['tmp_name'][$p];
                    $_FILES['file']['error']=$files['gallery']['error'][$p];
                    $_FILES['file']['size']=$files['gallery']['size'][$p];
                    $config['file_name']=time().$files['gallery']['name'][$p];
                    $this->upload->initialize($config);
                    if($this->upload->do_upload('file')){
                        $imageDetailArray = $this->upload->data();
                        $this->cm->insert('performer_gallery', array("user_id" => $this->input->post('editpro_id'), "image" => $imageDetailArray['file_name']));
                    }
                }
            }
        }
        if(empty($chkTwo)){
            $updateArrayTwo['user_id'] = $this->input->post('editpro_id');
            $this->cm->insert('user_preference', $updateArrayTwo);
        }else{
            $updateArrayTwo['updated_at'] = date('Y-m-d H:i:s');
            $this->cm->update('user_preference', array("user_id" => $this->input->post('editpro_id')), $updateArrayTwo);
        }
        print 'ok~~Profile Details Updated Successfully!!!';
    }
    public function personalDetails(){
        $this->checkAge();
        $this->checkLogin();
        if($this->input->post('editpro_name')){
            $chk = $this->db->query("select id from users where email = '".$this->input->post('editpro_email')."' AND id != '".$this->session->userdata('UserId')."'")->result();
            if(empty($chk)){
                $updateArray = array(
                    'name'          => $this->input->post('editpro_name'),
                    'email'         => $this->input->post('editpro_email'),
                    'phone_no'      => $this->input->post('editpro_phone'),
                    'gender'        => $this->input->post('editpro_gender'),
                    'address_one'   => $this->input->post('editpro_address'),
                    'pincode'       => $this->input->post('editpro_pin'),
                    'updated_at'    => date('Y-m-d H:i:s')
                );
                if($this->input->post('editpro_pwd') != ''){
                    $updateArray['login_password'] = md5($this->input->post('editpro_pwd'));
                }
                if($this->input->post('editpro_card') != ''){
                    $updateArray['card_no'] = $this->input->post('editpro_card');
                    $updateArray['card_month'] = $this->input->post('editpro_cardm');
                    $updateArray['card_year'] = $this->input->post('editpro_cardy');
                    $updateArray['card_cvv'] = $this->input->post('editpro_card_cvv');
                }
                $this->cm->update('users', array("id" => $this->session->userdata('UserId')), $updateArray);
                print 'Personal Details Updated Successfully!!!';
                die;
            }else{
                print 'Sorry!!! Email already exists !!!';
                die;
            }
        }
        $data['user'] = $this->getUserDetails($this->session->userdata('UserId'));
        $data['wallet'] = $this->getWalletAmonut($this->session->userdata('UserId'));
        $join = array();
        $join[] = ['table' => 'user_preference up', 'on' => 'up.user_id = s.performer_id', 'type' => 'left'];
        $join[] = ['table' => 'users u', 'on' => 's.performer_id = u.id', 'type' => 'left'];
        $data['subs'] = $this->cm->select('subscribe s', array('s.user_id' => $this->session->userdata('UserId'), 's.status' => 1), 'u.id, u.name, u.image, up.display_name, u.usernm', 's.id', 'desc', $join, '5', '0');
        $join = array();
        $join[] = ['table' => 'user_preference up', 'on' => 'up.user_id = vc.performer_id', 'type' => 'left'];
        $join[] = ['table' => 'users u', 'on' => 'vc.performer_id = u.id', 'type' => 'left'];
        $data['history'] = $this->cm->select('video_chat vc', array('vc.user_id' => $this->session->userdata('UserId'), 'vc.status' => 2), 'u.id, u.name, u.image, up.display_name, u.usernm', 'vc.id', 'desc', $join, '5', '0');
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/personal_details');
        $this->load->view('frontend/layout/footer');
    }
    public function verification(){
        if($this->input->post('verify_name')){
            $chk = $this->cm->get_all('user_verification', array('user_id' => $this->session->userdata('UserId')));
            if(empty($chk)){
                $chktwo = $this->db->query("select id from users where email = '".$this->input->post('verify_email')."' AND id != '".$this->session->userdata('UserId')."'")->result();
                if(empty($chktwo)){
                    $updateData = array(
                        "name"          => $this->input->post('verify_full_name'),
                        "email"         => $this->input->post('verify_email'),
                        "phone_no"      => $this->input->post('verify_phone'),
                        "dob"           => $this->input->post('verify_dob'),
                        "address_one"   => $this->input->post('verify_add_one'),
                        "address_two"   => $this->input->post('verify_add_two'),
                        "city"          => $this->input->post('verify_city'),
                        "pincode"       => $this->input->post('verify_pincode'),
                        "country"       => $this->input->post('verify_country'),
                        "i_am_a"        => $this->input->post('i_am_a'),
                        "us_citizen"    => $this->input->post('us_citizen')
                    );
                    $this->cm->update('users', array("id" => $this->session->userdata('UserId')), $updateData);
                }else{
                    print 'notok~~Sorry !!! Email already exists !!!';
                    die;
                }
                $insertData = array();
                $files = $_FILES;
                for($g=0; $g<2; $g++ ){
                    if($g==0){
                        $nm='verify_pic';
                    }else{
                        $nm='verify_pic_id';
                    }
                    if (!empty($files) && $files[$nm]['name'] !='' ) {
                        $insertData[$nm] = $this->commonFileUpload('assets/verify_image/', $files[$nm]['name'], $nm);
                    }
                }
                $insertData['user_id'] = $this->session->userdata('UserId');
                $insertData['name'] = $this->input->post('verify_name');
                $insertData['verify_date'] = $this->input->post('verify_year').'-'.$this->input->post('verify_month').'-'.$this->input->post('verify_day');
                $this->cm->insert('user_verification', $insertData);
                print 'ok~~Verification Details Successfully Uploaded !!!';
                die;
            }else{
                print 'ok~~Verification Details Already Uploaded & Yet to be Approved !!!';
                die;
            }
        }
        $this->checkAge();
        $this->checkLogin();
        $data['user'] = $this->getUserDetails($this->session->userdata('UserId'));
        $data['countries'] = $this->cm->get_all('add_countries', array("status" => 1));
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/verification');
        $this->load->view('frontend/layout/footer');
    }
    public function viewProfile($id = '', $nm = ''){
        $this->checkAge();
        $this->checkLogin();
        $data['user'] = $this->getUserDetails($id);
        if(!empty($data['user'])){
            if($data['user'][0]['willingness'] != ''){
                $will = $this->db->query("select GROUP_CONCAT(w.name) will from willingness w where w.id IN (".$data['user'][0]['willingness'].")")->result_array();
            }
            if($data['user'][0]['attribute'] != ''){
                $attr = $this->db->query("select GROUP_CONCAT(st.name) attr from show_type st where st.id IN (".$data['user'][0]['attribute'].")")->result_array();
            }
            if(!empty($will)){
                $data['user'][0]['willingness'] = $will[0]['will'];
            }
            if(!empty($attr)){
                $data['user'][0]['attribute'] = $attr[0]['attr'];
            }
        }
        $data['chat'] = $this->cm->get_chat($this->session->userdata('UserId'), $id);
        if($this->session->userdata('UserType') == 1){            
            $data['vote'] = $this->getVoteDetails($id);
        }
        $data['subs'] = $this->cm->get_all('subscribe', array("user_id" => $this->session->userdata('UserId'), "performer_id" => $id));
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/view_profile');
        $this->load->view('frontend/layout/footer');
    }
    public function filterPerformer(){
        if($this->input->post('type') == 'age'){
            $data['performer'] = $this->getPerformerDetails('Yes', '', '', $this->input->post('id'));
        }else{
            $data['performer'] = $this->getPerformerDetails('Yes', '', $this->input->post('type'), $this->input->post('id'));
        }
        if(!empty($data['performer'])){
            $html = $this->load->view('frontend/pages/ajax_load', $data, TRUE);
            $html = 'ok~~'.$html;
        }else{
            $html = 'notok~~No Such Performer Found !!!';
        }
        print $html;
    }
    public function accountSettings(){
        $this->checkAge();
        $this->checkLogin();
        $updateArray = array(
            "receiveEmail"  => $this->input->post('ac_email'),
            "allowContact"  => $this->input->post('ac_contact'),
            "saveHistory"   => $this->input->post('ac_history'),
            "maxCredit"     => $this->input->post('ac_credit'),
            "blockMessage"  => $this->input->post('ac_block'),
            "updated_at"    => date('Y-m-d H:i:s')
        );
        if($this->input->post('ac_credit') == 'Y'){
            $updateArray['creditLimit'] = $this->input->post('ac_maxcrdt');
        }
        $this->cm->update('user_preference', array("user_id" => $this->session->userdata('UserId')), $updateArray);
        print 'Details Updated Successfully !!!';
    }
    public function dashBoard(){
        //$this->checkAge();
        //$this->checkLogin();
        $data['header'] = 'two';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('frontend/pages/dashboard');
        $this->load->view('frontend/layout/footer');
    }
}
