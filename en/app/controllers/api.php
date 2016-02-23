<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');
class Api extends CI_Controller {    

    public function __construct() {

        parent::__construct();

        $this->load->model('soghan_model');
        $this->load->library('session');

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }
    
    public function save_user() {

        $user_data = array(
            'first_name'    => $this->input->get_post('first_name'),
            'middle_name'   => $this->input->get_post('middle_name'),
            'family_name'   => $this->input->get_post('family_name'),
            'email'         => $this->input->get_post('email'),
            'password'      => $this->input->get_post('password'),
            'mobile'        => $this->input->get_post('mobile'),
            'country_name'  => $this->input->get_post('country_name'),
            'city_name'     => $this->input->get_post('city_name')
        );
        
        $check = $this->soghan_model->checkRecord('users', array('email' => $this->input->get_post('email')));        
        if($check){
            $result['status'] = 'error';          
            $result['msg']    = 'Email already exists';
        }
        else{
            $res = $this->soghan_model->saveRecord('users', $user_data);            
            if($res){
                // $this->load->library('encrypt');
                // $enc_email = $this->encrypt->encode($this->input->get_post('email'));
                $msg = 'Please click the given link to verfiy your email<br><a href="'. base_url() .'verification/?status='.$this->input->get_post('email').'" targer="blank">Click Here!</a>';
                
                $this->send_email($this->input->get_post('email'), $this->input->get_post('first_name'), 'Soghan Account Verification', $msg);
                             
                $result['status'] = 'success';          
                $result['msg']    = 'Successfully Signed up, Please check your email for verification';                
            }else{
                $result['status'] = 'error';
                $result['msg']    = 'Some error Occurred';       
            }            
        }
        
        header('Content-Type: application/json');
        echo json_encode($result);                
    }
    
    public function user_verify(){
        // $this->load->library('encrypt');
        // $email = $this->encrypt->decode($_GET['status']);
        $email = $_GET['status'];
        
        $check = $this->soghan_model->checkRecord('users', array('email' => $email));
        if($check){
            $this->soghan_model->updateRecord('users', 'email', $email, array('status' => 1));
            echo 'Verified';
        }
        else{
            echo 'Error';
        }
    }
    
    public function resend_email(){
        
        $check = $this->soghan_model->checkRecord('users', array('email' => $this->input->get_post('email')));        
        if($check){            
            // $this->load->library('encrypt');
            // $enc_email = $this->encrypt->encode($this->input->get_post('email'));
            $msg = 'Please click the given link to verfiy your email<br><a href="'.  base_url() .'verification/?status='.$this->input->get_post('email').'" targer="blank">Click Here!</a>';

            $this->send_email($check->email, $check->first_name, 'Soghan Account Verification', $msg);
            
            $result['status'] = 'sueccess';
            $result['msg']    = 'Please check your email';
        }
        else{
            $result['status'] = 'error';
            $result['msg']    = 'Email does not exists';
        }
        
        header('Content-Type: application/json');
        echo json_encode($result);         
    }

    public function user_login() {
        
        $login_data = array(
            'email'    => $this->input->get_post('email'),
            'password' => $this->input->get_post('password')
        );
        $res = $this->soghan_model->checkRecord('users', $login_data);
        if($res){
            if($res->status == 0){
                $result['status'] = 'pending';
                $result['msg']    = 'Not verified';
            }
            else{
                $result['status'] = 'success';
                $result['msg']    = 'User Found';
            }
        }else{
            $result['status'] = 'error';
            $result['msg']    = 'Invalid Username OR Password';
        }
        
        header('Content-Type: application/json');
        echo json_encode($result);           
    }
    
    function send_email($to, $f_name, $subject, $msg, $attachment=''){
        $this->load->library('phpmailer');
        $mail = new PHPMailer(true);
        $mail->IsSMTP();

        // local
        $mail->Host = "ssl://smtp.googlemail.com";
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->Username = "hamzasynergistics@gmail.com";
        $mail->Password = "synergistics";
        $mail->AddReplyTo('no-reply@email.com', 'Soghan');

        // live                            
//        $mail->Host = "localhost";
//        $mail->SMTPAuth = true;
//        $mail->SMTPSecure = "ssl";
//        $mail->Username = "newsletter@synergistics.ae";
//        $mail->Password = "newsletter123@";
//        $mail->Port = "465";
//        $mail->AddReplyTo('no-reply@email.com', 'Synergistics');

        $mail->AddAddress($to, $f_name);
        $mail->SetFrom('info@synergistics.ae', 'Soghan');
        $mail->Subject = $subject;
        $body = $msg;
                
        if(!empty($attachment)){
            $mail->AddAttachment($attachment);
        } 
        $mail->MsgHTML($body);
        $mail->Send();
    }
    
