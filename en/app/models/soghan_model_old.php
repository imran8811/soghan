<?php

class Soghan_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function checkRecord($table, $data)
    {                 
        $query = $this->db->get_where($table, $data);
        return $query->row();      
    }
    
    function searchPost($country, $sub_cat, $start, $end='')
    { 
        $this->db->distinct();
        $this->db->select('posts.*, picture');
        if(!empty($country)){
            $this->db->where('country_name', $country);
            // $this->db->where("( camel_name LIKE '%$title%' OR father_name LIKE '%$title%' OR mother_name LIKE '%$title%' OR type_name LIKE '%$title%' OR type_name LIKE '%$title%' OR cat_name LIKE '%$title%' OR sub_cat_name LIKE '%$title%' OR Price = '$title')");
        }
        $this->db->join('pictures', 'pictures.post_id = posts.post_id', 'left');
        if(!empty($sub_cat)){
            $this->db->where('cat_name', $sub_cat);
        }
        if($start!=1){
            $this->db->limit($start, $end);
        }
        $this->db->order_by('posts.post_id', 'DESC');
        $this->db->group_by('pictures.post_id');
        $query = $this->db->get('posts');
        
        return $query->result_array();      
    }
    
    function saveRecord($table, $data)
    { 
        $this->db->insert($table, $data);
        return $this->db->insert_id(); 
    }
    
    function updateRecord($table, $where_field, $where_value, $data)
    {
        $this->db->where($where_field, $where_value);
        $this->db->update($table, $data);        
        return $this->db->affected_rows(); 
    }
    
    function deleteRecord($table, $where_field, $where_value)
    {
        $this->db->where($where_field, $where_value);
        $this->db->delete($table);
        return $this->db->affected_rows(); 
    }
    
    function getAllCountries()
    {
        $this->db->select('country_id, country_name');
        $this->db->order_by('country_name', 'ASC');
        // $this->db->order_by('sort', 'ASC');
        $query = $this->db->get('countries');
        return $query->result_array(); 
    }
    
    function getCitiesByCountry($id, $status='')
    {
        $this->db->select('cities.city_id, city_name');
        if(!empty($status)){
            $this->db->join('maidans', 'cities.city_id = maidans.city_id', 'inner');
            $this->db->order_by('sort', 'ASC');
            $this->db->group_by('cities.city_id');
        }
        $query = $this->db->get_where('cities', array('cities.country_id' => $id));
        return $query->result_array(); 
    }
    
    function getCitiesByCountryName($country)
    {
        $this->db->select('city_id, city_name');
        $this->db->join('cities', 'countries.country_id = cities.country_id', 'inner');
        $query = $this->db->get_where('countries', array('country_name' => $country));
        return $query->result_array(); 
    }
    
    function getCountryByCity($id)
    {
        $this->db->select('city_name, country_name');
        $this->db->join('countries', 'countries.country_id = cities.country_id', 'inner');
        $query = $this->db->get_where('cities', array('cities.city_id' => $id));
        return $query->row(); 
    }
        
    function getAllCities()
    {
        $this->db->select('city_id, city_name');
        $query = $this->db->get('cities');
        $query = $this->db->get('cities');
        return $query->result_array(); 
    }
    
    function getMaidanCountries()
    {
        $this->db->select('country_id, country_name');
        $this->db->order_by('sort', 'ASC');
        $query = $this->db->get_where('countries', array('maidan' => 1));
        return $query->result_array(); 
    }
    
    function getMaidans($field, $id)
    {
        $this->db->select('maidan_title, english_maidan_title');
        $this->db->order_by('maidan_id', 'ASC');
        $query = $this->db->get_where('maidans', array($field => $id));
        return $query->result_array(); 
    }
    
    function getAllCategories()
    {
        $this->db->select('cat_id, cat_name');
        $query = $this->db->get('categories');
        return $query->result_array(); 
    }
    
    function getSubCatByCat($id)
    {
        $this->db->select('sub_cat_id, sub_cat_name');
        $query = $this->db->get_where('sub_categories', array('sub_categories.cat_id' => $id));
        return $query->result_array(); 
    }
    
    function updateViews($id){
        $this->db->query("update posts set views = views + 1 where post_id = $id");
        return $this->db->affected_rows(); 
    }
    
    function getPosts($start='', $end=''){
        
        $limit = (!empty($end)) ? "limit $start, $end" : "";
        
        $query = $this->db->query("select posts.*, pictures.picture
                    from posts
                    left join pictures on pictures.post_id = posts.post_id
                    group by post_id
                    order by post_id desc
                    $limit
                ");
                
        return $query->result_array();        
    }
    
    function getPostDetail($id){
        
        $this->db->join('users', 'users.user_id = posts.user_id', 'inner');
        $this->db->join('pictures', 'pictures.post_id = posts.post_id', 'left');
        $this->db->where('posts.post_id', $id);
        $query = $this->db->get('posts');
        
        return $query->result_array();
    }
    
    function deletePost($post_id){
        
        $this->db->select('picture');
        $this->db->where('post_id', $post_id);
        $query = $this->db->get('pictures');
        $pictures = $query->result_array();
        
        foreach($pictures as $pic){
            $path = str_replace(base_url(), '', $pic['picture']); 
            unlink($path);
        }
        
        $this->db->query("delete posts, pictures, camel_videos from posts
                      left join pictures on posts.post_id = pictures.post_id
                      left join camel_videos on camel_videos.post_id = posts.post_id
                      where posts.post_id = $post_id
                    ");
        
        return $this->db->affected_rows();
    }
    
    function getVendorsList($id, $limit='', $start='')
    {
        $this->db->select('vendor_detail_id, contact_name, company_name, phone, mobile, email, description, latitude, longitude, city, area, country, image, vendor_type');
        $this->db->join('vendors', 'vendors.vendor_id = vendor_details.vendor_id', 'inner');
        if(!empty($limit)){
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get_where('vendor_details', $id);
        
        return $query->result_array();
    }
    
    
        
    
    
    
    
    //---------------- Mobile Functions ----------------\\
    
    function getAllPosts()
    {
        // $query = $this->db->query('select posts.*
                // from posts
                // order by post_id desc
            // ');
                 
        $query = $this->db->query('select posts.*, users.mobile, users.email
                    from posts
                    inner join users on users.user_id = posts.user_id
                    order by post_id desc
                ');
        
//        $query = $this->db->query('select posts.*, types.gender, sub_categories.sub_cat_name, categories.cat_name, pictures.picture
//                    from posts
//                    inner join types on types.type_id = posts.type_id
//                    inner join sub_categories on sub_categories.sub_cat_id = types.sub_cat_id
//                    inner join categories on categories.cat_id = sub_categories.cat_id
//                    inner join cities on cities.city_id = posts.city_id
//                    inner join countries on countries.country_id = cities.country_id
//                    left join pictures on pictures.post_id = posts.post_id
//                    order by post_id desc
//                ');
        
        return $query->result_array();
    }

    function getCamelPics($post_id){
        
        $this->db->select('picture as pic_camel');
        $query = $this->db->get_where('pictures', array('post_id' => $post_id));
        return $query->result_array();
    }
    
    function getCamelVideos($post_id){
        
        $query = $this->db->get_where('camel_videos', array('post_id' => $post_id));
        return $query->result_array();
    }
    
    function getLinks($end='', $start='', $lang='')
    {
        $this->db->select('link_id, '.$lang.'username, '.$lang.'company, email, phone, location, url, image, detail_image');
        if(!empty($start)){
            $this->db->limit($start, $end);
        }   
        $this->db->order_by('link_id', 'DESC');
        $query = $this->db->get('links');
        
        return $query->result_array();
    }
    
    function getLinkDetail($id)
    {
        $query = $this->db->get_where('links', array('link_id' => $id));
        return $query->row();
    }
    
    function getVendors($status='', $lang='')
    {
        if(!empty($status)){
            $this->db->select('vendors.vendor_id, '.$lang.'vendor_type, vendor_mobile_image, vendor_detail_id, '.$lang.'contact_name, '.$lang.'company_name, email, mobile, phone, latitude, longitude, '.$lang.'area, '.$lang.'city, '.$lang.'description, image');
            $this->db->join('vendor_details', 'vendors.vendor_id = vendor_details.vendor_id', 'inner');
        }else{
            $this->db->select('vendors.vendor_id, '.$lang.'vendor_type, vendor_image');
        }
        $this->db->order_by('vendor_id', 'ASC');
        $query = $this->db->get('vendors');
        
        return $query->result_array();
    }
    
    function getVendorDetail($id)
    {
        $this->db->select('contact_name, english_contact_name, company_name, english_company_name, phone, mobile, email, description, english_description, latitude, longitude, image');
        $query = $this->db->get_where('vendor_details', array('vendor_id' => $id));
        
        return $query->result_array();
    }
    
    function getEvents($date='', $status='')
    {
        $this->db->select('event_id, addedto_local_calendar, all_day, title, name, event_date, start_time, end_time, timezone, event_location, notes');
        if(!empty($date) && empty($status)){
            $this->db->where('event_date', $date);
        }        
        else if(!empty($status)){
//            $start_date = $date;
            $start_date = date('Y-m', strtotime($date)).'-01';
            $end_date   = date('Y-m', strtotime($date)).'-31';
            $this->db->where("event_date between '$start_date' and '$end_date'");
        }        
        $this->db->order_by("event_date, start_time ASC");
        $query = $this->db->get('events');
        
//        echo ($this->db->last_query());
        
        return $query->result_array();
    }
    
    function getEventDetail($id)
    {
        $query = $this->db->get_where('events', array('event_id' => $id));   
        return $query->row();
    }
    
    
    function getTokens()
    {
        $this->db->select('token');
        $query = $this->db->get('tokens');
        return $query->result_array(); 
    }

    function getAllMobileAds()
    { 
        $this->db->select('ad_id, ad_image, url, created_date');
        $this->db->order_by('sort', 'ASC');
        $query = $this->db->get('ads');
        return $query->result_array();      
    }
    
    function updateAnalytics($query)
    {
        $this->db->query($query);
        return $this->db->affected_rows(); 
    }

    function getAllSubCategories()
    {
        // $this->db->select(', arabic_country_name');
        $this->db->order_by('arabic_sub_cat_name', 'ASC');
        $query = $this->db->get('sub_categories');
        return $query->result_array();
    }
        
}