<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

    function __construct() {

        parent::__construct();
        
        if(!$this->session->userdata('user_logged_in')){
            redirect(base_url().'admin');
        }
        
        
        $this->data = array(
            'menu' => 'product'
        );
        
        $this->userId = $this->session->userdata('user_logged_in')['id'];
    }
    
    /*
        Manage Product
        */
    public function index() {
        
        $data = $this->data;
        $data['sub_menu'] = 'product_list';
        $data['title'] = 'Admin | Products';
        
        $join[] = ['table' => 'product_brands b', 'on' => 'b.id = p.brand_id', 'type' => 'inner'];
        $join[] = ['table' => 'categories c', 'on' => 'c.id = p.cat_id', 'type' => 'inner'];
        $data['data_list'] = $this->cm->select('products p', [], 'p.*,c.name category,b.name brand', 'p.id', 'desc', $join);
        $this->load->view('admin/products', $data);
    }
    
    public function add_product(){
        
        $mode = $this->input->get('mode');
        if(!$mode){
            redirect('admin/product');
        }
        
        $data = $this->data;
        $data['sub_menu'] = 'product_list';
        $data['title'] = 'Admin | Products';
        
        $edit_id = $this->input->get('id');
        if($edit_id){            
            $data['edit_data'] = $this->cm->select_row('products', ['id' => $edit_id], '');
            $data['edit_specification'] = $this->cm->select('product_specification', ['product_id'=> $edit_id], '');
            $data['edit_variation'] = $this->cm->select('product_variations', ['product_id'=> $edit_id], '');
            $data['data_sub_cat_list'] = $this->cm->select('sub_categories', ['cat_id'=> $data['edit_data']['cat_id'],'status'=> 1], '', 'name', 'asc');
        }
        
        $data['data_cat_list'] = $this->cm->select('categories', ['status'=> 1], '', 'name', 'asc');
        $data['data_brand_list'] = $this->cm->select('product_brands', ['status'=> 1], '', 'name', 'asc');
        $data['data_size_list'] = $this->cm->select('product_sizes', ['status'=> 1], '', 'name', 'asc');
        //$data['data_color_list'] = $this->cm->select('product_colors', ['status'=> 1], '', 'name', 'asc');
        $data['data_color_list'] = $this->db->query("select id,name from product_colors where status=1 and status >0 order by id=0 desc,name asc")->result_array();
        $data['post_attr'] = ['mode'=> $mode, 'id'=> $edit_id];
        $this->load->view('admin/add_product', $data);
    }
    
    public function receive_product_form_data(){
                
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            
            $this->load->library('image_lib');
            
            $mode = $this->input->post('mode');
            $cat_id   = $this->input->post('cat_id');
            $sub_cat_id   = $this->input->post('sub_cat_id');
            $brand_id   = $this->input->post('brand_id');
            $product_name = addslashes($this->input->post('product_name'));
            $pro_desc = $this->input->post('product_description');
            $pro_specification = $this->input->post('pro_specification'); //this is the array
            $pro_specification_dtls = $this->input->post('pro_specification_dtls'); //this is the array
            $size_id = $this->input->post('size_id'); //this is the array
            $color_id = $this->input->post('color_id'); //this is the array
            $pro_mrp_price = $this->input->post('pro_mrp_price');
            $pro_sell_price = $this->input->post('pro_sell_price');
            $pro_quantity = $this->input->post('pro_quantity');
            $set_discount = $this->input->post('set_discount');
            $disc_percentage = $this->input->post('disc_percentage');
            $pro_status = $this->input->post('pro_status');
            
            if(!$pro_desc){
                echo json_encode(array('success'=> false, 'message'=> 'Product description cannot be blank')); die();
            }
            
            if($mode == 'add'){
                
                if(isset($_FILES['product_primary_image']['name']) && empty($_FILES['product_primary_image']['name'])){
                    echo json_encode(array('success'=> false, 'message'=> 'Please upload product primary image ')); die();
                }
                
                if($pro_quantity < 0){
                    echo json_encode(array('success'=> false, 'message'=> 'Please check your product stock quantity.')); die();
                }
                
                if ($this->cm->select_row('products', ['LOWER(name)' => strtolower($product_name)], 'id')) {                    
                    echo json_encode(array('success'=> false, 'message'=> 'Sorry, '.$product_name. ' already exists.')); die();
                } else {
                    
                    //Upload product primary image
                    $uploadPath = FCPATH. 'uploads/product_images/';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'jpg|jpeg|png';
                    $config['encrypt_name'] = TRUE;
                    $config['max_size'] = '500';
                    $config['max_width'] = '300';
                    $config['max_height'] = '350';
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if($this->upload->do_upload('product_primary_image')){

                        $data = array('upload_data' => $this->upload->data());	
                        $file_name = $data['upload_data']['file_name']; 
                        $slug = slugfy($product_name);
                        $in_array = [
                            'name' => $product_name,
                            'slug' => $slug,
                            'cat_id' => $cat_id,
                            'sub_cat_id' => $sub_cat_id,
                            'brand_id' => $brand_id,
                            'description' => addslashes($pro_desc),
                            'primary_image' => $file_name,
                            'quantity' => $pro_quantity,
                            'purchase_quantity' => $pro_quantity,
                            'price' => $pro_mrp_price,
                            'sell_price' => $pro_sell_price,
                            'set_discount' => $set_discount,
                            'discount_percentage' => $disc_percentage,
                            'status' => $pro_status
                        ];

                        $insert_id = $this->cm->insert('products', $in_array);
                        if($insert_id){
                            //create product stock log
                            $this->cm->insert('product_stock_log', [
                                'product_id' => $insert_id,
                                'previous_stock' => 0,
                                'new_stock' => $pro_quantity,
                                'current_stock' => $pro_quantity,
                                'status' => 'IN',
                                'remarks' => 'New Stock Added'
                            ]);
                            //** Update product slug if exist
                            if ($this->cm->select_row('products', ['LOWER(slug)' => strtolower($slug), 'id !='=> $insert_id], 'id')) {
                                $this->cm->update('products', ['id'=> $insert_id], ['slug'=> $slug.'-'.$insert_id]);
                            }
                            
                            //*Insert Product specification */
                            if(!empty($pro_specification) && !empty($pro_specification_dtls)){
                                $in_specification = [];
                                for ($i = 0; $i < count($pro_specification); $i++) {
                                    $in_specification[] = [
                                        'product_id' => $insert_id,
                                        'specification' => $pro_specification[$i],
                                        'details' => $pro_specification_dtls[$i]
                                    ];
                                }
                                if(!empty($in_specification)){
                                    $insert_spec = $this->db->insert_batch('product_specification', $in_specification);
                                    if(!$insert_spec){
                                        $this->cm->delete('products', ['id' => $insert_id]);
                                        $this->cm->delete('product_stock_log', ['product_id' => $insert_id]);
                                        echo json_encode(array('success' => false, 'message' => 'Faild to added Product specification.')); die();
                                    }
                                }
                            }                           
                            //*Insert size and color **/
                            if(!empty($size_id) && !empty($color_id)){                            
                                for ($i = 0; $i < count($size_id); $i++) {

                                    $in_size_color = [
                                        'product_id' => $insert_id,
                                        'size_id' => $size_id[$i],
                                        'color_id' => $color_id[$i]
                                    ];

                                    $product_attr_id = $this->cm->insert('product_variations', $in_size_color);
                                    if (!$product_attr_id) {
                                        
                                        if (file_exists(FCPATH.'uploads/product_images/'.$file_name)){
                                            unlink(FCPATH . 'uploads/product_images/' . $file_name);
                                        }
                                        $this->cm->delete('products', ['id' => $insert_id]);
                                        $this->cm->delete('product_stock_log', ['product_id' => $insert_id]);
                                        echo json_encode(array('success' => false, 'message' => 'Failed to add the product to database please refresh the page and try again'));
                                        die();
                                    }
                                    //** Upload product image **/
                                    if (isset($_FILES['product_image' . $i]['name'])) {
                                        $filesCount = count($_FILES['product_image' . $i]['name']);

                                        $uploadData = array();
                                        for ($j = 0; $j < $filesCount; $j++) {
                                            $_FILES['file']['name'] = $_FILES['product_image' . $i]['name'][$j];
                                            $_FILES['file']['type'] = $_FILES['product_image' . $i]['type'][$j];
                                            $_FILES['file']['tmp_name'] = $_FILES['product_image' . $i]['tmp_name'][$j];
                                            $_FILES['file']['error'] = $_FILES['product_image' . $i]['error'][$j];
                                            $_FILES['file']['size'] = $_FILES['product_image' . $i]['size'][$j];

                                            // File upload configuration
                                            $uploadPath = FCPATH . 'uploads/product_images/';
                                            $config['upload_path'] = $uploadPath;
                                            $config['allowed_types'] = 'jpg|jpeg|png';
                                            $config['encrypt_name'] = TRUE;
                                            $config['max_size'] = '2048000';
                                            $config['max_width'] = '1024';
                                            $config['max_height'] = '768';

                                            // Load and initialize upload library
                                            $this->load->library('upload', $config);
                                            $this->upload->initialize($config);

                                            // Upload file to server
                                            if ($this->upload->do_upload('file')) {
                                                // Uploaded file data
                                                $fileData = $this->upload->data();

                                                $source_path = FCPATH . 'uploads/product_images/' . $fileData['file_name'];

                                                $target_path = FCPATH . 'uploads/product_images/thumb/';
                                                $config_manip = array(
                                                    'image_library' => 'gd2',
                                                    'source_image' => $source_path,
                                                    'new_image' => $target_path,
                                                    'maintain_ratio' => TRUE,
                                                    'create_thumb' => TRUE,
                                                    'width' => 270,
                                                    'height' => 314
                                                );

                                                $this->image_lib->initialize($config_manip);
                                                if (!$this->image_lib->resize()) {
                                                    
                                                    if (file_exists(FCPATH.'uploads/product_images/'.$file_name)){
                                                        unlink(FCPATH . 'uploads/product_images/' . $file_name);
                                                    }
                                                    $this->cm->delete('products', ['id' => $insert_id]);
                                                    $this->cm->delete('product_stock_log', ['product_id' => $insert_id]);
                                                    echo json_encode(array('success'=> false, 'message'=> $this->image_lib->display_errors())); die();
                                                }
                                                $this->image_lib->clear();

                                                $uploadData[$i . $j]['image'] = $fileData['file_name'];
                                                $uploadData[$i . $j]['product_id'] = $insert_id;
                                                $uploadData[$i . $j]['color_id'] = $color_id[$i];
                                            } else {
                                                
                                                if (file_exists(FCPATH.'uploads/product_images/'.$file_name)){
                                                    unlink(FCPATH . 'uploads/product_images/' . $file_name);
                                                }
                                                $this->cm->delete('products', ['id' => $insert_id]);
                                                $this->cm->delete('product_stock_log', ['product_id' => $insert_id]);
                                                echo json_encode(array('success'=> false, 'message'=> $this->upload->display_errors())); die();
                                            }
                                        }

                                        if (!empty($uploadData)) {
                                            $this->db->insert_batch('product_images', $uploadData);
                                        }
                                    }
                                }
                            }

                            echo json_encode(array('success'=> true, 'message'=> stripslashes($product_name).' Successfully added to product list.', 'mode'=> 'add')); die();
                        } else {
                            echo json_encode(array('success'=> false, 'message'=> 'Failed to add the product to database please refresh the page and try again')); die();
                        }
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> $this->upload->display_errors())); die();
                    }                    
                    
                }
            } 
            
            if($mode == 'edit'){
                                
                $id = $this->input->post('id');
                if ($this->cm->select_row('products', ['LOWER(name)' => strtolower($product_name), 'id !='=> $id], 'id')) {
                    echo json_encode(array('success'=> false, 'message'=> 'Sorry, '.stripslashes($product_name). ' already exists.')); die();
                } else {
                    
                    $product_current = $this->cm->select_row('products', ['id'=> $id], '');
                    $slug = slugfy($product_name);
                    $up_array = [
                        'name' => $product_name,
                        'cat_id' => $cat_id,
                        'sub_cat_id' => $sub_cat_id,
                        'brand_id' => $brand_id,
                        'slug'=> $slug,
                        'description' => addslashes($pro_desc),
                        'quantity' => $pro_quantity,
                        'purchase_quantity' => $pro_quantity,
                        'price' => $pro_mrp_price,
                        'sell_price' => $pro_sell_price,
                        'set_discount' => $set_discount,
                        'discount_percentage' => $disc_percentage,
                        'status' => $pro_status
                    ];

                    $update_product = $this->cm->update('products', ['id'=> $id], $up_array);
                    //** Update product slug if exist
                    if ($this->cm->select_row('products', ['LOWER(slug)' => strtolower($slug), 'id !='=> $id], 'id')) {
                        $this->cm->update('products', ['id'=> $id], ['slug'=> $slug.'-'.$id]);
                    }
                    //add product new stock if exist
                    if($pro_quantity !='' && $pro_quantity >0){
                        //update product quantity
                        $this->cm->update('products', ['id'=> $id], [
                            'quantity' => $product_current['quantity']+$pro_quantity,
                            'purchase_quantity' => $product_current['purchase_quantity']+$pro_quantity
                        ]);
                        //create product stock log
                        $this->cm->insert('product_stock_log', [
                            'product_id' => $id,
                            'previous_stock' => $product_current['purchase_quantity'],
                            'new_stock' => $pro_quantity,
                            'current_stock' => $product_current['purchase_quantity']+$pro_quantity,
                            'status' => 'IN',
                            'remarks' => 'New Stock Added'
                        ]);
                    }
                    //upload product primary image
                    if(isset($_FILES['product_primary_image']['name']) && !empty($_FILES['product_primary_image']['name'])){
                        
                        $uploadPath = FCPATH. 'uploads/product_images/';
                        $config['upload_path'] = $uploadPath;
                        $config['allowed_types'] = 'jpg|jpeg|png';
                        $config['encrypt_name'] = TRUE;
                        $config['max_size'] = '500';
                        $config['max_width'] = '300';
                        $config['max_height'] = '350';
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if($this->upload->do_upload('product_primary_image')){
                            $data = array('upload_data' => $this->upload->data());	
                            $file_name = $data['upload_data']['file_name'];
                            $this->cm->update('products', ['id'=> $id], ['primary_image'=> $file_name]);
                            if($this->input->post('old_product_primary_image')){
                                unlink(FCPATH.'uploads/product_images/'.$this->input->post('old_product_primary_image'));
                            }
                        } else {
                            echo json_encode(array('success'=> false, 'message'=> $this->upload->display_errors())); die();
                        }
                    }
                    //*Insert Product specification */
                    if(!empty($pro_specification) && !empty($pro_specification_dtls)){
                        
                        $this->cm->delete('product_specification', ['product_id' => $id]);
                        $in_specification = [];
                        for ($i = 0; $i < count($pro_specification); $i++) {
                            $in_specification[] = [
                                'product_id' => $id,
                                'specification' => $pro_specification[$i],
                                'details' => $pro_specification_dtls[$i]
                            ];
                        }
                        if(!empty($in_specification)){
                            $insert_spec = $this->db->insert_batch('product_specification', $in_specification);
                            if(!$insert_spec){
                                echo json_encode(array('success' => false, 'message' => 'Faild to added Product specification.')); die();
                            }
                        }
                    } 
                    //**  Insert size and color **/
                    if(!empty($size_id) && !empty($color_id)){                            
                        for ($i = 0; $i < count($size_id); $i++) {

                            $in_size_color = [
                                'product_id' => $id,
                                'size_id' => $size_id[$i],
                                'color_id' => $color_id[$i]
                            ];

                            $product_attr_id = $this->cm->insert('product_variations', $in_size_color);
                            if (!$product_attr_id) {
                                echo json_encode(array('success' => false, 'message' => 'Failed to add the product to database please refresh the page and try again'));
                                die();
                            }
                            //** Upload product image **/
                            if (isset($_FILES['product_image' . $i]['name'])) {
                                $filesCount = count($_FILES['product_image' . $i]['name']);

                                $uploadData = array();
                                for ($j = 0; $j < $filesCount; $j++) {
                                    $_FILES['file']['name'] = $_FILES['product_image' . $i]['name'][$j];
                                    $_FILES['file']['type'] = $_FILES['product_image' . $i]['type'][$j];
                                    $_FILES['file']['tmp_name'] = $_FILES['product_image' . $i]['tmp_name'][$j];
                                    $_FILES['file']['error'] = $_FILES['product_image' . $i]['error'][$j];
                                    $_FILES['file']['size'] = $_FILES['product_image' . $i]['size'][$j];

                                    // File upload configuration
                                    $uploadPath = FCPATH . 'uploads/product_images/';
                                    $config['upload_path'] = $uploadPath;
                                    $config['allowed_types'] = 'jpg|jpeg|png';
                                    $config['encrypt_name'] = TRUE;
                                    $config['max_size'] = '2048000';
                                    $config['max_width'] = '1024';
                                    $config['max_height'] = '768';

                                    // Load and initialize upload library
                                    $this->load->library('upload', $config);
                                    $this->upload->initialize($config);

                                    // Upload file to server
                                    if ($this->upload->do_upload('file')) {
                                        // Uploaded file data
                                        $fileData = $this->upload->data();

                                        $source_path = FCPATH . 'uploads/product_images/' . $fileData['file_name'];

                                        $target_path = FCPATH . 'uploads/product_images/thumb/';
                                        $config_manip = array(
                                            'image_library' => 'gd2',
                                            'source_image' => $source_path,
                                            'new_image' => $target_path,
                                            'maintain_ratio' => TRUE,
                                            'create_thumb' => TRUE,
                                            'width' => 270,
                                            'height' => 314
                                        );

                                        $this->image_lib->initialize($config_manip);
                                        if (!$this->image_lib->resize()) {
                                            echo json_encode(array('success'=> false, 'message'=> $this->image_lib->display_errors())); die();
                                        }
                                        $this->image_lib->clear();

                                        $uploadData[$i . $j]['image'] = $fileData['file_name'];
                                        $uploadData[$i . $j]['product_id'] = $id;
                                        $uploadData[$i . $j]['color_id'] = $color_id[$i];
                                    } else {
                                        echo json_encode(array('success'=> false, 'message'=> $this->upload->display_errors())); die();
                                    }
                                }

                                if (!empty($uploadData)) {
                                    $this->db->insert_batch('product_images', $uploadData);
                                }
                            }
                        }
                    }
                    
                    echo json_encode(array('success'=> true, 'message'=> stripslashes($product_name).' Successfully update to product list.', 'mode'=> 'edit')); die();
                }
            }
        }
    }
    
    /*
        Product stock log
    */
    public function stock_log(){
        
        $data = array();
        $data = $this->data;
        $data['sub_menu'] = 'product_list';
        $data['title'] = 'Admin | Product Stock Log';
        
        $mode = $this->input->get('mode');
        if(!$mode){
            redirect('admin/product');
        }
        
        $id = $this->input->get('id');
        if($id){
            $data['data_product'] = $this->cm->select_row('products', ['id'=> $id], '');
            $data['data_list'] = $this->cm->select('product_stock_log', ['product_id'=> $id], '', 'id', 'asc');
        }
        $this->load->view('admin/product_stock_log', $data);
    }
    /*
        Get product color images
    */
    public function get_product_color_images(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {

            $product_id = $this->input->post('product_id');
            $color_id = $this->input->post('color_id');
            if($product_id && $color_id){
                $data['edit_pro_image'] = $this->cm->select('product_images', ['product_id' => $product_id, 'color_id'=> $color_id], 'id,image');
                $this->load->view('admin/ajax/product_image', $data);        
            }
        }
    }
    /*
        Remove product specification
    */
    public function remove_product_specification(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            if($this->input->post('id')){
                if($this->cm->delete('product_specification', ['id' => $this->input->post('id')])){
                    echo json_encode(['success'=> true, 'message'=> "successfully remove"]); die();
                } else {
                    echo json_encode(['success'=> false, 'message'=> "Faild to remove"]); die();
                }                
            }
        }
    }
    /*
        Remove Product images
    */
    public function remove_product_image() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {

            $id = $this->input->post('id');
            $image = $this->input->post('img', true);
            $thumb_file = explode('.', $image);
            if($id){
                
                $filename = FCPATH."uploads/product_images/".$image;
                if (file_exists($filename)){
                    unlink(FCPATH . 'uploads/product_images/' . $image);
                    unlink(FCPATH . 'uploads/product_images/thumb/' . $thumb_file[0] . '_thumb' . '.' . $thumb_file[1]);
                    if($this->cm->delete('product_images', ['id' => $id])){
                        echo json_encode(['success'=> true, 'message'=> '']);
                    } else {
                        echo json_encode(['success'=> false, 'message'=> 'Faild to delete please try again']);
                    }
                }else{
                    echo json_encode(['success'=> false, 'message'=> 'File does not exist.']);
                }
            }            
        }
    }
    /*
        Update product size
    */
    public function update_product_size(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            
            $id = $this->input->post('id');
            $size_id = $this->input->post('size_id');
            if($id && $size_id){
                $update = $this->cm->update('product_variations', ['id'=> $id], ['size_id' => $size_id]);
                if($update){
                    echo 'success'; die;
                }
            }            
        }
    }
    /*
        Update product color
    */
    public function update_product_color(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            
            $id = $this->input->post('id');
            $color_id = $this->input->post('color_id');
            if($id && $color_id){
                $update = $this->cm->update('product_variations', ['id'=> $id], ['color_id' => $color_id]);
                if($update){
                    echo 'success'; die;
                }
            }            
        }
    }
    
    /*
        Upload Product image
    */
    public function upload_product_image(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            
            $product_id = $this->input->post('product_id');
            $color_id = $this->input->post('pro_color_id');
            if($product_id && $color_id){
                $uploadPath = FCPATH . 'uploads/product_images/';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['encrypt_name'] = TRUE;
                $config['max_size'] = '2048000';
                $config['max_width'] = '1024';
                $config['max_height'] = '768';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                
                if ($this->upload->do_upload('file')) {

                    $fileData = $this->upload->data();
                    $source_path = FCPATH . 'uploads/product_images/' . $fileData['file_name'];
                    $target_path = FCPATH . 'uploads/product_images/thumb/';                    
                    $config_manip = array(
                        'image_library' => 'gd2',
                        'source_image' => $source_path,
                        'new_image' => $target_path,
                        'maintain_ratio' => TRUE,
                        'create_thumb' => TRUE,
                        'width' => 270,
                        'height' => 314
                    );
                    $this->load->library('image_lib', $config_manip);
                    $this->image_lib->resize();

                    $insert_id = $this->cm->insert('product_images', [
                        'product_id' => $product_id,
                        'image' => $fileData['file_name'],
                        'color_id' => $color_id
                    ]);
                    if($insert_id){
                        
                        $full_image = base_url() . 'uploads/product_images/thumb/' . explode('.',$fileData['file_name'])[0].'_thumb.'.explode('.',$fileData['file_name'])[1];
                        $html = '<div class="modal-imgwith-icon">
                                    <a href="javascript:void(0)" id="' . $insert_id . '" data-img="' . $fileData['file_name'] . '" class="remove-productImage" title="Remove"><i class="fa fa-close"></i></a>
                                    <div class="modals-img"><img src="' . $full_image . '"></div>
                                </div>';
                        echo json_encode(['success'=> true, 'html'=> $html]); die();
                    }
                } else {
                    echo json_encode(['success'=> false, 'message'=> $this->upload->display_errors()]); die();
                }
            }
        }
    }
    
    /*
        Remove product Size Color and Image section
    */
    public function remove_product_variation(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $id = $this->input->post('id');
            $product_id = $this->input->post('product_id');
            $color_id = $this->input->post('color_id');
            if($id && $product_id && $color_id){
                $this->cm->delete('product_variations', ['id' => $id]);
                $product_images = $this->cm->select('product_images', ['product_id' => $product_id, 'color_id'=> $color_id], '');
                if($product_images){
                    foreach($product_images as $img){
                        if (file_exists(FCPATH.'uploads/product_images/'.$img['image'])){
                            $image = explode('.',$img['image']);
                            unlink(FCPATH . 'uploads/product_images/' . $img['image']);
                            unlink(FCPATH . 'uploads/product_images/thumb/' . $image[0] . '_thumb' . '.' . $image[1]);
                        }
                    }
                    $this->cm->delete('product_images', ['product_id' => $product_id, 'color_id'=> $color_id]);
                }
                echo json_encode(['success'=> true, 'message'=> 'successfully delete']);
            }
        }
    }
    
    /*
        Manage Product Brands
        */
    public function brands(){
        $data = $this->data = array(
            'menu' => 'brand'
        );
        $data['sub_menu'] = 'brand_list';
        $data['title'] = 'Admin | Brands';
        $data['data_list'] = $this->cm->select('product_brands', [], '', 'id', 'desc');        
        $this->load->view('admin/brands', $data);
    }
    
    public function add_brand(){
        
        $mode = $this->input->get('mode');
        if(!$mode){
            redirect('admin/product/sub-categories');
        }
        
        $data = $this->data = array(
            'menu' => 'brand'
        );
        $data['sub_menu'] = 'brand_list';
        $data['title'] = 'Admin | Brands';
        
        $edit_id = $this->input->get('id');
        if($edit_id){            
            $data['edit_data'] = $this->cm->select_row('product_brands', ['id' => $edit_id], '');
        }
        
        $data['post_attr'] = ['mode'=> $mode, 'id'=> $edit_id];
        $this->load->view('admin/add_brand', $data);
    }
    
    public function receive_brand_form_data(){
                
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            
            $mode = $this->input->post('mode');
            $name = htmlentities(addslashes($this->input->post('name')));
            
            if($mode == 'add'){
                
                if ($this->cm->select_row('product_brands', ['LOWER(name)' => strtolower($name)], 'id')) {                   
                    echo json_encode(array('success'=> false, 'message'=> $name. ' already exist.')); die();
                } else {
                    
                    $slug = slugfy($name);
                    $in_array = [
                        'name' => ucwords($name),
                        'slug' => $slug
                    ];

                    $insert_id = $this->cm->insert('product_brands', $in_array);
                    if($insert_id){
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully added Brand.')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Faild to added Brand')); die();
                    }
                }
            } 
            
            if($mode == 'edit'){
                
                $id = $this->input->post('id');
                if ($this->cm->select_row('product_brands', ['LOWER(name)' => strtolower($name), 'id !=' => $id], 'id')) {                
                    echo json_encode(array('success'=> false, 'message'=> $name. ' already exist.')); die();
                } else {
                    
                    $slug = slugfy($name);
                    $in_array = [
                        'name' => ucwords($name),
                        'slug' => $slug
                    ];
                    $up_status = $this->cm->update('product_brands', ['id'=> $id], $in_array);
                    if($up_status){
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully update Brand.')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Nothing to change, please try again.')); die();
                    }
                }
            }
        }
    }
    
    /*
        Manage Product Colors
        */
    public function colors(){
        $data = $this->data = array(
            'menu' => 'color_size'
        );
        $data['sub_menu'] = 'color_list';
        $data['title'] = 'Admin | Colors';
        $data['data_list'] = $this->cm->select('product_colors', ['id!='=> 0], '', 'id', 'desc');        
        $this->load->view('admin/colors', $data);
    }
    
    public function add_color(){
        
        $mode = $this->input->get('mode');
        if(!$mode){
            redirect('admin/product/colors');
        }
        
        $data = $this->data = array(
            'menu' => 'color_size'
        );
        $data['sub_menu'] = 'color_list';
        $data['title'] = 'Admin | Colors';
        
        $edit_id = $this->input->get('id');
        if($edit_id){            
            $data['edit_data'] = $this->cm->select_row('product_colors', ['id' => $edit_id], '');
        }
        
        $data['post_attr'] = ['mode'=> $mode, 'id'=> $edit_id];
        $this->load->view('admin/add_color', $data);
    }
    
    public function receive_color_form_data(){
                
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            
            $mode = $this->input->post('mode');
            $name = htmlentities(addslashes($this->input->post('name')));
            $color_code = addslashes($this->input->post('code'));
            
            if($mode == 'add'){
                
                if ($this->cm->select_row('product_colors', ['LOWER(name)' => strtolower($name)], 'id')) {                   
                    echo json_encode(array('success'=> false, 'message'=> $name. ' already exist.')); die();
                } else {
                    
                    $in_array = [
                        'name' => ucwords($name),
                        'color_code' => $color_code
                    ];

                    $insert_id = $this->cm->insert('product_colors', $in_array);
                    if($insert_id){
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully added Color.')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Faild to added Color')); die();
                    }
                }
            } 
            
            if($mode == 'edit'){
                
                $id = $this->input->post('id');
                if ($this->cm->select_row('product_colors', ['LOWER(name)' => strtolower($name), 'id !=' => $id], 'id')) {                
                    echo json_encode(array('success'=> false, 'message'=> $name. ' already exist.')); die();
                } else {
                    
                    $in_array = [
                        'name' => ucwords($name),
                        'color_code' => $color_code
                    ];
                    $up_status = $this->cm->update('product_colors', ['id'=> $id], $in_array);
                    if($up_status){
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully update Color.')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Nothing to change, please try again.')); die();
                    }
                }
            }
        }
    }
    
    /*
        Manage Product Size
        */
    public function sizes(){
        $data = $this->data = array(
            'menu' => 'color_size'
        );
        $data['sub_menu'] = 'size_list';
        $data['title'] = 'Admin | Sizes';
        $data['data_list'] = $this->cm->select('product_sizes', [], '', 'id', 'desc');        
        $this->load->view('admin/sizes', $data);
    }
    
    public function add_size(){
        
        $mode = $this->input->get('mode');
        if(!$mode){
            redirect('admin/product/sizes');
        }
        
        $data = $this->data = array(
            'menu' => 'color_size'
        );
        $data['sub_menu'] = 'size_list';
        $data['title'] = 'Admin | Sizes';
        
        $edit_id = $this->input->get('id');
        if($edit_id){            
            $data['edit_data'] = $this->cm->select_row('product_sizes', ['id' => $edit_id], '');
        }
        
        $data['post_attr'] = ['mode'=> $mode, 'id'=> $edit_id];
        $this->load->view('admin/add_size', $data);
    }
    
    public function receive_size_form_data(){
                
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            
            $mode = $this->input->post('mode');
            $name = htmlentities(addslashes($this->input->post('name')));
            
            if($mode == 'add'){
                
                if ($this->cm->select_row('product_sizes', ['LOWER(name)' => strtolower($name)], 'id')) {                   
                    echo json_encode(array('success'=> false, 'message'=> $name. ' already exist.')); die();
                } else {
                    
                    $in_array = [
                        'name' => ucwords($name)
                    ];

                    $insert_id = $this->cm->insert('product_sizes', $in_array);
                    if($insert_id){
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully added Size.')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Faild to added Size')); die();
                    }
                }
            } 
            
            if($mode == 'edit'){
                
                $id = $this->input->post('id');
                if ($this->cm->select_row('product_sizes', ['LOWER(name)' => strtolower($name), 'id !=' => $id], 'id')) {                
                    echo json_encode(array('success'=> false, 'message'=> $name. ' already exist.')); die();
                } else {
                    
                    $in_array = [
                        'name' => ucwords($name)
                    ];
                    $up_status = $this->cm->update('product_sizes', ['id'=> $id], $in_array);
                    if($up_status){
                        echo json_encode(array('success'=> true, 'message'=> 'Successfully update Size.')); die();
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> 'Nothing to change, please try again.')); die();
                    }
                }
            }
        }
    }
    /*
        Manage Category wise product in Home page    
    */
    public function product_in_category(){
        $data = $this->data;
        $data['sub_menu'] = 'product_in_category';
        $data['title'] = 'Admin | Category wise product in Home page';
        
        $data['first_box'] = $this->cm->select_row('bulk_menu', ['id'=> 1], '');
        $data['second_box'] = $this->cm->select_row('bulk_menu', ['id'=> 2], '');       
        $data['therd_box'] = $this->cm->select_row('bulk_menu', ['id'=> 3], '');       
        $data['forth_box'] = $this->cm->select_row('bulk_menu', ['id'=> 4], '');       
        $this->load->view('admin/product_in_category', $data);
    }
    public function edit_product_in_category(){
        
        $data = $this->data;
        $data['sub_menu'] = 'product_in_category';
        $data['title'] = 'Admin | Category wise product in Home page';
        
        $edit_id = $this->input->get('id');
        if($edit_id){            
            $data['edit_data'] = $this->cm->select_row('bulk_menu', ['id' => $edit_id], '');
            $join[] = ['table' => 'view_product_listing v', 'on' => 'bp.product_id = v.id', 'type' => 'inner'];
            $data['edit_product'] = $this->cm->select('bulk_products bp', ['bp.bulk_id'=> $edit_id], 'v.*', '', '', $join);
            $data['data_product_list'] = $this->cm->select('view_product_listing', ['cat_id'=> $data['edit_data']['cat_id'], 'sub_cat_id'=> $data['edit_data']['sub_cat_id']], '', '', '');
            $data['data_sub_cat'] = $this->cm->select('sub_categories', ['status'=> 1, 'cat_id'=> $data['edit_data']['cat_id']], '', 'name', 'asc');
        }
        
        $data['data_cat_list'] = $this->cm->select('categories', ['status'=> 1], '', 'name', 'asc');
        $data['post_attr'] = ['mode'=> 'edit', 'id'=> $edit_id];
        $this->load->view('admin/edit_bulk_menu', $data);
    }
    /*
        Get product By Cat ID and Sub cat ID
    */
    public function getProductByCatIdSubCatId(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $cat_id = $this->input->post('cat_id');
            $sub_cat_id = $this->input->post('sub_cat_id');
            if($cat_id && $sub_cat_id){
                $dropdown_option = array();
                $product_data = $this->cm->select('view_product_listing', ['status'=> 1, 'cat_id'=> $cat_id, 'sub_cat_id'=> $sub_cat_id], '', 'name', 'asc');
                if($product_data){
                    foreach($product_data as $pro){
                        $dropdown_option[] = [
                            'id' => $pro['id'],
                            'text' => $pro['name'],
                            'html' => '<div style="color:red">'.$pro['name'].'</div><div><small>Brand: '.$pro['product_brand'].'</small></div>',
                            'title' => $pro['name']
                        ];
                    }
                }  
                if(!$dropdown_option){
                    echo json_encode(['success'=> false, 'message'=> 'product not found!', 'data'=> '']); die;
                }
                echo json_encode(['success'=> true, 'message'=> '', 'data'=> $dropdown_option]); die;
            }
        }
    }
    /*
        get home page product customize data
    */
    public function receive_bulk_product_set_for_home_page(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $mode = $this->input->post('mode');
            $title = $this->input->post('title');
            $id = $this->input->post('id');
            $cat_id = $this->input->post('cat_id');
            $sub_cat_id = $this->input->post('sub_cat_id');
            $product_id = $this->input->post('product_id');
            if(!$id){
                echo json_encode(['success'=> false, 'message'=> 'id is missing please try again or refresh this page!']); die;
            }
            if(!$title){
                echo json_encode(['success'=> false, 'message'=> 'title is missing!']); die;
            }
            if(!$cat_id){
                echo json_encode(['success'=> false, 'message'=> 'please select category!']); die;
            }
            if(!$sub_cat_id){
                echo json_encode(['success'=> false, 'message'=> 'please select sub category!']); die;
            }
            if(!$product_id){
                echo json_encode(['success'=> false, 'message'=> 'please select product!']); die;
            }
            if($mode == 'edit'){
                $slug = slugfy($title);
                $in_array = [
                    'title' => ucwords($title),
                    'slug' => $slug,
                    'cat_id' => $cat_id,
                    'sub_cat_id' => $sub_cat_id
                ];
                $update = $this->cm->update('bulk_menu', ['id'=> $id], $in_array);
                if($update){
                    if($this->cm->select_row('bulk_menu', ['slug'=> $slug, 'id!='=> $id], 'id')){
                        $this->cm->update('bulk_menu', ['id'=> $id], ['slug'=> $slug.'-'.$id]);
                    }
                }
                //upload image
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
                        $this->cm->update('bulk_menu', ['id'=> $id], ['image'=> $file_name]);
                    } else {
                        echo json_encode(array('success'=> false, 'message'=> $this->upload->display_errors())); die();
                    }
                }
                //insert bulk product
                $product_ids = array();
                for($i=0; $i<count($product_id); $i++){
                    $product_ids[] = [
                        'bulk_id' => $id,
                        'product_id' => $product_id[$i]
                    ];
                }
                if(!empty($product_ids)){
                    $this->cm->delete('bulk_products', ['bulk_id' => $id]);
                    $insert_batch = $this->db->insert_batch('bulk_products', $product_ids);
                    if(!$insert_batch){
                        echo json_encode(array('success'=> false, 'message'=> 'Faild to update!')); die();
                    }
                    echo json_encode(array('success'=> true, 'message'=> 'Successfully update.')); die();
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