    public function save_post() {
        
        $user_id = $this->soghan_model->checkRecord('users', array('email' => $this->input->get_post('user_email'), 'status' => 1));
        if($user_id){
            if($user_id->status == 0){
                $result['status'] = error;
                $result['msg']    = 'Account not activated';
            }
            else{    
                $a = json_decode($_POST['camel_videos']);
                
                $post_data = array(
                    'camel_id'     => $this->input->get_post('camel_id'),
                    'camel_name'   => $this->input->get_post('camel_name'),
                    'father_name'  => $this->input->get_post('father_name'),
                    'mother_name'  => $this->input->get_post('mother_name'),
                    'cat_name'     => $this->input->get_post('cat_name'),
                    'sub_cat_name' => $this->input->get_post('sub_cat_name'),
                    'type_name'    => $this->input->get_post('type_name'),
                    'price'        => $this->input->get_post('price'),
                    'note'         => $this->input->get_post('note'),
                    'description'  => $this->input->get_post('description'),
                    'reference_no' => $this->input->get_post('reference_no'),
                    'location'     => $this->input->get_post('location'),
                    'country_name' => $this->input->get_post('country_name'),
                    'region'       => $this->input->get_post('region'),
                    'nearest_race_track' => $this->input->get_post('nearest_race_track'),
                    'user_id'      => $user_id->user_id
                );

                $res = $this->soghan_model->saveRecord('posts', $post_data);
                if ($res) {
                    
                  for($i=0; $i<count($a); $i++){ 
                        $videos = array(
                            'competition_name' => $a[$i]->video_competitionName,
                            'video_country'    => $a[$i]->video_country,
                            'video_city'       => $a[$i]->video_city,
                            'video_region'     => $a[$i]->video_region,
                            'position'         => $a[$i]->video_position,
                            'image'            => $a[$i]->video_thumbURL,
                            'track'            => $a[$i]->video_track,
                            'video_url'        => $a[$i]->video_videoURL,
                            'post_id'          => $res                                
                        );
                        
                        $this->soghan_model->saveRecord('camel_videos', $videos);                            
                    }                    
                    
                    for ($i = 0; $i < 10; $i++) {
                        
                        if (!empty($_FILES['picture_' . $i]['name'])) {

                            $ext = pathinfo($_FILES['picture_' . $i]['name'], PATHINFO_EXTENSION);
                            $t = time();
                            // $imageName = $_FILES['picture_' . $i]['name'];                            

                            $config['upload_path'] = 'assets/uploads';
                            $config['file_name'] = $t.'_'.$i.'.'.$ext;
                            $config['overwrite'] = false;
                            $config["allowed_types"] = 'jpg|jpeg|png';
//                                $config["max_size"] = 5024;
//                                $config["max_width"] = 1024;
//                                $config["max_height"] = 1000;
                            $this->load->library('upload', $config);
                            $this->upload->do_upload('picture_' . $i);

                            ///----------------resize start-------------------///
//                                $resize['image_library'] = 'gd2';
//                                $resize['source_image'] = 'assets/uploads/'.$imageName;
//                                $resize['create_thumb'] = FALSE;
//                                $resize['maintain_ratio'] = TRUE;
//                                $resize['width'] = 140;
//                                $resize['height'] = 180;
//                                $this->load->library('image_lib', $resize);
//                                $this->image_lib->resize();
                            ///----------------resize end-------------------///
                            
                            if($i>0){
                                $db_img = $t.'_0'.$i.'.'.$ext;
                            }
                            else{
                                $db_img = $t.'_0'.'.'.$ext;
                            }
                            $img = 'http://soghan.ae/assets/uploads/'.$db_img;
                            
                            $this->soghan_model->saveRecord('pictures', array('picture' => $img, 'post_id' => $res));
                            
                        } else {
                            break;
                        }
                    }                        

                    $result['status'] = success;
                    $result['msg']    = 'Successfully Saved';
                } else {
                    $result['status'] = error;
                    $result['msg']    = 'Some error Occurred';
                }
            } 
        }else{
            $result['status'] = error;
            $result['msg']    = 'User not Found';
        } 
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    public function get_posts(){
        
        $result['camels_list'] = $this->soghan_model->getAllPosts();
        
        if(count($result['camels_list']) > 0){
            
            $result['status'] = success;
            $result['msg']    = 'Record Found';
            
            // for($i=0; $i<1; $i++){
//                 
                // $result = $posts;
//                 
                // // $pics = $this->soghan_model->getCamelPics($posts[$i]['post_id']);
                // // $result['camels_list'][$i]['pictures'] = $pics;
//                 
            // }
                        
            foreach($result['camels_list'] as $key => $post){         
                         
                $vids = $this->soghan_model->getCamelVideos($post['post_id']); 
                $result['camels_list'][$key]['camel_videos'] = $vids;
                        
                $pics = $this->soghan_model->getCamelPics($post['post_id']); 
                $result['camels_list'][$key]['pictures'] = $pics;
                
                // echo '<pre>'; print_r($vids);
                // echo '<pre>'; print_r($pics); die;
            }            
        } else {
            $result['status'] = error;
            $result['msg']    = 'No Record Found';
        }

        header('Content-Type: application/json');
        echo json_encode($result); die;
                    
        
        // if($posts){
//             
            // $result['status'] = success;
            // $result['msg']    = 'Record Found';
//             
            // $index = array_keys($posts[0]);
//             
// //            echo '<pre>'; print_r($index); die; 
//             
            // $id = $posts[0]['post_id'];
            // $res = 0;
            // $start = 0;
            // for ($i = $start; $i < count($posts) - 1; $i++) {
                // if ($posts[$i]['post_id'] == $id) {
// 
                    // foreach ($index as $key => $val) {
                        // if ($key < 21) {
                            // $result['camels_list'][$res][$val] = $posts[$i][$val];
                        // }
                    // }
                    
                    

                    // $count = 0;                    
                    // if($posts[$i]['video_id']){
                        // for ($k = $start; $k < count($posts); $k++) {
                            // if ($posts[$k]['post_id'] == $id) {
                                // $result['camels_list'][$res]['camel_videos'][$count]['competition_name'] = $posts[$k]['competition_name'];
                                // $result['camels_list'][$res]['camel_videos'][$count]['video_city'] = $posts[$k]['video_city'];
                                // $result['camels_list'][$res]['camel_videos'][$count]['video_country'] = $posts[$k]['video_country'];
                                // $result['camels_list'][$res]['camel_videos'][$count]['position'] = $posts[$k]['position'];
                                // $result['camels_list'][$res]['camel_videos'][$count]['track'] = $posts[$k]['track'];
                                // $result['camels_list'][$res]['camel_videos'][$count]['image'] = $posts[$k]['image'];
                                // $result['camels_list'][$res]['camel_videos'][$count]['video_url'] = $posts[$k]['video_url'];
                                // $count++;
                            // }
                        // }
                        // else{
                            // $start = $k;
                            // $id = $posts[$k]['post_id'];
                            // $k = count($posts) + 2;
                        // }     
                    // }
                    
                    
                    // for ($j = $start; $j < count($posts); $j++) {
                        // if ($posts[$j]['post_id'] == $id) {
                            // $result['camels_list'][$res]['pictures'][]['pic_camel'] = $posts[$j]['picture'];
                            // $count++;
                        // } else {
                            // $start = $j;
                            // $id = $posts[$j]['post_id'];
                            // $j = count($posts) + 2;
                        // }
                    // }                    
                    // $res++;
                // }
            // }            
              
    }
    
    public function search_post(){
        
        $result['search'] = $this->soghan_model->searchPost($this->input->get_post('search'));
        header('Content-Type: application/json');
        echo json_encode($result);
    }    
    
    public function delete_post(){
                
        if(empty($this->input->get_post('camel_id'))){
            $result['status'] = error;
            $result['msg']    = 'Camel id Required !';
        }
        else{        
            $check = $this->soghan_model->checkRecord('posts', array('camel_id' => $this->input->get_post('camel_id')));
            if($check){
                // $del = $this->soghan_model->deleteRecord('posts', 'camel_id', $this->input->get_post('camel_id'));
                $post_id = $this->soghan_model->checkRecord('posts', array('camel_id' => $this->input->get_post('camel_id')));
                $del = $this->soghan_model->deletePost($post_id->post_id);
                if($del){
                    $result['status'] = success;
                    $result['msg']    = 'Successfully Deleted';
                }
                else{
                    $result['status'] = error;
                    $result['msg']    = 'Some error occurred';
                }
            }
            else{
                    $result['status'] = success;
                    $result['msg']    = 'Does not exists';
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }    
    
    public function get_links(){
        
        $eng = ($this->input->get_post('lang')=='en') ? 'english_' : '';
        
        $result['links'] = $this->soghan_model->getLinks('', '', $eng);
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    public function get_vendors(){
        
        $eng = ($this->input->get_post('lang')=='en') ? 'english_' : '';       
        $vendors = $this->soghan_model->getVendors(1, $eng);
        
        if($vendors){
            $result['status']  = 'Success';
            $result['msg']     = 'Available';

            $id = $vendors[0]['vendor_id'];
            $res = 0;
            $start = 0;
            for ($i = $start; $i < count($vendors) - 1; $i++) {
                if ($vendors[$i]['vendor_id'] == $id) {

                    $result['vendors'][$res]['vendor_id'] = $vendors[$i]['vendor_id'];
                    $result['vendors'][$res]['vendor_type'] = $vendors[$i][$eng.'vendor_type'];
                    $result['vendors'][$res]['vendor_mobile_image'] = $vendors[$i]['vendor_mobile_image'];

                    $count = 0;
                    for ($j = $start; $j < count($vendors); $j++) {
                        if ($vendors[$j]['vendor_id'] == $id) {
                            $result['vendors'][$res]['users'][$count]['vendor_detail_id'] = $vendors[$j]['vendor_detail_id'];
                            $result['vendors'][$res]['users'][$count]['contact_name'] = $vendors[$j][$eng.'contact_name'];
                            $result['vendors'][$res]['users'][$count]['company_name'] = $vendors[$j][$eng.'company_name'];
                            $result['vendors'][$res]['users'][$count]['email'] = $vendors[$j]['email'];
                            $result['vendors'][$res]['users'][$count]['mobile'] = $vendors[$j]['mobile'];
                            $result['vendors'][$res]['users'][$count]['phone'] = $vendors[$j]['phone'];
                            $result['vendors'][$res]['users'][$count]['latitude'] = $vendors[$j]['latitude'];
                            $result['vendors'][$res]['users'][$count]['longitude'] = $vendors[$j]['longitude'];
                            $result['vendors'][$res]['users'][$count]['city'] = $vendors[$j][$eng.'city'];
                            $result['vendors'][$res]['users'][$count]['area'] = $vendors[$j][$eng.'area'];
                            $result['vendors'][$res]['users'][$count]['description'] = $vendors[$j][$eng.'description'];
                            $result['vendors'][$res]['users'][$count]['image'] = $vendors[$j]['image'];
                            $count++;
                            $i = $j;
                        } else {
                            $start = $j;
                            $id = $vendors[$j]['vendor_id'];
                            $j = count($vendors) + 2;
                        }
                    }
                    $res++;
                }
            }
        }
        else{
            $result['status'] = 'Error';
            $result['msg']    = 'No Record Found';
        }
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    public function get_vendor_detail($id){
        
        $detail = $this->soghan_model->getVendorDetail($id);
        if($detail){
            $result['status']        = success;
            $result['msg']           = 'Available';
            $result['vendor_detail'] = $detail;
        }
        else{
            $result['status'] = error;
            $result['msg']    = 'No Record Found';
        }
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    public function update_views($id){
        $count = $this->soghan_model->updateViews($id);
        if($count){
            $result['status']        = success;
            $result['msg']           = 'Updated';
        }
        else{
            $result['status'] = error;
            $result['msg']    = 'could not be Updated';
        }
        header('Content-Type: application/json');
        echo json_encode($result);        
    }
    
    public function get_events(){
        
        $events = $this->soghan_model->getEvents();
        if($events){
            $result['status']  = success;
            $result['msg']     = 'Available';
            $result['events']  = $events;
        }
        else{
            $result['status'] = error;
            $result['msg']    = 'No Record Found';
        }
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    
    public function youtube_mobile(){
        
        $result = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/search?part=snippet&key=AIzaSyD6Hw3aOpudY5BfG-T1729qEiCVHJ38y8k&channelId=UCRFhf8TCBy1xWYFC64mJwiA&maxResults=20&order=date&q=2015/10/16'));
       
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    public function save_token(){
        
        if(empty($this->input->get_post('token'))){
            $result['status'] = 'Error';
            $result['msg']    = 'Token required';            
        }
        else{
            $data['deviceType']  = "ios";
            $data['deviceToken'] = $this->input->get_post('token');
            $data['channels']    = array("CHSoghanDev");
            $data_string = json_encode($data);

            $result = file_get_contents('https://api.parse.com/1/installations', null, stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'header' => 'Content-Type: application/json' . "\r\n"
                    . 'X-Parse-Application-Id: 4IKj8uLTuWueCuSnKl1TasBNmypA00EFVJY07tyl' . "\r\n"
                    . 'X-Parse-REST-API-Key: QdzndZTRudXcK1oM5JtVZ8jR9c2ev8zxHpeY9OGZ' . "\r\n",
                    'content' => $data_string,
                ),
            )));

            $check = $this->soghan_model->checkRecord('tokens', array('token' => $this->input->get_post('token')));
            if($check){
                $result['status'] = 'Error';
                $result['msg']    = 'Already Exists';
            }
            else{
                $res = $this->soghan_model->saveRecord('tokens', array('token' => $this->input->get_post('token')));
                if($res){
                    $result['status'] = 'Success';
                    $result['msg']    = 'Succefully Saved';
                }
                else{
                    $result['status'] = 'Error';
                    $result['msg']    = 'Could not be saved';
                }
            }
        }       
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function mobile_ads(){
        
        $result['ads'] = $this->soghan_model->getAllMobileAds();
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    public function ad_clicks(){
        
        $input = json_decode(file_get_contents('php://input'));
        $total = count($input->data->ad_id);     

        for($i=0; $i < $total; $i++){

            $query = "update ads set clicks = clicks + ".$input->data->clicks[$i].", loads = loads + ".$input->data->loads[$i]
                      ." where ad_id = ".$input->data->ad_id[$i];

            $res = $this->soghan_model->updateAnalytics($query);
            
            $history[$input->data->ad_id[$i]] = $res;            
        }
                
        $result['msg'] = (in_array('1', $history) == 1) ? 'Yes' : 'No';
        $result['data'] = $history;
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    
    public function user_vendor(){
                
        if(!empty($_FILES['picture_logo']['name'])){
            $ext = pathinfo($_FILES['picture_logo']['name'], PATHINFO_EXTENSION);                         
    
            $config['upload_path'] = 'assets/uploads';
            $config['file_name'] = 'vendor_logo.'.$ext;
            $config['overwrite'] = true;
            $config["allowed_types"] = 'jpg|jpeg|png';
            $this->load->library('upload', $config);
            $this->upload->do_upload('picture_logo');
            
            $path = $config['upload_path'].'/'.$config['file_name'];
        }
        $msg = 'Category : '.$this->input->get_post('category');
        if($this->input->get_post('category') == 'Other'){  
            $msg .= '<br>User Category : '.$this->input->get_post('user_category');
        }
        $msg .= '<br>Company Name : '.$this->input->get_post('companyname');
        $msg .= '<br>Contact Name : '.$this->input->get_post('contactname');
        $msg .= '<br>Email Name : '.urldecode($this->input->get_post('email'));
        $msg .= '<br>Phone : '.$this->input->get_post('phone');
        $msg .= '<br>Mobile : '.$this->input->get_post('mobile');
        $msg .= '<br>Latitude : '.$this->input->get_post('latitude');
        $msg .= '<br>Longitude : '.$this->input->get_post('longitude');
        $msg .= '<br>Description : '.urldecode($this->input->get_post('description'));            

        $this->send_email('hafizmabuzar@synergistics.ae', $this->input->get_post('contactname'), 'Soghan New Vendor Detais', $msg, $path); 
        unlink($path);
        
        $result['status'] = 'Success';
        $result['msg']    = 'Mail Sent';       
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function get_vendors_android(){
              
        $eng = '';
        $vendors = $this->soghan_model->getVendors(1, $eng);        
        if($vendors){
            $result['status']  = 'Success';
            $result['msg']     = 'Available';

            $id = $vendors[0]['vendor_id'];
            $res = 0;
            $start = 0;
            for ($i = $start; $i < count($vendors) - 1; $i++) {
                if ($vendors[$i]['vendor_id'] == $id) {

                    $result['vendors_arabic'][$res]['vendor_id'] = $vendors[$i]['vendor_id'];
                    $result['vendors_arabic'][$res]['vendor_type'] = $vendors[$i][$eng.'vendor_type'];
                    $result['vendors_arabic'][$res]['vendor_mobile_image'] = $vendors[$i]['vendor_mobile_image'];

                    $count = 0;
                    for ($j = $start; $j < count($vendors); $j++) {
                        if ($vendors[$j]['vendor_id'] == $id) {
                            $result['vendors_arabic'][$res]['users'][$count]['vendor_detail_id'] = $vendors[$j]['vendor_detail_id'];
                            $result['vendors_arabic'][$res]['users'][$count]['contact_name'] = $vendors[$j][$eng.'contact_name'];
                            $result['vendors_arabic'][$res]['users'][$count]['company_name'] = $vendors[$j][$eng.'company_name'];
                            $result['vendors_arabic'][$res]['users'][$count]['email'] = $vendors[$j]['email'];
                            $result['vendors_arabic'][$res]['users'][$count]['mobile'] = $vendors[$j]['mobile'];
                            $result['vendors_arabic'][$res]['users'][$count]['phone'] = $vendors[$j]['phone'];
                            $result['vendors_arabic'][$res]['users'][$count]['latitude'] = $vendors[$j]['latitude'];
                            $result['vendors_arabic'][$res]['users'][$count]['longitude'] = $vendors[$j]['longitude'];
                            $result['vendors_arabic'][$res]['users'][$count]['city'] = $vendors[$j][$eng.'city'];
                            $result['vendors_arabic'][$res]['users'][$count]['area'] = $vendors[$j][$eng.'area'];
                            $result['vendors_arabic'][$res]['users'][$count]['description'] = $vendors[$j][$eng.'description'];
                            $result['vendors_arabic'][$res]['users'][$count]['image'] = $vendors[$j]['image'];
                            $count++;
                            $i = $j;
                        } else {
                            $start = $j;
                            $id = $vendors[$j]['vendor_id'];
                            $j = count($vendors) + 2;
                        }
                    }
                    $res++;
                }
            }

            $eng = 'english_';
            $vendors = $this->soghan_model->getVendors(1, $eng);
            
            $id = $vendors[0]['vendor_id'];
            $res = 0;
            $start = 0;
            for ($i = $start; $i < count($vendors) - 1; $i++) {
                if ($vendors[$i]['vendor_id'] == $id) {

                    $result['vendors_english'][$res]['vendor_id'] = $vendors[$i]['vendor_id'];
                    $result['vendors_english'][$res]['vendor_type'] = $vendors[$i][$eng.'vendor_type'];
                    $result['vendors_english'][$res]['vendor_mobile_image'] = $vendors[$i]['vendor_mobile_image'];

                    $count = 0;
                    for ($j = $start; $j < count($vendors); $j++) {
                        if ($vendors[$j]['vendor_id'] == $id) {
                            $result['vendors_english'][$res]['users'][$count]['vendor_detail_id'] = $vendors[$j]['vendor_detail_id'];
                            $result['vendors_english'][$res]['users'][$count]['contact_name'] = $vendors[$j][$eng.'contact_name'];
                            $result['vendors_english'][$res]['users'][$count]['company_name'] = $vendors[$j][$eng.'company_name'];
                            $result['vendors_english'][$res]['users'][$count]['email'] = $vendors[$j]['email'];
                            $result['vendors_english'][$res]['users'][$count]['mobile'] = $vendors[$j]['mobile'];
                            $result['vendors_english'][$res]['users'][$count]['phone'] = $vendors[$j]['phone'];
                            $result['vendors_english'][$res]['users'][$count]['latitude'] = $vendors[$j]['latitude'];
                            $result['vendors_english'][$res]['users'][$count]['longitude'] = $vendors[$j]['longitude'];
                            $result['vendors_english'][$res]['users'][$count]['city'] = $vendors[$j][$eng.'city'];
                            $result['vendors_english'][$res]['users'][$count]['area'] = $vendors[$j][$eng.'area'];
                            $result['vendors_english'][$res]['users'][$count]['description'] = $vendors[$j][$eng.'description'];
                            $result['vendors_english'][$res]['users'][$count]['image'] = $vendors[$j]['image'];
                            $count++;
                            $i = $j;
                        } else {
                            $start = $j;
                            $id = $vendors[$j]['vendor_id'];
                            $j = count($vendors) + 2;
                        }
                    }
                    $res++;
                }
            }            
        }
        else{
            $result['status'] = 'Error';
            $result['msg']    = 'No Record Found';
        }
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    
  
  
}