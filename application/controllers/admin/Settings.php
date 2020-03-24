<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    function __construct() {

        parent::__construct();
        
        if(!$this->session->userdata('user_logged_in')){
            redirect(base_url().'admin');
        }
        
        $this->data = array(
            'menu' => 'settings'
        );
        
        $this->userId = $this->session->userdata('user_logged_in')['id'];
    }

    /*
        Site settings 
    */
    public function index() {
                
        $data = $this->data;
        $data['sub_menu'] = 'site_settings';
        $data['title'] = 'Admin | Site Settings';
        
        if($this->input->post()){                        
            $set_update = $this->cm->update('site_settings', ['id'=> $this->input->post('id')], [
                'age_restriction' => $this->input->post('age_restriction'),
                'site_name' => $this->input->post('site_name'),
                'site_sender_name' => $this->input->post('site_sender_name'),
                'site_receiver_email' => $this->input->post('site_reciver_email'),
                'site_sender_email' => $this->input->post('sender_email'),
                'site_address' => $this->input->post('site_address'),
                'site_phone_1' => $this->input->post('site_phone1'),
                'site_phone_2' => $this->input->post('site_phone2'),
                'site_gplus_link' => $this->input->post('gplus_link'),
                'site_facebook_link' => $this->input->post('facebook_link'),
                'site_twitter_link' => $this->input->post('twitter_link'),
                'site_linkedin_link' => $this->input->post('linkedin_link'),
                'site_instagram_link' => $this->input->post('instagram_link'),
                'site_youtube_link' => $this->input->post('youtube_link'),
                'site_title' => $this->input->post('meta_title'),
                'site_meta_description' => $this->input->post('meta_description')
            ]);
            if($set_update){
                $this->session->set_flashdata('success_msg', 'Successfully update data.');
                redirect('admin/settings');
            }
        }
        
        $data['edit_data'] = $this->cm->select_row('site_settings', ['id' => 1], '');       
        $this->load->view('admin/site_settings', $data);
    }
    
    /*
        CMS settings 
    */
    public function cms() {
                
        $data = $this->data;
        $data['sub_menu'] = 'cms';
        $data['title'] = 'Admin | Manage CMS'; 
        
        if($this->input->post('mode') == 'edit'){
            
            $id = $this->input->post('id');
            if( !$this->input->post('page_content')){
                $this->session->set_flashdata('error_msg', 'Page Content cannot be blank.');
                redirect('admin/settings/edit_cms?mode=edit&id='.$id);
            }
            
            $set_update = $this->cm->update('static_pages', ['id'=> $id], [
                'page_title' => addslashes($this->input->post('page_title')),
                'page_content' => addslashes($this->input->post('page_content')),
                'page_meta_content' => addslashes($this->input->post('meta_description'))
            ]);
            if( !$set_update){
                $this->session->set_flashdata('error_msg', 'Faild to update, Please try again.');
                redirect('admin/settings/edit_cms?mode=edit&id='.$id);
            }
            
            $this->session->set_flashdata('success_msg', 'Successfully update CMS');
            redirect('admin/settings/cms');
        }
        
        $data['data_list'] = $this->cm->select('static_pages', [], '', 'id', 'desc');     
        $this->load->view('admin/cms_pages', $data);
    }
    
    public function edit_cms(){
        
        $mode = $this->input->get('mode');
        if(!$mode){
            redirect('admin/settings/cms');
        }
        
        $data = $this->data;
        $data['sub_menu'] = 'cms';
        $data['title'] = 'Admin | CMS';
        
        $edit_id = $this->input->get('id');
        if($edit_id){            
            $data['edit_data'] = $this->cm->select_row('static_pages', ['id' => $edit_id], '');
        }
        
        $data['post_attr'] = ['mode'=> $mode, 'id'=> $edit_id];
        $this->load->view('admin/edit_cms', $data);
    }
    /*
        Question categories
        */
    public function question_categories(){
        
        $data = $this->data;
        $data['sub_menu'] = 'question_categories';
        $data['title'] = 'Admin | Question Categories';
         
        $data['data_list'] = $this->cm->select('faq_question_categories', [], '', 'id', 'desc');
        $this->load->view('admin/faq_question_categories', $data);
    }
    /*
        Add Edit Question Category
    */
    public function add_question_category(){
        
        $mode = $this->input->get('mode');
        if(!$mode){
            redirect('admin/settings/question_categories');
        }
        
        $data = $this->data;
        $data['sub_menu'] = 'question_categories';
        $data['title'] = 'Admin | Question Categories';        
        $edit_id = $this->input->get('id');
        if($edit_id){            
            $data['edit_data'] = $this->cm->select_row('faq_question_categories', ['id' => $edit_id], '');
        }
         
        $data['post_attr'] = ['mode'=> $mode, 'id'=> $edit_id];
        $this->load->view('admin/add_question_category', $data);
    }
    /*
        Save and Update Question Category
        */
    public function save_question_category_data(){
                
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $mode = $this->input->post('mode');
            $category_name = addslashes($this->input->post('servicename'));            
            if($mode == 'add'){
                
                if($this->cm->select_row('faq_question_categories', ['LOWER(name)' => strtolower($category_name)], 'id')){         
                    echo json_encode(array('success'=> false, 'message'=> $category_name. ' already exist.')); die();
                } else {
                    $in_array = [
                        'name' => ucwords($category_name)
                    ];
                    $insert_id = $this->cm->insert('faq_question_categories', $in_array);
                    if($insert_id){
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully added category')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Faild to added category')); die();
                    }
                }
            }
            
            if($mode == 'edit'){
                
                $id = $this->input->post('id');
                if ($this->cm->select_row('faq_question_categories', ['LOWER(name)' => strtolower($category_name), 'id !=' => $id], 'id')) {                
                    echo json_encode(array('success'=> false, 'message'=> $category_name. ' already exist.')); die();
                } else {                    
                    $up_array = [
                        'name' => ucwords($category_name)
                    ];
                    $up_status = $this->cm->update('faq_question_categories', ['id'=> $id], $up_array); 
                    if($up_status){
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully update category')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Nothing to change, please try again.')); die();
                    }
                }
            }
        }
    }
    /*
        Manage Questions
        */
    public function questions(){
        
        $data = $this->data;
        $data['sub_menu'] = 'questions';
        $data['title'] = 'Admin | Questions';
        /*
            Save and Update Question
            */
        if($this->input->post()){
            $mode = $this->input->post('mode');
            $category_id = $this->input->post('faq_category_id');
            $faq_question = addslashes($this->input->post('faq_question'));
            $faq_answer = addslashes($this->input->post('faq_answer'));
            if($mode == 'add'){
                if(!$faq_answer){
                    $this->session->set_flashdata('error_msg', 'Answer cannot be empty!');
                    redirect(base_url().'admin/settings/add_question?mode=add');
                }
                
                if($this->cm->select_row('faq_questions', ['LOWER(question)' => strtolower($faq_question)], 'id')){
                    $this->session->set_flashdata('error_msg', $faq_question.' already exist!');
                    redirect(base_url().'admin/settings/add_question?mode=add');
                } else {
                    $in_array = [
                        'cat_id' => $category_id,
                        'question' => ucfirst($faq_question),
                        'answer' => ucfirst($faq_answer)
                    ];
                    $insert_id = $this->cm->insert('faq_questions', $in_array);
                    if($insert_id){
                        $this->session->set_flashdata('success_msg', 'Successfully added question.');
                        redirect(base_url().'admin/settings/questions');
                    } else {
                        $this->session->set_flashdata('error_msg', 'Faild to added question!');
                        redirect(base_url().'admin/settings/questions');
                    }
                }
            }
            if($mode == 'edit'){
                $id = $this->input->post('id');
                if(!$faq_answer){
                    $this->session->set_flashdata('error_msg', 'Answer cannot be empty!');
                    redirect(base_url().'admin/settings/add_question?mode=edit&id='.$id);
                }
                
                if($this->cm->select_row('faq_questions', ['LOWER(question)' => strtolower($faq_question), 'id !=' => $id], 'id')) {
                    $this->session->set_flashdata('error_msg', $faq_question.' already exist!');
                    redirect(base_url().'admin/settings/add_question?mode=edit&id='.$id);
                } else {
                    $up_array = [
                        'cat_id' => $category_id,
                        'question' => ucfirst($faq_question),
                        'answer' => ucfirst($faq_answer)
                    ];
                    $up_status = $this->cm->update('faq_questions', ['id'=> $id], $up_array); 
                    if($up_status){
                        $this->session->set_flashdata('success_msg', 'Successfully update question.');
                        redirect(base_url().'admin/settings/questions');
                    } else {
                        $this->session->set_flashdata('error_msg', 'Nothing to change, please try again!');
                        redirect(base_url().'admin/settings/questions');
                    }
                }
            }
        }
        
        $join[] = ['table' => 'faq_question_categories c', 'on' => 'q.cat_id = c.id', 'type' => 'inner'];
        $data['data_list'] = $this->cm->select('faq_questions q', [], 'q.id,c.name category,q.question,q.status,q.updated_at', 'q.id', 'desc', $join);
        $this->load->view('admin/faq_questions', $data);
    }
    /*
        Add Edit Question Category
    */
    public function add_question(){
        
        $mode = $this->input->get('mode');
        if(!$mode){
            redirect('admin/settings/questions');
        }
        
        $data = $this->data;
        $data['sub_menu'] = 'questions';
        $data['title'] = 'Admin | Questions';
        $edit_id = $this->input->get('id');
        if($edit_id){            
            $data['edit_data'] = $this->cm->select_row('faq_questions', ['id' => $edit_id], '');
        }
        
        $data['data_faq_category'] = $this->cm->select('faq_question_categories', ['status'=> 1], 'id,name', 'name', 'asc');
        $data['post_attr'] = ['mode'=> $mode, 'id'=> $edit_id];
        $this->load->view('admin/add_question', $data);
    }
    /*
        Manage Credit Plans
        */
    public function credit_plan(){
        $data = $this->data = array(
            'menu' => 'plan_list'
        );
        $data['sub_menu'] = 'plan_list';
        $data['title'] = 'Admin | Credit Plans';
        /*
            Save and Update Plan
            */
        if($this->input->post()){
            $mode = $this->input->post('mode');
            $heading = addslashes($this->input->post('heading'));
            if($this->input->post('credit') < 1){
                $this->session->set_flashdata('error_msg', '0 value not allow for credit!');
                redirect(base_url().'admin/credit/add_plan?mode=add');
            }
            if($this->input->post('sell_price') < 1){
                $this->session->set_flashdata('error_msg', '0 value not allow for sell price!');
                redirect(base_url().'admin/credit/add_plan?mode=add');
            }
            if($mode == 'add'){
                
                if($this->cm->select_row('credit_plans', ['LOWER(title)' => strtolower($heading)], 'id')){
                    $this->session->set_flashdata('error_msg', stripslashes($heading).' already exist!');
                    redirect(base_url().'admin/credit/add_plan?mode=add');
                } else {
                    $in_array = [
                        'title' => $heading,
                        'description' => addslashes($this->input->post('description')),
                        'credit' => $this->input->post('credit'),
                        'sell_price' => $this->input->post('sell_price'),
                        'tag' => $this->input->post('taging')
                    ];
                    $insert_id = $this->cm->insert('credit_plans', $in_array);
                    if($insert_id){
                        $this->session->set_flashdata('success_msg', 'Successfully added plan.');
                        redirect(base_url().'admin/credit/plans');
                    } else {
                        $this->session->set_flashdata('error_msg', 'Faild to added plan!');
                        redirect(base_url().'admin/credit/plans');
                    }
                }
            }
            if($mode == 'edit'){
                
                $id = $this->input->post('id');                
                if($this->cm->select_row('credit_plans', ['LOWER(title)' => strtolower($heading), 'id !=' => $id], 'id')) {
                    $this->session->set_flashdata('error_msg', stripslashes($heading).' already exist!');
                    redirect(base_url().'admin/credit/add_plan?mode=edit&id='.$id);
                } else {
                    $up_array = [
                        'title' => $heading,
                        'description' => addslashes($this->input->post('description')),
                        'credit' => $this->input->post('credit'),
                        'sell_price' => $this->input->post('sell_price'),
                        'tag' => $this->input->post('taging')
                    ];
                    $up_status = $this->cm->update('credit_plans', ['id'=> $id], $up_array); 
                    if($up_status){
                        $this->session->set_flashdata('success_msg', 'Successfully update plan.');
                        redirect(base_url().'admin/credit/plans');
                    } else {
                        $this->session->set_flashdata('error_msg', 'Nothing to change, please try again!');
                        redirect(base_url().'admin/credit/plans');
                    }
                }
            }
        }
        
        $data['data_list'] = $this->cm->select('credit_plans', [], '', 'id', 'desc');        
        $this->load->view('admin/credit_plans', $data);
    }
    /*
        Add Edit Credit Plan
    */
    public function add_credit_plan(){
        
        $mode = $this->input->get('mode');
        if(!$mode){
            redirect('admin/credit/plans');
        }
        
        $data = $this->data = array(
            'menu' => 'plan_list'
        );
        $data['sub_menu'] = 'plan_list';
        $data['title'] = 'Admin | Credit Plans';
        $edit_id = $this->input->get('id');
        if($edit_id){            
            $data['edit_data'] = $this->cm->select_row('credit_plans', ['id' => $edit_id], '');
        }
        
        $data['post_attr'] = ['mode'=> $mode, 'id'=> $edit_id];
        $this->load->view('admin/add_credit_plan', $data);
    }
    
    /*
        Manage Banners
    */
    public function banners(){
        $data = $this->data = array(
            'menu' => 'banners'
        );
        $data['sub_menu'] = 'banner_list';
        $data['title'] = 'Admin | Banners';
        $data['data_list'] = $this->cm->select('banners', [], '', 'id', 'desc');        
        $this->load->view('admin/banners', $data);
    }
    
    public function add_banner(){
        $mode = $this->input->get('mode');
        if(!$mode){
            redirect('admin/banners');
        }
        
        $data = $this->data = array(
            'menu' => 'banners'
        );
        $data['sub_menu'] = 'banner_list';
        $data['title'] = 'Admin | Banners';
        
        $edit_id = $this->input->get('id');
        if($edit_id){            
            $data['edit_data'] = $this->cm->select_row('banners', ['id' => $edit_id], '');
        }
        
        $data['product_list'] = $this->cm->select('products', ['status'=> 1], 'id,name,slug', 'name', 'asc');
        $data['post_attr'] = ['mode'=> $mode, 'id'=> $edit_id];
        $this->load->view('admin/add_banner', $data);
    }
    
    public function receive_banner_form_data(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            
            $mode = $this->input->post('mode');
            $title  = addslashes($this->input->post('title'));
            $description = addslashes($this->input->post('description'));
            $banner_url_product = $this->input->post('banner_url_product');
            
            if($mode == 'add'){ 
                
                $in_array = [
                    'title' => ucwords($title),
                    'description' => ucfirst($description),
                    'product_id' => $banner_url_product
                ];

                $insert_id = $this->cm->insert('banners', $in_array);
                if($insert_id){

                    $uploadPath = FCPATH. 'uploads/banner_images/';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'jpg|jpeg|png';
                    $config['encrypt_name'] = TRUE;
                    $config['max_size'] = '2048000';
                    $config['max_width'] = '1366';
                    $config['max_height'] = '768';
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('category_image')){
                        $data = array('upload_data' => $this->upload->data());
                        $file_name = $data['upload_data']['file_name']; 
                        $this->cm->update('banners', ['id'=> $insert_id], ['image'=> $file_name]);
                    } else {
                        $this->cm->delete('banners', ['id' => $insert_id]);
                        echo json_encode(array('success'=> false, 'message'=> $this->upload->display_errors())); die();
                    }

                    echo json_encode(array('success'=> true, 'message'=> 'Successfully added Banner.')); die();
                } else {
                    echo json_encode(array('success'=> false, 'message'=> 'Faild to added Banner')); die();
                }
            } 
            
            if($mode == 'edit'){
                
                $id = $this->input->post('id');                    
                $in_array = [
                    'title' => ucwords($title),
                    'description' => ucfirst($description),
                    'product_id' => $banner_url_product
                ];
                $up_status = $this->cm->update('banners', ['id'=> $id], $in_array);

                $image_upload = '';
                if(isset($_FILES['category_image']['name']) && !empty($_FILES['category_image']['name'])){

                    $uploadPath = FCPATH. 'uploads/banner_images/';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'jpg|jpeg|png';
                    $config['encrypt_name'] = TRUE;
                    $config['max_size'] = '2048000';
                    $config['max_width'] = '1366';
                    $config['max_height'] = '768';
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('category_image')){

                        if($this->input->post('old_category_img')){
                            unlink(FCPATH.'uploads/banner_images/'.$this->input->post('old_category_img'));
                        }

                        $data = array('upload_data' => $this->upload->data());
                        $file_name = $data['upload_data']['file_name']; 
                        $image_upload = $this->cm->update('banners', ['id'=> $id], ['image'=> $file_name]);
                    } else {                            
                        echo json_encode(array('success'=> false, 'message'=> $this->upload->display_errors())); die();
                    }
                }

                if($up_status || $image_upload){
                    echo json_encode(array('success'=> true, 'message'=> 'Successfully update Banner.')); die();
                } else {
                    echo json_encode(array('success'=> false, 'message'=> 'Nothing to change, please try again.')); die();
                }
            }
        }
    }
    
    public function delete_banner(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {            
            $id = $this->input->post('id');
            if($id){
                $banner = $this->cm->select_row('banners', ['id' => $id], '');
                if($banner){
                    if (file_exists(FCPATH . 'uploads/banner_images/'.$banner['image'])){
                        unlink(FCPATH . 'uploads/banner_images/' . $banner['image']);
                        $this->cm->delete('banners', ['id' => $id]);
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully delete Banner.')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Faild to delete Banner.')); die();
                    }
                }                
            }
        }
    }
    
    /*
        Change Table Status
        */
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