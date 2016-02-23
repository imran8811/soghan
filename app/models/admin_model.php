<?php

class Admin_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function checkRecord($table, $data)
    {                 
        $query = $this->db->get_where($table, $data);
        return $query->row();      
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
        $query = $this->db->get('countries');
        return $query->result_array(); 
    }
    
    function getCitiesByCountry($id)
    {
        $this->db->select('cities.city_id, city_name');
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
        return $query->result_array(); 
    }
    function getMaidans($field, $id)
    {
        $this->db->select('maidan_title');
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
    
    function getPosts($start, $end){

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
    
    function getAllEvents($start='', $end='')
    {
        if(!empty($start)){
            $this->db->limit($start, $end);
        }       
        $this->db->order_by('event_date', 'DESC');
        $query = $this->db->get('events');
        
        return $query->result_array();
    }
    
    function getEvent($id)
    {
        $this->db->where('event_id', $id);        
        $query = $this->db->get('events');        
        return $query->row();
    }
    
    function getAllVendors($start='', $end='')
    {
        if(!empty($start)){
            $this->db->limit($start, $end);
        }       
        $this->db->order_by('vendor_id', 'DESC');
        $query = $this->db->get('vendors');
        
        return $query->result_array();
    }
    
    function getVendor($id)
    {
        $this->db->where('vendor_id', $id);        
        $query = $this->db->get('vendors');        
        return $query->row();
    }
    
    function getAllVendorDetails($start='', $end='')
    {
        if(!empty($start)){
            $this->db->limit($start, $end);
        }       
        $this->db->order_by('vendor_detail_id', 'DESC');
        $query = $this->db->get('vendor_details');
        
        return $query->result_array();
    }
    
    function getVendorDetails($id)
    {
        $this->db->where('vendor_detail_id', $id);        
        $query = $this->db->get('vendor_details');        
        return $query->row();
    }    
    
    function getAllLinks($start='', $end='')
    {
        if(!empty($start)){
            $this->db->limit($start, $end);
        }       
        $this->db->order_by('link_id', 'DESC');
        $query = $this->db->get('links');
        
        return $query->result_array();
    }
    
    function getLink($id)
    {
        $this->db->where('link_id', $id);        
        $query = $this->db->get('links');        
        return $query->row();
    } 
    
    function getAllMaidans($start='', $end='')
    {
        $this->db->select('maidan_id, maidan_title, country_name, city_name');
        $this->db->join('countries', 'countries.country_id = maidans.country_id', 'left');
        $this->db->join('cities', 'cities.city_id = maidans.city_id', 'left');
        $this->db->order_by('maidans.country_id');
        if(!empty($start)){
            $this->db->limit($start, $end);
        }       
        $query = $this->db->get('maidans');
        
        return $query->result_array();
    }
    
    function getMaidan($id)
    {
        $this->db->select('maidans.*');
        $this->db->where('maidan_id', $id); 
        $this->db->join('countries', 'countries.country_id = maidans.country_id', 'left');
        $this->db->join('cities', 'cities.city_id = maidans.city_id', 'left');       
        $query = $this->db->get('maidans');        
        return $query->row();
    } 
    
    function getAllAds()
    { 
        $this->db->select('ad_id, ad_image, url, clicks, loads, sort, created_date');
        $this->db->order_by('sort', 'ASC');
        $query = $this->db->get('ads');
        return $query->result_array();      
    }

    
    
}