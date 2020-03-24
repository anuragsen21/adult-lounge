<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    
    if ( ! function_exists('slugfy')){

		function slugfy($name){
			$ci = & get_instance();            
            $text = preg_replace('~[^\pL\d]+~u', '-', $name);
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
            $text = preg_replace('~[^-\w]+~', '', $text);
            $text = trim($text, '-');
            $text = preg_replace('~-+~', '-', $text);
            $slug = strtolower($text); 
            return $slug;
		}
	}

    if ( ! function_exists('CommonCategoryList')){
        
		function CommonCategoryList(){
			$ci = & get_instance();
			$ci->load->database();
            $ci->load->model('Common_model');            
            $fatch_data = $ci->Common_model->select('categories', [], '', 'id', 'asc');
            return $fatch_data ? $fatch_data:array();
		}
	}

    if ( ! function_exists('CommonSubCategoryListById')){
        
		function CommonSubCategoryListById($catId = null){
			$ci = & get_instance();
			$ci->load->database();
            $ci->load->model('Common_model');            
            $fatch_data = $ci->Common_model->select('sub_categories', ['cat_id'=> $catId], '', 'name', 'asc');
            return $fatch_data ? $fatch_data:array();
		}
	}

    if ( ! function_exists('siteSettingsData')){        
		function siteSettingsData(){
			$ci = & get_instance();
			$ci->load->database();
            $ci->load->model('Common_model');            
            $fatch_data = $ci->Common_model->select_row('site_settings', ['id'=> 1], '');
            return $fatch_data ? $fatch_data:array();
		}
	}

    if ( ! function_exists('GetUserDetailsById')){        
		function GetUserDetailsById($userId){
			$ci = & get_instance();
			$ci->load->database();
            $ci->load->model('Common_model');            
            $fatch_data = $ci->Common_model->select_row('users', ['id'=> $userId], 'id,name,email,phone,gender,login_type,status,account_verified,created_at');
            return $fatch_data ? $fatch_data:array();
		}
	}

    if ( ! function_exists('GetUserId')){        
		function GetUserId(){
			$ci = & get_instance();
			$ci->load->library('session');
            $cookie = stripslashes($_COOKIE[LOGIN_COOKIE_NAME]);
            $savedCartArray = json_decode($cookie, true);
            return $savedCartArray['id'] ? $savedCartArray['id']:'';
		}
	}
    
    if ( ! function_exists('GetCartTotalCount')){        
		function GetCartTotalCount($cart_id){
			$ci = & get_instance();
			$ci->load->model('Common_model');
            $fatch_data = $ci->Common_model->select_row('product_cart', ['cookie_cart_id' => $cart_id], 'count(id) totalRow');
            return $fatch_data ? $fatch_data['totalRow']:array();
		}
	}
    
    if ( ! function_exists('GetUserCartDetailsByCartId')){        
		function GetUserCartDetailsByCartId($cookie_cart_id){
			$ci = & get_instance();
			$ci->load->model('Common_model');
            
            $join[] = ['table' => 'products p', 'on' => 'c.product_id = p.id', 'type' => 'inner'];
            $join[] = ['table' => 'categories cc', 'on' => 'p.cat_id = cc.id', 'type' => 'inner'];
            $join[] = ['table' => 'sub_categories sc', 'on' => 'p.sub_cat_id = sc.id', 'type' => 'inner'];
            $join[] = ['table' => 'product_variations pv', 'on' => 'c.product_variation_id = pv.id', 'type' => 'inner'];
            $join[] = ['table' => 'product_sizes ps', 'on' => 'pv.size_id = ps.id', 'type' => 'inner'];
            $join[] = ['table' => 'product_colors pc', 'on' => 'pv.color_id = pc.id', 'type' => 'inner'];
            
            $fatch_data = $ci->Common_model->select('product_cart c', ['c.cookie_cart_id'=> $cookie_cart_id], 'c.id,p.name,p.slug,cc.name category,cc.slug cat_slug,sc.name sub_category,sc.slug sub_cat_slug,p.primary_image,p.purchase_quantity,p.sell_price,p.set_discount,p.discount_percentage,c.product_id,c.product_variation_id,c.quantity,ps.name size,pc.name color', 'c.id', 'desc', $join);
            return $fatch_data ? $fatch_data:array();
		}
	}
