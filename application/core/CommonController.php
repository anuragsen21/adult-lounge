<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CommonController extends CI_Controller {
	private $data = array();
	function __construct() {
     	parent::__construct();
        //$this->load->library('encrypt');//load this library.
        /*$this->data = array(
            'menu' => 'CommonController'
        );*/
	}
    public function checkAge(){
        if(!$this->session->userdata('setAge')){
            redirect(base_url());
        }
    }
    public function checkLogin(){
        if(!$this->session->userdata('UserId')){
            redirect(base_url('login'));
        }
    }
    public function getUserDetails($id = ''){
        $join[] = ['table' => 'user_preference up', 'on' => 'up.user_id = u.id', 'type' => 'left'];
        $user = $this->cm->select('users u', array('u.id' => $id), 'u.id, u.name, u.email, u.phone_no, u.usernm, u.gender, u.sexual_pref, u.age, u.image, u.address_one, u.address_two, u.city, u.country, u.pincode, u.dob, u.i_am_a, u.us_citizen, u.isLogin, up.height, up.weight, up.hair, up.eye, up.zodiac, up.build, up.chest, up.pubic_hair, up.penis, up.description, up.burst, up.cup, up.display_name, up.category, up.attribute, up.willingness, up.appearance, up.feature, up.receiveEmail, up.allowContact, up.saveHistory, up.maxCredit, up.creditLimit, up.blockMessage, (select GROUP_CONCAT(pg.image) from performer_gallery pg where pg.user_id = u.id) images', 'u.id', 'desc', $join);        
        return $user;
    }
    public function getPerformerDetails($isVerified = '', $id = '', $type = '', $checkId = ''){
        $condition = array(
            'u.login_type'          => '2',
            'u.status'              => '1'
        );
        if($isVerified != ''){
            $condition['u.account_verified'] = 'Yes';
        }
        if($id != ''){
            $condition['u.id'] = $id;
        }
        if($type == '' && $checkId != ''){
            $condition['u.age'] = $checkId;
        }
        if($type != '' && $checkId != ''){
            $condition['up.'.$type.' LIKE '] = '%'.$checkId.'%';
        }
        $join[] = ['table' => 'user_preference up', 'on' => 'up.user_id = u.id', 'type' => 'left'];        
        $performer = $this->cm->select('users u', $condition, 'u.id, u.name, u.email, u.phone_no, u.usernm, u.gender, u.sexual_pref, u.age, u.image, u.isLogin, up.display_name, up.height, up.weight, up.hair, up.eye, up.zodiac, up.build, up.chest, up.burst, up.cup, up.pubic_hair, up.penis, up.description, up.category, up.attribute, up.willingness, up.appearance, up.feature, (select GROUP_CONCAT(pg.image) from performer_gallery pg where pg.user_id = u.id) images', 'u.id', 'desc', $join);
        return $performer;
    }
    public function getCommonMenu(){
        $menu['show'] = $this->cm->get_specific('show_type', array("status" => 1));
        $menu['service'] = $this->cm->get_specific('services', array("status" => 1));
        $menu['age'] = $this->db->query("select distinct age from users where login_type = '2' ORDER BY age ASC")->result();
        $menu['categories'] = $this->cm->get_specific('categories', array("status" => 1));
        $menu['will'] = $this->cm->get_specific('willingness', array("status" => 1));
        $menu['appearence'] = $this->cm->get_specific('appearence', array("status" => 1));
        return $menu;
    }
    public function getWalletAmonut($id = ''){
        return $this->cm->get_all('wallet', array('user_id' => $this->session->userdata('UserId')));
    }
    public function getVoteDetails($id = ''){
        $vote = array(
                    "rank" => 0,
                    "vote" => 0,
                );
        //$voting = $this->db->query('SELECT DISTINCT performer_id, count(id) vote FROM `vote` ORDER BY vote DESC')->result();
        $voting = $this->db->query('SELECT DISTINCT v.performer_id, (select count(vt.id) from vote vt where vt.performer_id = v.performer_id) vote FROM `vote` v ORDER BY vote DESC')->result();
        if(!empty($voting)){
            $rank = 1;
            foreach($voting as $vot){
                if($id == $vot->performer_id){
                    $vote = array(
                                "rank" => $rank,
                                "vote" => $vot->vote,
                            );
                    break;
                }
                $rank++;
            }
        }
        return $vote;
    }
    public function commonFileUpload($path = '', $imageName = '', $imageInputName = '', $oldImage = ''){
        $pro_image = '';
        $upPath = FCPATH . $path;
        if (!file_exists($upPath)) {
            mkdir($upPath, 0777, true);
        }
        $config = array(
            'upload_path' => $upPath,
            'allowed_types' => "gif|jpg|png|jpeg|JPEG|JPG|GIF|PNG",
            'overwrite' => TRUE,
            'max_size' => "8192000",
            /*'max_height' => "1536",
            'max_width' => "2048",*/
            'encrypt_name' => TRUE
        );
        $config['file_name'] = time().$imageName;
        $this->upload->initialize($config);
        $this->load->library('upload', $config);
        if ($this->upload->do_upload($imageInputName)) {
            $imageDetailArray = $this->upload->data();
            $pro_image = $imageDetailArray['file_name'];
            if($oldImage != ''){
                if (file_exists($upPath.$oldImage)) {
                    unlink($upPath.$oldImage);
                }
            }
        }else{
            $res = $this->upload->display_errors();
        }
        return $pro_image;
    }
}
?>
