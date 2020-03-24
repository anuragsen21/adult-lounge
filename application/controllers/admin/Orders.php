<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {
    
    public $data = array();
    
    function __construct() {
        parent::__construct();
        
        if(!$this->session->userdata('user_logged_in')){
            redirect(base_url().'admin');
        }
        
        $this->load->model(array('Common_model' => 'cm'));
        $this->data = array(
            'menu' => 'orders'
        );
        
    }
    /*
        Orders Listing
        */
    public function index() { 
        
        $data = array();
        $data = $this->data;
        $data['sub_menu'] = 'order_list';
        $data['title'] = 'Admin | Orders';
        
        $this->session->set_userdata('filter_order_list', '');
        $select_value = array();
        $where_clause = array();
        $queries = array(); //filter queries string
        //get query string from url
        parse_str($_SERVER['QUERY_STRING'], $queries);
        if($queries){
            $select_value = [
                'start_date' => $queries['start_date'],
                'end_date' => $queries['end_date'],
                'status' => $queries['status']
            ];
            if($queries['status'] == '' && $queries['start_date'] == '' && $queries['end_date'] == ''){
                redirect(base_url().'admin/orders');
            }
            if(array_key_exists("status", $queries) || array_key_exists("start_date", $queries)){
                if($queries['status']){
                    $where_clause = [
                        'od.status' => $queries['status']
                    ];
                }
                if($queries['start_date'] !='' && $queries['end_date'] !=''){
                    $where_clause += [
                        'DATE_FORMAT(o.created_at, "%Y-%m-%d") >=' => date('Y-m-d', strtotime($queries['start_date'])),
                        'DATE_FORMAT(o.created_at, "%Y-%m-%d") <=' => date('Y-m-d', strtotime($queries['end_date']))
                    ];
                } elseif($queries['start_date'] !=''){
                    $where_clause += [
                        'DATE_FORMAT(o.created_at, "%Y-%m-%d") =' => date('Y-m-d', strtotime($queries['start_date']))
                    ];
                }
            }
        }
        $data['select_value'] = $select_value;
        
        //print_r($queries).'<br>';
        //print_r($where_clause); //die;
        
        /*
        SELECT o.*,od.product_id,od.product_variation_id,od.quantity,od.sell_price,od.discount_percentage,od.discount_price,od.status order_status,p.name product_name,p.slug product_slug,pb.name product_brand,ps.name product_size,pc.name product_color,c.name product_category,u.name order_by,od.created_at order_at 
        FROM user_orders o
        join user_order_details od on o.id = od.order_id
        join products p on od.product_id = p.id
        join product_variations pv on p.id = pv.product_id
        join product_sizes ps on pv.size_id = ps.id
        join product_colors pc on pv.color_id = pc.id
        join product_brands pb on p.brand_id = pb.id
        join categories c on p.cat_id = c.id
        join users u on o.user_id = u.id
        */
        $join[] = ['table' => 'user_order_details od', 'on' => 'o.id = od.order_id', 'type' => 'inner'];
        $join[] = ['table' => 'products p', 'on' => 'od.product_id = p.id', 'type' => 'inner'];
        $join[] = ['table' => 'product_brands pb', 'on' => 'p.brand_id = pb.id', 'type' => 'inner'];
        $join[] = ['table' => 'categories c', 'on' => 'p.cat_id = c.id', 'type' => 'inner'];
        $join[] = ['table' => 'users u', 'on' => 'o.user_id = u.id', 'type' => 'inner'];
        $data['data_list'] = $this->cm->select('user_orders o', $where_clause, 'o.*,od.id order_details_id,od.product_id,od.quantity,od.sell_price,od.discount_percentage,od.discount_price,od.status order_status,p.name product_name,p.slug product_slug,p.primary_image,pb.name product_brand,od.product_size,od.product_color,c.name product_category,u.name order_by,od.created_at order_at', 'o.id', 'desc', $join);
        
        //store filter value in session
        if(!empty($where_clause) && !empty($data['data_list'])){
            $this->session->set_userdata('filter_order_list', $where_clause);
        }
        //echo $this->db->last_query(); die;
        $this->load->view('admin/orders', $data);	
    }
    /*
        Excel export order list filter wise
    */
    public function order_export_excel(){
        
        $data_list = array();
        $filter_data = $this->session->userdata('filter_order_list');
        if($filter_data){
            $join[] = ['table' => 'user_order_details od', 'on' => 'o.id = od.order_id', 'type' => 'inner'];
            $join[] = ['table' => 'products p', 'on' => 'od.product_id = p.id', 'type' => 'inner'];
            $join[] = ['table' => 'product_brands pb', 'on' => 'p.brand_id = pb.id', 'type' => 'inner'];
            $join[] = ['table' => 'categories c', 'on' => 'p.cat_id = c.id', 'type' => 'inner'];
            $join[] = ['table' => 'users u', 'on' => 'o.user_id = u.id', 'type' => 'inner'];
            $data_list = $this->cm->select('user_orders o', $filter_data, 'o.*,od.id order_details_id,od.product_id,od.quantity,od.sell_price,od.discount_percentage,od.discount_price,od.status order_status,p.name product_name,p.slug product_slug,p.primary_image,pb.name product_brand,od.product_size,od.product_color,c.name product_category,u.name order_by,od.created_at order_at', 'o.id', 'desc', $join);
        }
        if($data_list){
            $this->load->library('excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            // set Header
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Order ID');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Payment mode');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Order amount');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Payment status');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Product name');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Brand');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Product size');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Product color');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Quantity');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Sell price');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Discount percentage');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Discount price');
            $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Order status');
            // set Row
            $rowCount = 2;
            foreach ($data_list as $val) 
            {
                $order_status = '';
                if($val['order_status'] == 0){
                    $order_status = 'Order Placed';
                } elseif($val['order_status'] == 1){
                    $order_status = 'Order Receive';
                } elseif($val['order_status'] == 2){
                    $order_status = 'Out for Delivery';
                } elseif($val['order_status'] == 3){ 
                    $order_status = 'Delivered';
                } elseif($val['order_status'] == 4){ 
                    $order_status = 'Cancelled';
                } elseif($val['order_status'] == 5){
                    $order_status = 'Request for Return';
                } elseif($val['order_status'] == 6){
                    $order_status = 'Returned'; 
                } elseif($val['order_status'] == 7){
                    $order_status = 'Closed'; 
                }
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $val['order_at']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $val['order_no']);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $val['payment_mode']);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $val['total_amount']);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $val['payment_status']);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $val['product_name']);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $val['product_brand']);
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $val['product_size']);
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $val['product_color']);
                $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $val['quantity']);
                $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($val['sell_price'], 2));
                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $val['discount_percentage']);
                $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, number_format($val['discount_price'], 2));
                $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $order_status);
                $rowCount++;
            }

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            // create file name
            $fileName = 'Orders-'.date('Y-m-d').'.xlsx';
            $objWriter->save($fileName);
            // download file
            header("Content-Type: application/vnd.ms-excel");
            redirect(site_url().$fileName);
        }
    }
    /*
        Get order details by ajax
    */
    public function get_order_details(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else { 
            $data = array();
            $order_id = $this->input->post('order_id');
            $order_details_id = $this->input->post('order_details_id');   
            if($order_id && $order_details_id){
                $join[] = ['table' => 'user_order_details od', 'on' => 'o.id = od.order_id', 'type' => 'inner'];
                $join[] = ['table' => 'products p', 'on' => 'od.product_id = p.id', 'type' => 'inner'];
                $join[] = ['table' => 'product_variations pv', 'on' => 'p.id = pv.product_id', 'type' => 'inner'];
                $join[] = ['table' => 'product_brands pb', 'on' => 'p.brand_id = pb.id', 'type' => 'inner'];
                $join[] = ['table' => 'categories c', 'on' => 'p.cat_id = c.id', 'type' => 'inner'];
                $join[] = ['table' => 'sub_categories sc', 'on' => 'p.sub_cat_id = sc.id', 'type' => 'inner'];
                $join[] = ['table' => 'users u', 'on' => 'o.user_id = u.id', 'type' => 'inner'];
                $data['order_details'] = $this->cm->select_row('user_orders o', ['od.id'=> $order_details_id, 'od.order_id'=> $order_id], 'o.*,od.id    order_details_id,od.product_id,od.quantity,od.sell_price,od.discount_percentage,od.discount_price,od.status order_status,p.name product_name,p.slug product_slug,p.primary_image,pb.name product_brand,od.product_size,od.product_color,c.slug cat_slug,sc.slug sub_cat_slug,u.name order_by,u.email user_email,u.phone user_phone,u.gender,od.created_at order_at', $join);
                $html = $this->load->view('admin/ajax/order_details', $data, TRUE);
                echo json_encode(['success'=> true, 'html'=> $html]);
            }
        }
    }
    /*
        Update Order Status by AJAX
    */
    public function update_order_status(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $order_id = $this->input->post('order_id');
            $order_dtls_id = $this->input->post('order_dtls_id');
            $order_status = $this->input->post('order_status');
            
            if($order_id && $order_dtls_id && $order_status){
                $status_update = $this->cm->update('user_order_details', ['order_id'=> $order_id, 'id'=> $order_dtls_id], [
                        'status' => $order_status
                    ]);
                if($status_update){
                    $this->cm->insert('user_order_logs', [
                            'order_id' => $order_id,
                            'order_details_id' => $order_dtls_id,
                            'status' => $order_status
                        ]);
                    
                    /*
                        SELECT o.order_no,p.name,ps.name size,pc.name color,od.quantity,od.sell_price,od.discount_percentage,od.discount_price,od.remarks,od.status
                        FROM user_order_details od
                        join user_orders o on od.order_id = o.id
                        join products p on od.product_id = p.id
                        join product_variations pv on p.id = pv.product_id
                        join product_sizes ps on pv.size_id = ps.id
                        join product_colors pc on pv.color_id = pc.id
                        WHERE od.id = 1 and od.order_id = 1
                    */
                    /*
                        Order details
                    */
                    $oJoin[] = ['table' => 'user_orders o', 'on' => 'od.order_id = o.id', 'type' => 'inner'];
                    $oJoin[] = ['table' => 'products p', 'on' => 'od.product_id = p.id', 'type' => 'inner'];
                    $oJoin[] = ['table' => 'product_variations pv', 'on' => 'p.id = pv.product_id', 'type' => 'inner'];
                    $oJoin[] = ['table' => 'product_sizes ps', 'on' => 'pv.size_id = ps.id', 'type' => 'inner'];
                    $oJoin[] = ['table' => 'product_colors pc', 'on' => 'pv.color_id = pc.id', 'type' => 'inner'];
                    $oJoin[] = ['table' => 'users u', 'on' => 'o.user_id = u.id', 'type' => 'inner'];
                    $order_details = $this->cm->select_row('user_order_details od', ['od.id'=> $order_dtls_id, 'od.order_id'=> $order_id], 'o.order_no,p.name,ps.name size,pc.name color,od.quantity,od.sell_price,od.discount_percentage,od.discount_price,od.status,u.name user_name,u.email user_email', $oJoin);
                    //Order status dropdown option
                    $order_status_option = '<option value="">Update status</option>';
                    if($order_status == 1){
                        //order confirm mail sent to user
                        if($order_details){
                            if($order_details['discount_percentage'] > 0){ 
                                $discount_price = $order_details['sell_price']- (($order_details['sell_price'] * $order_details['discount_percentage']) / 100); 
                            } else {
                                $discount_price = $order_details['sell_price'];
                            }
                            
                            $mail_subject = 'Your order for '.$order_details['name'].' has been successfully receive';
                            $message = '<p>Hi <b>'.$order_details['user_name'].'</b></p>';
                            $message .= "<p>Your order has been successfully receive</p>";
                            $message .= "<p></p><p>Order ID: <b>".$order_details['order_no']."</b></p>";
                            $message .= "<p></p><p>Item: ".$order_details['name']."</p>";           
                            $message .= "<p>Size: ".$order_details['size']."</p>";           
                            $message .= "<p>Color: ".$order_details['color']."</p>";           
                            $message .= "<p>Qty: ".$order_details['quantity']."</p>";           
                            $message .= "<p>Amount payable on delivery: <b>£".number_format($discount_price, 2)."</b></p>";           
                            $message .= "<p></p><p>Thank you for shopping with Shorolafashion</p>"; 
                            $this->cm->send_email($order_details['user_email'], siteSettingsData()['site_sender_email'],'','', $mail_subject, $message,'','','');
                        }                        
                        //dropdown option
                        $order_status_option .= '<option value="2">Out for delivery</option>';
                        $order_status_option .= '<option value="4">Order Cancel</option>';
                    }elseif($order_status == 2){
                        //order out for delivery mail sent to user
                        if($order_details){
                            if($order_details['discount_percentage'] > 0){ 
                                $discount_price = $order_details['sell_price']- (($order_details['sell_price'] * $order_details['discount_percentage']) / 100); 
                            } else {
                                $discount_price = $order_details['sell_price'];
                            }
                            
                            $mail_subject = 'Out for Delivery '.$order_details['name'].' with Order ID '.$order_details['order_no'];
                            $message = '<p>Hi <b>'.$order_details['user_name'].'</b></p>';
                            $message .= "<p></p><p>Item: ".$order_details['name']."</p>";           
                            $message .= "<p>Size: ".$order_details['size']."</p>";           
                            $message .= "<p>Color: ".$order_details['color']."</p>";           
                            $message .= "<p>Qty: ".$order_details['quantity']."</p>";           
                            $message .= "<p>Amount payable on delivery: <b>£".number_format($discount_price, 2)."</b></p>"; 
                            $this->cm->send_email($order_details['user_email'], siteSettingsData()['site_sender_email'],'','', $mail_subject, $message,'','','');
                        }                        
                        //dropdown option
                        $order_status_option .= '<option value="3">Order Delivered</option>';
                        $order_status_option .= '<option value="4">Order Cancel</option>';
                    } elseif($order_status == 3){
                        //order delivered mail sent to user
                        if($order_details){
                            if($order_details['discount_percentage'] > 0){ 
                                $discount_price = $order_details['sell_price']- (($order_details['sell_price'] * $order_details['discount_percentage']) / 100); 
                            } else {
                                $discount_price = $order_details['sell_price'];
                            }
                            
                            $mail_subject = 'Delivered '.$order_details['name'].' with Order ID '.$order_details['order_no'];
                            $message = '<p>Hi <b>'.$order_details['user_name'].'</b></p>';
                            $message .= "<p></p><p>Item: ".$order_details['name']."</p>";           
                            $message .= "<p>Size: ".$order_details['size']."</p>";           
                            $message .= "<p>Color: ".$order_details['color']."</p>"; 
                            $this->cm->send_email($order_details['user_email'], siteSettingsData()['site_sender_email'],'','', $mail_subject, $message,'','','');
                        }                        
                        //dropdown option
                        $order_status_option .= '<option value="7">Closed</option>';
                    } elseif($order_status == 6){
                        $order_status_option .= '<option value="7">Closed</option>';
                    }
                    
                    echo json_encode(['success'=> true, 'message'=> 'Successfully Change Order Status', 'html'=> $order_status_option]);
                }
            }
        }
    }
    /*
        Update cancel reason
    */
    public function update_cancel_reason(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $order_id = $this->input->post('order_id');
            $order_dtls_id = $this->input->post('order_dtls_id');
            $order_status = $this->input->post('order_status');
            if($order_id && $order_dtls_id && $order_status){
                $status_update = $this->cm->update('user_order_details', ['order_id'=> $order_id, 'id'=> $order_dtls_id], [
                            'status' => $order_status
                        ]);
                if($status_update){                    
                    $this->cm->insert('user_order_logs', [
                            'order_id' => $order_id,
                            'order_details_id' => $order_dtls_id,
                            'status' => $order_status,
                            'remarks' => addslashes($this->input->post('remarks'))
                        ]);
                    /*
                        Order details
                    */
                    $oJoin[] = ['table' => 'user_orders o', 'on' => 'od.order_id = o.id', 'type' => 'inner'];
                    $oJoin[] = ['table' => 'products p', 'on' => 'od.product_id = p.id', 'type' => 'inner'];
                    $oJoin[] = ['table' => 'product_variations pv', 'on' => 'p.id = pv.product_id', 'type' => 'inner'];
                    $oJoin[] = ['table' => 'product_sizes ps', 'on' => 'pv.size_id = ps.id', 'type' => 'inner'];
                    $oJoin[] = ['table' => 'product_colors pc', 'on' => 'pv.color_id = pc.id', 'type' => 'inner'];
                    $oJoin[] = ['table' => 'users u', 'on' => 'o.user_id = u.id', 'type' => 'inner'];
                    $order_details = $this->cm->select_row('user_order_details od', ['od.id'=> $order_dtls_id, 'od.order_id'=> $order_id], 'o.order_no,p.name,ps.name size,pc.name color,od.quantity,od.sell_price,od.discount_percentage,od.discount_price,od.remarks,od.status,u.name user_name,u.email user_email', $oJoin);
                    //order status dropdown option
                    $order_status_option = '';
                    $display_message = '';
                    if($order_status == 4){
                        //order cancel mail sent to user
                        if($order_details){
                            if($order_details['discount_percentage'] > 0){ 
                                $discount_price = $order_details['sell_price']- (($order_details['sell_price'] * $order_details['discount_percentage']) / 100); 
                            } else {
                                $discount_price = $order_details['sell_price'];
                            }
                            
                            $mail_subject = $order_details['quantity'].' item from your order '.$order_details['order_no'].' has been cancelled';
                            $message = '<p>Hi <b>'.$order_details['user_name'].'</b></p>';
                            $message .= "<p>We are sorry to let you know that your order ".$order_details['order_no']." for the below listed item has been cancelled</p>";
                            $message .= "<p></p><p>Item: ".$order_details['name']."</p>";           
                            $message .= "<p>Size: ".$order_details['size']."</p>";           
                            $message .= "<p>Color: ".$order_details['color']."</p>";           
                            $message .= "<p>Qty: ".$order_details['quantity']."</p>";           
                            $message .= "<p>Price: <b>£".number_format($discount_price, 2)."</b></p>";
                            $message .= "<p></p><p>".$order_details['remarks']."</p>";
                            $this->cm->send_email($order_details['user_email'], siteSettingsData()['site_sender_email'],'','', $mail_subject, $message,'','','');
                        }
                        //dropdown option
                        $order_status_option .= '<option value="">Update status</option>';
                        $order_status_option .= '<option value="7">Closed</option>';
                        $display_message = 'Successfully cancel this order';
                    } elseif($order_status == 7){
                        $order_status_option .= '<small class="label bg-red">Closed</small>';
                        $display_message = 'Successfully closed this order';
                    }
                    
                    echo json_encode(['success'=> true, 'message'=> $display_message, 'html'=> $order_status_option]);
                }
            }
        }
    }
    /*
        order log
    */
    public function order_log(){
        
        $data = array();
        $data = $this->data;
        $data['sub_menu'] = 'order_list';
        $data['title'] = 'Admin | Order Log';
        
        $order_id = $this->uri->segment(4);
        $order_dtls_id = $this->uri->segment(5);
        $order_no = $this->uri->segment(6);
        if($order_id && $order_dtls_id && $order_no){
            //order details
            $join[] = ['table' => 'products p', 'on' => 'od.product_id = p.id', 'type' => 'inner'];
            $join[] = ['table' => 'product_brands pb', 'on' => 'p.brand_id = pb.id', 'type' => 'inner'];
            $join[] = ['table' => 'product_variations pv', 'on' => 'p.id = pv.product_id', 'type' => 'inner'];
            $join[] = ['table' => 'product_sizes ps', 'on' => 'pv.size_id = ps.id', 'type' => 'inner'];
            $join[] = ['table' => 'product_colors pc', 'on' => 'pv.color_id = pc.id', 'type' => 'inner'];
            $data['order_details'] = $this->cm->select_row('user_order_details od', ['od.id'=> $order_dtls_id], 'p.name,ps.name size,pc.name color,pb.name brand,od.quantity,od.sell_price,od.discount_percentage,od.discount_price', $join);
            //get order log
            $order_log_data = $this->cm->select('user_order_logs l', ['l.order_id'=> $order_id, 'l.order_details_id'=> $order_dtls_id], '', 'id', 'asc', '', '', '', 'DATE_FORMAT(l.created_at, "%Y-%m-%d")');
            if($order_log_data){
                $order_log_array = array();
                foreach($order_log_data as $order_log){
                    $order_more_log = array();
                    $order_logs_data = $this->cm->select('user_order_logs l', [
                        'l.order_id'=> $order_log['order_id'], 
                        'l.order_details_id'=> $order_log['order_details_id'], 
                        'DATE_FORMAT(l.created_at, "%Y-%m-%d") ='=> date('Y-m-d', strtotime($order_log['created_at']))
                    ], '', 'id', 'asc');
                    if($order_logs_data){
                        foreach($order_logs_data as $logs){
                            $order_more_log[] = [
                                'id' => $logs['id'],
                                'status' => $logs['status'],
                                'remarks' => $logs['remarks'],
                                'created_at' => $logs['created_at']
                            ];
                        }
                    }
                    $order_log_array[] = [
                        'status' => $order_log['status'],
                        'created_at' => $order_log['created_at'],
                        'order_logs' => $order_more_log
                    ];
                }
                /*echo '<pre>';
                print_r($order_log_array);*/
                $data['order_log_details'] = $order_log_array;
            }
            $data['order_no'] = $order_no;
            $this->load->view('admin/order_log', $data);
        }
    }
    
    
}