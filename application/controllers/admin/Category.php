<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

    function __construct() {

        parent::__construct();
        
        if(!$this->session->userdata('user_logged_in')){
            redirect(base_url().'admin');
        }
        
        $this->data = array(
            'menu' => 'category'
        );
        
        $this->userId = $this->session->userdata('user_logged_in')['id'];
    }

    public function index() {
                
        $data = $this->data;
        $data['sub_menu'] = 'category_list';
        $data['title'] = 'Admin | Category';
        
        $data['category_list'] = $this->cm->select('categories', [], '', 'id', 'desc');        
        $this->load->view('admin/category/category-listing', $data);
    }

    public function add_category(){
        
        $mode = $this->input->get('mode');
        if(!$mode){
            redirect('admin/product/categories');
        }
        
        $data = $this->data;
        $data['sub_menu'] = 'category_list';
        $data['title'] = 'Admin | Category';
        
        $edit_id = $this->input->get('id');
        if($edit_id){            
            $data['edit_category'] = $this->cm->select_row('categories', ['id' => $edit_id], '');
        }
        
        $data['post_attr'] = ['mode'=> $mode, 'id'=> $edit_id];
        $this->load->view('admin/category/add_category', $data);
    }
    
    public function receive_category_form_data(){
                
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            
            $mode = $this->input->post('mode');
            $show_on_site   = $this->input->post('show_on_site');
            $category_name = htmlentities(addslashes($this->input->post('category_name')));
            $category_desc = htmlentities(addslashes($this->input->post('category_desc')));
            
            if($mode == 'add'){ 
                
                if ($this->cm->select_row('categories', ['LOWER(name)' => strtolower($category_name)], 'id')) {                    
                    echo json_encode(array('success'=> false, 'message'=> $category_name. ' already exist.')); die();
                } else {
                    
                    $slug = slugfy($category_name);
                    $in_array = [
                        'name' => ucwords($category_name),
                        'slug' => $slug,
                        'description' => ucfirst($category_desc),
                        'show_on_site' => $show_on_site
                    ];

                    $insert_id = $this->cm->insert('categories', $in_array);
                    if($insert_id){

                        if(isset($_FILES['category_image']['name']) && !empty($_FILES['category_image']['name'])){
                            $uploadPath = FCPATH. 'uploads/category_images/';
                            $config['upload_path'] = $uploadPath;
                            $config['allowed_types'] = 'jpg|jpeg|png';
                            $config['encrypt_name'] = TRUE;
                            $config['max_size'] = '2048000';
                            $config['max_width'] = '1024';
                            $config['max_height'] = '748';
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);

                            if($this->upload->do_upload('category_image')){

                                $data = array('upload_data' => $this->upload->data());
                                //image file name		
                                $file_name = $data['upload_data']['file_name']; 
                                $this->cm->update('categories', ['id'=> $insert_id], ['image'=> $file_name]);
                            } else {
                                
                                $this->cm->delete('categories', ['id' => $insert_id]);
                                echo json_encode(array('success'=> false, 'message'=> $this->upload->display_errors())); die();
                            }
                        }

                        echo json_encode(array('success'=> true, 'message'=> 'Successfully added Category.')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Faild to added Category')); die();
                    }
                }
            } 
            
            if($mode == 'edit'){
                
                $id = $this->input->post('id');
                if ($this->cm->select_row('categories', ['LOWER(name)' => strtolower($category_name), 'id !=' => $id], 'id')) {                
                    echo json_encode(array('success'=> false, 'message'=> $category_name. ' already exist.')); die();
                } else {
                    
                    $slug = slugfy($category_name);
                    $in_array = [
                        'name' => ucwords($category_name),
                        'slug' => $slug,
                        'description' => ucfirst($category_desc),
                        'show_on_site' => $show_on_site
                    ];
                    $up_status = $this->cm->update('categories', ['id'=> $id], $in_array);
                    
                    $image_upload = '';
                    if(isset($_FILES['category_image']['name']) && !empty($_FILES['category_image']['name'])){
                        
                        $uploadPath = FCPATH. 'uploads/category_images/';
                        $config['upload_path'] = $uploadPath;
                        $config['allowed_types'] = 'jpg|jpeg|png';
                        $config['encrypt_name'] = TRUE;
                        $config['max_size'] = '2048000';
                        $config['max_width'] = '1024';
                        $config['max_height'] = '748';
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if($this->upload->do_upload('category_image')){
                            
                            if($this->input->post('old_category_img')){
                                unlink(FCPATH.'uploads/category_images/'.$this->input->post('old_category_img'));
                            }
                            
                            $data = array('upload_data' => $this->upload->data());
                            //image file name		
                            $file_name = $data['upload_data']['file_name']; 
                            $image_upload = $this->cm->update('categories', ['id'=> $id], ['image'=> $file_name]);
                        } else {                            
                            echo json_encode(array('success'=> false, 'message'=> $this->upload->display_errors())); die();
                        }
                    }
                    
                    if($up_status || $image_upload){
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully update Category.')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Nothing to change, please try again.')); die();
                    }
                    
                }
            }
        }
    }
    
    public function sub_category(){
        $data = $this->data;
        $data['sub_menu'] = 'sub_category_list';
        $data['title'] = 'Admin | Category';
        
        $data['category_list'] = $this->db->query('SELECT * FROM sub_categories ORDER BY id DESC')->result_array();        
        $this->load->view('admin/category/sub-category', $data);
    }
    
    public function add_sub_category(){
        
        $mode = $this->input->get('mode');
        if(!$mode){
            redirect('admin/product/categories');
        }
        
        $data = $this->data;
        $data['sub_menu'] = 'sub_category_list';
        $data['title'] = 'Admin | Category';
        
        $edit_id = $this->input->get('id');
        if($edit_id){            
            $data['edit_category'] = $this->cm->select_row('sub_categories', ['id' => $edit_id], '');
        }
        
        $data['category_list'] = $this->cm->select('categories', ['status'=> 1], '', 'name', 'asc'); 
        $data['post_attr'] = ['mode'=> $mode, 'id'=> $edit_id];
        $this->load->view('admin/category/add_sub_category', $data);
    }
    
    public function receive_sub_category_form_data(){
                
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            
            $mode = $this->input->post('mode');
            $show_on_site   = $this->input->post('show_on_site');
            $category_name = htmlentities(addslashes($this->input->post('category_name')));
            $category_id = $this->input->post('category_id');
            
            if($mode == 'add'){ 
                
                if ($this->cm->select_row('sub_categories', ['LOWER(name)' => strtolower($category_name)], 'id')) {                    
                    echo json_encode(array('success'=> false, 'message'=> $category_name. ' already exist.')); die();
                } else {
                    
                    $slug = slugfy($category_name);
                    $in_array = [
                        'name' => ucwords($category_name),
                        'slug' => $slug,
                        'cat_id' => $category_id,
                        'show_on_site' => $show_on_site
                    ];

                    $insert_id = $this->cm->insert('sub_categories', $in_array);
                    if($insert_id){
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully added Sub Category.')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Faild to added Sub Category')); die();
                    }
                }
            } 
            
            if($mode == 'edit'){
                
                $id = $this->input->post('id');
                if ($this->cm->select_row('sub_categories', ['LOWER(name)' => strtolower($category_name), 'id !=' => $id], 'id')) {                
                    echo json_encode(array('success'=> false, 'message'=> $category_name. ' already exist.')); die();
                } else {
                    
                    $slug = slugfy($category_name);
                    $in_array = [
                        'name' => ucwords($category_name),
                        'slug' => $slug,
                        'cat_id' => $category_id,
                        'show_on_site' => $show_on_site
                    ];
                    $up_status = $this->cm->update('sub_categories', ['id'=> $id], $in_array);
                    if($up_status){
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully update Sub Category.')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Nothing to change, please try again.')); die();
                    }
                }
            }
        }
    }
    
    public function getSubCategotyByCatId(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {            
            $id = $this->input->post('id');
            if($id){
                $option = '<option value="">Select option</option>';
                $sub_category = $this->cm->select('sub_categories', ['cat_id' => $id], '', 'name', 'asc'); if($sub_category){
                    foreach($sub_category as $sub){
                        $option .= '<option value='.$sub['id'].'>'.$sub['name'].'</option>';
                    }
                }
                echo $option;
            }
        }
    }
    
    public function change_status(){
        
        if($this->input->post()){
            $id = $this->input->post('id');
            $table = $this->input->post('table');
            
            $status = $this->cm->change_status($table, ['id' => $id]);
            if($status == 1){
                echo '<i class="fa fa-fw fa-check green-check-icon"></i>'; die();
            } elseif($status == 0){
                echo '<i class="fa fa-fw fa-close red-check-icon"></i>'; die();
            }
        }
    }
    
    
    
}

?>