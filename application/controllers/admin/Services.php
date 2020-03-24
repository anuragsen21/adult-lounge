<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Services extends CI_Controller {
    function __construct() {
        parent::__construct();
        if(!$this->session->userdata('user_logged_in')){
            redirect(base_url().'admin');
        }
        $this->data = array(
            'menu' => 'Service'
        );
        $this->userId = $this->session->userdata('user_logged_in')['id'];
    }
    /*
        Service listing
    */
    public function index() {  
        $data = $this->data;
        $data['sub_menu'] = 'service_list';
        $data['title'] = 'Admin | Services';
        $data['data_list'] = $this->cm->select('services', [], '', 'id', 'desc');        
        $this->load->view('admin/services', $data);
    }
    /*  
    Add Edit Service
        */
    public function add_service(){
        $mode = $this->input->get('mode');
        if(!$mode){
            redirect('admin/services');
        }
        $data = $this->data;
        $data['sub_menu'] = 'service_list';
        $data['title'] = 'Admin | Services';        
        $edit_id = $this->input->get('id');
        if($edit_id){            
            $data['edit_data'] = $this->cm->select_row('services', ['id' => $edit_id], '');
        }
        $data['post_attr'] = ['mode'=> $mode, 'id'=> $edit_id];
        $this->load->view('admin/add_service', $data);
    }
    /*
        Save and Update service
        */
    public function save_service_data(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $mode = $this->input->post('mode');
            $service_name = addslashes($this->input->post('servicename'));            
            if($mode == 'add'){
                
                if($this->cm->select_row('services', ['LOWER(name)' => strtolower($service_name)], 'id')){         
                    echo json_encode(array('success'=> false, 'message'=> $service_name. ' already exist.')); die();
                } else {
                    $in_array = [
                        'name' => ucwords($service_name)
                    ];
                    $insert_id = $this->cm->insert('services', $in_array);
                    if($insert_id){
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully added service')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Faild to added service')); die();
                    }
                }
            }
            if($mode == 'edit'){
                $id = $this->input->post('id');
                if ($this->cm->select_row('services', ['LOWER(name)' => strtolower($service_name), 'id !=' => $id], 'id')) {
                    echo json_encode(array('success'=> false, 'message'=> $service_name. ' already exist.')); die();
                } else {
                    $up_array = [
                        'name' => ucwords($service_name)
                    ];
                    $up_status = $this->cm->update('services', ['id'=> $id], $up_array); 
                    if($up_status){
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully update service')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Nothing to change, please try again.')); die();
                    }
                }
            }
        }
    }
    /*
        Cetegory listing
        */
    public function category_listing() {    
        $data = $this->data = array(
            'menu' => 'category_list'
        );
        $data['sub_menu'] = 'category_list';
        $data['title'] = 'Admin | Categories';
        $data['data_list'] = $this->cm->select('categories', [], '', 'id', 'desc');        
        $this->load->view('admin/categories', $data);
    }
    /*
        Add Edit Category
    */
    public function add_category(){
        $mode = $this->input->get('mode');
        if(!$mode){
            redirect('admin/categories');
        }
        $data = $this->data = array(
            'menu' => 'category_list'
        );
        $data['sub_menu'] = 'category_list';
        $data['title'] = 'Admin | Categories';        
        $edit_id = $this->input->get('id');
        if($edit_id){            
            $data['edit_data'] = $this->cm->select_row('categories', ['id' => $edit_id], '');
        }
        $data['post_attr'] = ['mode'=> $mode, 'id'=> $edit_id];
        $this->load->view('admin/add_category', $data);
    }
    /*
        Save and Update Category
        */
    public function save_category_data(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $mode = $this->input->post('mode');
            $category_name = addslashes($this->input->post('servicename'));            
            if($mode == 'add'){
                
                if($this->cm->select_row('categories', ['LOWER(name)' => strtolower($category_name)], 'id')){         
                    echo json_encode(array('success'=> false, 'message'=> $category_name. ' already exist.')); die();
                } else {
                    $in_array = [
                        'name' => ucwords($category_name)
                    ];
                    $insert_id = $this->cm->insert('categories', $in_array);
                    if($insert_id){
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully added category')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Faild to added category')); die();
                    }
                }
            }
            
            if($mode == 'edit'){
                
                $id = $this->input->post('id');
                if ($this->cm->select_row('categories', ['LOWER(name)' => strtolower($category_name), 'id !=' => $id], 'id')) {
                    echo json_encode(array('success'=> false, 'message'=> $category_name. ' already exist.')); die();
                } else {
                    $up_array = [
                        'name' => ucwords($category_name)
                    ];
                    $up_status = $this->cm->update('categories', ['id'=> $id], $up_array); 
                    if($up_status){
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully update category')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Nothing to change, please try again.')); die();
                    }
                }
            }
        }
    }
    public function change_status(){
        if($this->input->post()){
            $id = $this->input->post('id');
            $table = $this->input->post('table');
            if($table == 'user_verification'){
                $status = $this->cm->change_status($table, ['user_id' => $id]);
                if($status == 1){
                    $this->cm->update('users', ['id' => $id], ['account_verified' => 'Yes']);
                }else{
                    $this->cm->update('users', ['id' => $id], ['account_verified' => 'No']);
                }
            }else{
                $status = $this->cm->change_status($table, ['id' => $id]);
            }
            if($status == 1){
                //echo '<i class="fa fa-fw fa-check green-check-icon"></i>'; die();
                echo '<span class="glyphicon glyphicon-ok-sign green-check-icon"></span>'; die();
            } elseif($status == 0){
                //echo '<i class="fa fa-fw fa-close red-check-icon"></i>'; die();
                echo '<span class="glyphicon glyphicon-remove-sign red-check-icon"></span>'; die();
            }
        }
    }
    public function users($user_type){ 
        $data = $this->data = array(
            'menu' => 'users'
        );
        $data['sub_menu'] = $user_type;
        $data['title'] = 'Admin | Users';
        if($user_type == 'user'){
            $srch = 1;
        }else{
            $srch = 2;
        }
        $data['users'] = $this->cm->select('users', ["login_type" => $srch], '', 'id', 'desc');
        $this->load->view('admin/users', $data);
    }
    public function add_user($id = ''){
        if($this->input->post('user_name')){
            $id = $this->input->post('user_id');
            if($id == ''){
                $chk = $this->cm->get_all('users', array("email" => $this->input->post('user_email')));
                if(empty($chk)){
                    if($this->input->post('user_type') == 1){
                        $status = 1;
                        $verified = 'Yes';
                    }else{
                        $status = 0;
                        $verified = 'No';
                    }
                    $insertData = array(
                        "name"              => $this->input->post('user_name'),
                        "email"             => $this->input->post('user_email'),
                        "login_type"        => $this->input->post('user_type'),
                        "login_password"    => md5($this->input->post('user_pwd')),
                        "gender"            => $this->input->post('user_gender'),
                        "status"            => $status,
                        "account_verified"  => $verified
                    );
                    $insert_id = $this->cm->insert('users', $insertData);
                    $this->session->set_flashdata('success_msg', 'User Successfully addded !!!');
                }else{
                    $this->session->set_flashdata('error_msg', 'Email already exists !!!');
                }                
            }else{
                $chk = $this->db->query("select id from users where email = '".$this->input->post('user_email')."' AND id != '".$id."'")->result();
                if(empty($chk)){
                    if($this->input->post('user_type') == 2){
                        $status = $this->input->post('user_status');
                    }else{
                        $status = $this->input->post('user_status');
                    }
                    $updateData = array(
                        "name"              => $this->input->post('user_name'),
                        "email"             => $this->input->post('user_email'),
                        "login_type"        => $this->input->post('user_type'),
                        "gender"            => $this->input->post('user_gender'),
                        "status"            => $status
                    );
                    $this->cm->update('users', ['id'=> $id], $updateData);
                    $this->session->set_flashdata('success_msg', 'User Details Successfully Updated !!!');
                }else{
                    $this->session->set_flashdata('error_msg', 'Email already exists !!!');
                }
            }
        }
        $data = $this->data = array(
            'menu' => 'users'
        );
        $data['sub_menu'] = '';
        if($id != ''){
            $data['users'] = $this->cm->get_specific('users', array("id" => $id));
            $data['mode'] = 'Edit';
        }else{
            $data['mode'] = 'Add';
        }
        $data['title'] = 'Admin | '.$data['mode'].' User';
        $this->load->view('admin/add_user', $data);
    }
    public function verify_performer(){ 
        $data = $this->data = array(
            'menu' => 'verify'
        );
        $data['sub_menu'] = 'performer';
        $data['title'] = 'Admin | Verify Performer';
        $data['users'] = $this->cm->select('user_verification', [], '', 'id', 'desc');
        $this->load->view('admin/verify_performer', $data);
    }
    public function user_details(){ 
        $data = $this->data = array(
            'menu' => 'verify'
        );
        $data['sub_menu'] = 'performer';
        $data['title'] = 'Admin | Verify Performer';
        $join[] = ['table' => 'user_verification uv', 'on' => 'uv.user_id = u.id', 'type' => 'left'];
        $data['user'] = $this->cm->select('users u', array('u.id' => $this->session->userdata('UserId')), 'u.id, u.name, u.email, u.phone_no, u.gender, u.dob, u.sexual_pref, u.age, u.image, u.address_one, u.address_two, u.city, u.pincode, u.country, u.i_am_a, u.us_citizen, uv.verify_pic, uv.verify_pic_id, uv.name signature_name, uv.verify_date', 'u.id', 'desc', $join);
        $this->load->view('admin/user_details', $data);
    }
    public function vote(){
        $data = $this->data = array(
            'menu' => 'Vote'
        );
        $data['sub_menu'] = 'vote';
        $data['title'] = 'Admin | Vote Management';
        $data['vote'] = $this->db->query('SELECT DISTINCT v.performer_id, u.name, up.display_name, u.image, (select count(vt.id) from vote vt where vt.performer_id = v.performer_id) vote FROM `vote` v LEFT JOIN users u ON u.id = v.performer_id LEFT JOIN user_preference up ON up.user_id = v.performer_id ORDER BY vote DESC')->result();        
        $this->load->view('admin/vote', $data);
    }
    public function showType(){
        if($this->input->post()){
            $chk = $this->cm->get_specific('show_type', array("name" => $this->input->post('showname')));
            if(!empty($chk)){
                $this->session->set_flashdata('success_msg', 'Show Type Already Available !!!');
            }else{
                if($this->input->post('show_edit_id') == ''){
                    $this->cm->insert('show_type', array("name" => $this->input->post('showname')));
                    $this->session->set_flashdata('success_msg', 'Show Type Successfully addded !!!');
                }else{
                    $this->cm->update('show_type', ['id'=> $this->input->post('show_edit_id')], array("name" => $this->input->post('showname')));
                    $this->session->set_flashdata('success_msg', 'Show Type Successfully updated !!!');
                }
            }
        }
        $data = $this->data = array(
            'menu' => 'Service'
        );
        $data['sub_menu'] = 'show-type';
        $data['title'] = 'Admin | Show Type Management';
        $data['show'] = $this->cm->select('show_type', [], '', 'id', 'desc');
        $this->load->view('admin/show_type', $data);
    }
    public function addShowType($id = ''){
        $data = $this->data = array(
            'menu' => 'Service'
        );
        $data['sub_menu'] = 'show-type';
        $data['title'] = 'Admin | Show Type Management';
        if($id != ''){
            $data['show'] = $this->cm->select('show_type', ['id' => $id], '', 'id', 'desc');
            $data['mode'] = 'Edit';
        }else{
            $data['mode'] = 'Add';
        }
        $this->load->view('admin/add_show_type', $data);
    }
    public function willingness(){
        if($this->input->post()){
            $chk = $this->cm->get_specific('willingness', array("name" => $this->input->post('willname')));
            if(!empty($chk)){
                $this->session->set_flashdata('success_msg', 'Willingness Already Available !!!');
            }else{
                if($this->input->post('show_edit_id') == ''){
                    $this->cm->insert('willingness', array("name" => $this->input->post('willname')));
                    $this->session->set_flashdata('success_msg', 'Willingness Successfully addded !!!');
                }else{
                    $this->cm->update('willingness', ['id'=> $this->input->post('will_edit_id')], array("name" => $this->input->post('willname')));
                    $this->session->set_flashdata('success_msg', 'Willingness Successfully updated !!!');
                }
            }
        }
        $data = $this->data = array(
            'menu' => 'Service'
        );
        $data['sub_menu'] = 'willingness';
        $data['title'] = 'Admin | Willingness Management';
        $data['will'] = $this->cm->select('willingness', [], '', 'id', 'desc');
        $this->load->view('admin/willingness', $data);
    }
    public function addWillingness($id = ''){
        $data = $this->data = array(
            'menu' => 'Service'
        );
        $data['sub_menu'] = 'willingness';
        $data['title'] = 'Admin | Willingness Management';
        if($id != ''){
            $data['will'] = $this->cm->select('willingness', ['id' => $id], '', 'id', 'desc');
            $data['mode'] = 'Edit';
        }else{
            $data['mode'] = 'Add';
        }
        $this->load->view('admin/add_willingness', $data);
    }
    public function appearence(){
        if($this->input->post()){
            $chk = $this->cm->get_specific('appearence', array("name" => $this->input->post('aprncname')));
            if(!empty($chk)){
                $this->session->set_flashdata('success_msg', 'Appearence Already Available !!!');
            }else{
                if($this->input->post('aprnc_edit_id') == ''){
                    $this->cm->insert('appearence', array("name" => $this->input->post('aprncname')));
                    $this->session->set_flashdata('success_msg', 'Appearence Successfully addded !!!');
                }else{
                    $this->cm->update('appearence', ['id'=> $this->input->post('aprnc_edit_id')], array("name" => $this->input->post('aprncname')));
                    $this->session->set_flashdata('success_msg', 'Appearence Successfully updated !!!');
                }
            }
        }
        $data = $this->data = array(
            'menu' => 'Service'
        );
        $data['sub_menu'] = 'appearence';
        $data['title'] = 'Admin | Appearence Management';
        $data['appearence'] = $this->cm->select('appearence', [], '', 'id', 'desc');
        $this->load->view('admin/appearence', $data);
    }
    public function addAppearence($id = ''){
        $data = $this->data = array(
            'menu' => 'Service'
        );
        $data['sub_menu'] = 'appearence';
        $data['title'] = 'Admin | Appearence Management';
        if($id != ''){
            $data['appearence'] = $this->cm->select('appearence', ['id' => $id], '', 'id', 'desc');
            $data['mode'] = 'Edit';
        }else{
            $data['mode'] = 'Add';
        }
        $this->load->view('admin/add_appearence', $data);
    }
}
?>
