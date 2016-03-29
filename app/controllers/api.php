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
            'family_name'   => $this->input->get_post('last_name'),
            'email'         => $this->input->get_post('email'),
            'password'      => $this->input->get_post('password'),
            'mobile'        => $this->input->get_post('mobile')
        );
        
        if($this->uri->segment(1) == 'update_profile'){
            $update_res = $this->soghan_model->updateRecord('users', 'email', $this->input->get_post('email'), $user_data); 
            if($update_res > 0){
                $result['status'] = 'success';
                $result['msg']    = 'Successfully Updated';
                $result['title_en']   = 'Success';
                $result['title_ar']   = 'نجاح';
                $result['msg_en'] = 'Successfully Updated';
                $result['msg_ar'] = 'تم التحديث بنجاح';
            }
            else if($update_res == 0){
                $result['status'] = 'success';
                $result['msg']    = 'No Changed';
                $result['title_en']   = 'Success';
                $result['title_ar']   = 'نجاح';
                $result['msg_en'] = 'No Changed';
                $result['msg_ar'] = 'لا تغيير';
            }
            else{
                $result['status'] = 'error';
                $result['msg']    = 'Could not be updated';
                $result['title_en']   = 'Error';
                $result['title_ar']   = 'خطأ';
                $result['msg_en'] = 'Could not be updated';
                $result['msg_ar'] = 'لا يمكن تحديثها';       
            } 
        }
        else{
            $check = $this->soghan_model->checkRecord('users', array('email' => $this->input->get_post('email')));        
            if($check){
                $result['status'] = 'error';          
                $result['msg']    = 'Email already exists';
                $result['title_en']   = 'Error';
                $result['title_ar']   = 'خطأ';
                $result['msg_en'] = 'Email already exists';
                $result['msg_ar'] = 'البريد الالكتروني موجود بالفعل';
            }
            else{
                $res = $this->soghan_model->saveRecord('users', $user_data);            
                if($res){
    //                $msg = 'Please click the given link to verfiy your email<br><a href="'. base_url() .'verification/?status='.$this->input->get_post('email').'" targer="blank">Click Here!</a>';

//                    $msg = 'Dear User, <br><br>
//                            Confirm your email address to complete your Soghan account. Its easy &#45 just click on the button below.</p><br>
//                            <a href="'. base_url() .'verification/?status='.$this->input->get_post('email').'"><img src="'.base_url().'assets/images/soghan_confirm.png" text-align="centre" width="135" height="35"></a><br>
//                            </body>
//                            </html>';
                    
                    $msg = $this->load->view('signup_email', '', true);
                        $msg.= '<table width="500" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td width="500" valign="top" class="class100p">
                                    <table width="500" border="0" cellpadding="0" cellspacing="0" class="class100p">
                                        <tr>
                                            <td valign="top">
                                                <table width="500" border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td style="padding: 0 20px 10px 10px;">
                                                            <p>شكرآ</p>
                                                            <p>لتسجيلكم في تطبيق صوغان، فيما يلي اسم المستخدم وكلمة السر الخاصة بكم</p>
                                                            <p>اسم المستخدم: '.ucwords($this->input->get_post('first_name')).' '.ucwords($this->input->get_post('last_name')).'</p>
                                                            <a href="'. base_url() .'verification/?status='.$this->input->get_post('email').'" style="background: #9b4e46; color: #fff; padding: 10px 20px; width: 150px; display: block; text-align: center; text-decoration: none; margin: 0 auto; border-radius: 3px;">التحقق من حسابك</a>
                                                            <p style="text-align: center">تلا تنسى ان تشارك اصدقائك بتطبيق صوغان </p>
                                                            <p style="text-align: center">تاطيب الامنيات بالتوفيق  </p>
                                                            <p style="text-align: center">تفريق صوغان  </p>
                                                            <p style="text-align: center">تسجيلكم في صوغان يمثل قبولكم لأحكام وشروط الاستخدام.</p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>';

                    $this->send_email($this->input->get_post('email'), $this->input->get_post('first_name'), 'صوغان', $msg);

                    $result['status'] = 'success';        
                    $result['msg']    = 'Successfully Signed up, Please check your email for verification';
                    $result['title_en']   = 'Success';
                    $result['title_ar']   = 'نجاح';
                    $result['msg_en'] = 'Successfully Signed up, Please check your email for verification';
                    $result['msg_ar'] = 'توقيع بنجاح ، يرجى التحقق من بريدك الالكتروني للتحقق';                    
                }
                else{
                    $result['status'] = 'error';
                    $result['msg']    = 'Some error Occurred';
                    $result['title_en']   = 'Error';
                    $result['title_ar']   = 'خطأ';
                    $result['msg_en'] = 'Some error Occurred';
                    $result['msg_ar'] = 'حدث بعض الخطأ';      
                } 
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
            
            $result['status'] = 'success';
            $result['msg']    = 'Please check your email';
            $result['title_en']   = 'Email Sent';
            $result['title_ar']   = 'البريد الإلكتروني المرسلة';
            $result['msg_en'] = 'Please check your email';
            $result['msg_ar'] = 'يرجى التحقق من بريدك الالكتروني'; 
        }
        else{
            $result['status'] = 'error';
            $result['msg']    = 'Email does not exists';
            $result['title_en']   = 'Error';
            $result['title_ar']   = 'خطأ';
            $result['msg_en'] = 'Email does not exists';
            $result['msg_ar'] = 'البريد الإلكتروني لا توجد'; 
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
                $result['title_en']   = 'Pending';
                $result['title_ar']   = 'ريثما';
                $result['msg_en'] = 'Not verified';
                $result['msg_ar'] = 'لم يتم التحقق';
            }
            else if($res->status == '-1'){
                $result['status'] = 'error';
                $result['msg']    = 'Your account has been Blocked';
                $result['title_en']   = 'Error';
                $result['title_ar']   = 'خطأ';
                $result['msg_en'] = 'Your account has been Blocked';
                $result['msg_ar'] = 'تم حظر الحساب الخاص بك';
            }
            else{                
                $result['status'] = 'success';
                $result['msg']    = 'User Found';
                $result['first_name'] = $res->first_name;
                $result['last_name']  = $res->family_name;
                $result['mobile']     = $res->mobile;
                $result['msg']    = 'User Found';
                $portfolio = $this->soghan_model->getPortfolio($this->input->get_post('email'));
                
                $result['portfolio'] = (count($portfolio) > 0) ? 1 : 0;
            }
        }else{
            $result['status'] = 'error';
            $result['msg']    = 'Invalid Username OR Password';
            $result['title_en']   = 'Error';
            $result['title_ar']   = 'خطأ';
            $result['msg_en'] = 'Invalid Username OR Password';
            $result['msg_ar'] = 'اسم المستخدم أو كلمة المرور غير صالحة';
        }
        
        header('Content-Type: application/json');
        echo json_encode($result);           
    }
    
    public function logout(){
        
        $email = $this->input->get_post('email');
        if(empty($email)){
            $result['status'] = error;
            $result['msg'] = 'Email required';
        }
        else{
            $res = $this->soghan_model->updateRecord('users', 'email', $email, array('user_logout' => 0));
            
            if($res > 0){
                $result['status'] = success;
                $result['msg'] = 'Successfully logged out';
            }
            else{
                $result['status'] = error;
                $result['msg'] = 'Some error occurred';
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    function send_email($to, $f_name, $subject, $msg, $attachment=''){ 
        $this->load->library('phpmailer');
        $mail = new PHPMailer(true); 
        $mail->CharSet = "utf-8";
//        $mail->IsSMTP();

        // local
//        $mail->Host = "ssl://smtp.googlemail.com";
//        $mail->SMTPDebug = 0;
//        $mail->SMTPAuth = true;
//        $mail->Port = 465;
//        $mail->Username = "hamzasynergistics@gmail.com";
//        $mail->Password = "synergistics";
//        $mail->AddReplyTo('no-reply@email.com', 'Soghan');

        // live                            
        $mail->Host = "localhost";
//        $mail->SMTPAuth = true;
//        $mail->SMTPSecure = "ssl";
        $mail->Username = "info@soghan.ae";
        $mail->Password = "soghan_123@";
//        $mail->Port = "465";
        $mail->AddReplyTo('do-not-reply@soghan.ae', '');

        $mail->AddAddress($to, $f_name);
        $mail->SetFrom('do-not-reply@soghan.ae', 'Soghan');
        $mail->Subject = $subject;
        $body = $msg;
                
        if(!empty($attachment)){
            $mail->AddAttachment($attachment);
        } 
        $mail->MsgHTML($body);
        $mail->Send();
    }
    
    public function save_post() {
                                
        if(empty($this->input->get_post('user_email'))){
            $result['status'] = error;
            $result['msg']    = 'User Id required';
        }
        else{            
            $user_id = $this->soghan_model->checkRecord('users', array('email' => $this->input->get_post('user_email'), 'status' => 1));
            if($user_id->user_id > 0){          
                if($user_id->status == 0){
                    $result['status'] = error;
                    $result['msg']    = 'Account not activated';
                    $result['title_en']   = 'Error';
                    $result['title_ar']   = 'خطأ';
                    $result['msg_en'] = 'Account not activated';
                    $result['msg_ar'] = 'الحساب غير مفعل';
                }
                else if($user_id->status == '-1'){
                    $result['status'] = error;
                    $result['msg']    = 'Your account has been Blocked';
                    $result['title_en']   = 'Error';
                    $result['title_ar']   = 'خطأ';
                    $result['msg_en'] = 'Your account has been Blocked';
                    $result['msg_ar'] = 'تم حظر الحساب الخاص بك';
                }
                else{  
                    $status = ($this->uri->segment(1) == 'portfolio_backup') ? 0 : 1;
                    $a = json_decode($_POST['camel_videos']);
                    
                    if(!empty($this->input->get_post('marketplace_id')) && $this->input->get_post('marketplace_id') > 0){
                        $check_post = $this->soghan_model->checkRecord('posts', array('post_id' => $this->input->get_post('marketplace_id')));
                        if($check_post->post_id > 0){
                            $marketplace_id = $this->input->get_post('marketplace_id');
                            $is_listed = 1;
                        }
                        else{
                            $marketplace_id = '';
                            $is_listed = 0;
                        }
                    }
                    else{
                        $marketplace_id = $this->input->get_post('marketplace_id');
                        $is_listed = $this->input->get_post('is_listed');
                    }                    
                    
                    $post_data = array(
                        'camel_id'     => $this->input->get_post('camel_id'),
                        'camel_name'   => $this->input->get_post('camel_name'),
                        'father_name'  => $this->input->get_post('father_name'),
                        'mother_name'  => $this->input->get_post('mother_name'),
                        'cat_name'     => $this->input->get_post('cat_name'),
                        'sub_cat_name' => $this->input->get_post('sub_cat_name'),
                        'type_name'    => $this->input->get_post('type_name'),
                        'price'        => $this->input->get_post('price'),
                        'personal_note' => $this->input->get_post('personal_note'),
                        'note'         => $this->input->get_post('note'),
                        'description'  => $this->input->get_post('description'),
                        'reference_no' => $this->input->get_post('reference_no'),
                        'registration_no' => $this->input->get_post('registration_no'),
                        'location'     => $this->input->get_post('location'),
                        'country_name' => $this->input->get_post('country_name'),
                        'region'       => $this->input->get_post('region'),
                        'nearest_race_track' => $this->input->get_post('nearest_race_track'),
                        'marketplace_id' => $marketplace_id,
                        'is_listed'    => $is_listed,
                        'status'       => $status,
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

                        $last_pic_id = $this->soghan_model->getLastPictureId();                            
                        for ($i = 0; $i < 10; $i++) {

                            if (!empty($_FILES['picture_' . $i]['name'])) {                                

                                $post_id = $last_pic_id->picture_id + 1;
                                $ext = pathinfo($_FILES['picture_' . $i]['name'], PATHINFO_EXTENSION);
                                $t = mt_rand(). $post_id . '.' . $ext;
                                $path = 'assets/uploads/'.$t;
                                
//                                $move = "assets/uploads/".$_FILES['picture_' . $i]['name'];
                                move_uploaded_file($_FILES['picture_' . $i]['tmp_name'], $path);

//                                $config['upload_path'] = 'assets/uploads';
//                                $config['file_name'] = $t.'_'.$i.'.'.$ext;
//    //                            $config['file_name'] = $t.'.'.$ext;
//                                $config['overwrite'] = false;
//                                $config["allowed_types"] = 'jpg|jpeg|png';
//    //                            $config['encrypt_name']     = true;
//
//    //                            $config['encrypt_name'] = TRUE;
//    //                                $config["max_size"] = 5024;
//    //                                $config["max_width"] = 1024;
//    //                                $config["max_height"] = 1000;
//                                $this->load->library('upload', $config);
//                                $this->upload->do_upload('picture_' . $i);

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

//                                if($i>0){
//                                    $db_img = $t.'_0'.$i.'.'.$ext;
//                                }
//                                else{
//                                    $db_img = $t.'_0'.'.'.$ext;
//                                }
    //                            $db_img = $t.'.'.$ext;
//                                $img = 'http://soghan.ae/assets/uploads/'.$db_img;
                                $img = 'http://soghan.ae/assets/uploads/'.$t;

                                $this->soghan_model->saveRecord('pictures', array('picture' => $img, 'post_id' => $res));                            
                            } else {
                                break;
                            }
                        }

                        $marketplace_id = (empty($this->input->get_post('marketplace_id'))) ? $res : $this->input->get_post('marketplace_id');
                        $this->soghan_model->updateRecord('posts', 'post_id', $this->input->get_post('camel_post_id'), 
                                array('marketplace_id' => $marketplace_id, 'is_listed' => 1));

                        
                        $result['status'] = success;
                        $result['msg']    = 'Successfully Saved';
                        $result['title_en']   = 'Success';
                        $result['title_ar']   = 'نجاح';
                        $result['msg_en'] = 'Successfully Saved';
                        $result['msg_ar'] = 'تم الحفظ بنجاح';
                        if($this->uri->segment(1) == 'portfolio_backup'){
                            $cur_status = $this->soghan_model->checkRecord('posts', array('post_id' => $res));
                            $result['update'] = "$cur_status->is_listed";
                        }
                        
                        if($this->uri->segment(1) == 'portfolio_backup'){
                            $result['post_id'] = "$res";
                        }else{
                            $result['marketplace_id'] = "$res";
                        }

                    } else {
                        $result['status'] = error;
                        $result['msg']    = 'Some error Occurred';
                        $result['title_en']   = 'Error';
                        $result['title_ar']   = 'خطأ';
                        $result['msg_en'] = 'Some error Occurred';
                        $result['msg_ar'] = 'حدث بعض الخطأ';
                    }
                } 
            }else{
                $result['status'] = error;
                $result['msg']    = 'User not Found';
                $result['title_en']   = 'Error';
                $result['title_ar']   = 'خطأ';
                $result['msg_en'] = 'User not Found';
                $result['msg_ar'] = 'المستخدم ليس موجود';
            } 
        }
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    public function get_portfolio(){
        
        $user_email = $this->input->get_post('user_email');
        if(empty($user_email)){
            $result['status'] = error;
            $result['msg']    = 'User Id Required';            
        }
        else{
            $check = $this->soghan_model->checkRecord('users', array('email' => $user_email));
            if($check->status == '-1'){
                $result['status'] = error;
                $result['msg']    = 'Your Account has been Blocked';
                $result['title_en']   = 'Error';
                $result['title_ar']   = 'خطأ';
                $result['msg_en'] = 'Your account has been Blocked';
                $result['msg_ar'] = 'تم حظر الحساب الخاص بك';
            }
            else{
                $result['camels_list'] = $this->soghan_model->getPortfolio($user_email);

                if(count($result['camels_list']) > 0){

                    $result['status'] = success;
                    $result['msg']    = 'Record Found';
                    $result['title_en']   = 'Success';
                    $result['title_ar']   = 'نجاح';
                    $result['msg_en'] = 'Record Found';
                    $result['msg_ar'] = 'سجل العثور على';

                    foreach($result['camels_list'] as $key => $post){         

                        $vids = $this->soghan_model->getCamelVideos($post['post_id']); 
                        $result['camels_list'][$key]['camel_videos'] = $vids;

                        $pics = $this->soghan_model->getCamelPics($post['post_id']); 
                        $result['camels_list'][$key]['pictures'] = $pics;          
                    }

    //                $user_id  = $this->soghan_model->checkRecord('users', array('email' => $user_email)); 
                    $result['zip_url'] = base_url()."assets/portfolio_$check->user_id.zip";

                    $all_pics = $this->soghan_model->getAllPortfolioPictures($check->user_id);

                    $this->load->library('zip');
                    foreach($all_pics as $row){
                        $files_to_zip[] = str_replace(base_url(), '', $row['picture']);                    
                    }

                    $this->zip->create_zip($files_to_zip,"assets/portfolio_$check->user_id.zip");                
                } else {
                    $result['status'] = error;
                    $result['msg']    = 'No Record Found';
                    $result['title_en']   = 'Error';
                    $result['title_ar']   = 'خطأ';
                    $result['msg_en'] = 'No Record Found';
                    $result['msg_ar'] = 'لا يوجد سجلات';
                }                
            }            
        }               

        header('Content-Type: application/json');
        echo json_encode($result); die;
    }
    
    public function get_posts(){
        
        $status = ($this->uri->segment(1) == 'portfolio') ? 0 : 1;        
        $result['camels_list'] = $this->soghan_model->getAllPosts($status);
        
        if(count($result['camels_list']) > 0){
            
            $result['status'] = success;
            $result['msg']    = 'Record Found';
            $result['title_en']   = 'Success';
            $result['title_ar']   = 'نجاح';
            $result['msg_en'] = 'Record Found';
            $result['msg_ar'] = 'سجل العثور على';
            
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
            $result['title_en']   = 'Error';
            $result['title_ar']   = 'خطأ';
            $result['msg_en'] = 'No Record Found';
            $result['msg_ar'] = 'لا يوجد سجلات';
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
                
        $post_id = $this->input->get_post('post_id');
        
        if(empty($post_id)){
            $result['status'] = error;
            $result['msg']    = 'Post id Required !';
        }
        else{        
            $check = $this->soghan_model->checkRecord('posts', array('post_id' => $post_id));
            if($check){
//                $post_id = $this->soghan_model->getMarketPalce($this->input->get_post('post_id'));
                                
                $del = $this->soghan_model->deletePost($post_id);
                $this->soghan_model->updateRecord('posts', 'marketplace_id', $post_id, 
                        array('marketplace_id' => '', 'is_listed' => 0));
                if($del > 0){
                    $result['status'] = success;
                    $result['msg']    = 'Successfully Deleted';
                    $result['title_en']   = 'Success';
                    $result['title_ar']   = 'نجاح';
                    $result['msg_en'] = 'Successfully Deleted';
                    $result['msg_ar'] = 'المحذوفة بنجاح';
                }
                else{
                    $result['status'] = error;
                    $result['msg']    = 'Some error occurred';
                    $result['title_en']   = 'Error';
                    $result['title_ar']   = 'خطأ';
                    $result['msg_en'] = 'Some error occurred';
                    $result['msg_ar'] = 'وقعت بعض الأخطاء';
                }
            }
            else{
                $result['status'] = success;
                $result['msg']    = 'Does not exists';
                $result['title_en']   = 'Success';
                $result['title_ar']   = 'نجاح';
                $result['msg_en'] = 'Does not exists';
                $result['msg_ar'] = 'لا توجد';
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($result);
    } 
    
    public function delete_portfolio(){
                
        $user_email = $this->input->get_post('user_email');
        if(empty($user_email)){
            $result['status'] = error;
            $result['msg']    = 'User id Required !';
        }
        else{        
            $check = $this->soghan_model->checkRecord('users', array('email' => $user_email));
            if($check){               
                $del = $this->soghan_model->deletePortfolio($check->user_id);
                if($del > 0){
                    $result['status'] = success;
                    $result['msg']    = 'Successfully Deleted';
                    $result['title_en']   = 'Success';
                    $result['title_ar']   = 'نجاح';
                    $result['msg_en'] = 'Successfully Deleted';
                    $result['msg_ar'] = 'المحذوفة بنجاح';
                }
                else{
                    $post_check = $this->soghan_model->getPortfolio($user_email);
                    if($post_check){
                        $result['status'] = error;
                        $result['msg']    = 'Could not be Deleted';
                        $result['title_en']   = 'Error';
                        $result['title_ar']   = 'خطأ';
                        $result['msg_en'] = 'Could not be Deleted';
                        $result['msg_ar'] = 'لا يمكن المحذوفة';
                    }
                    else{
                        $result['status'] = success;
                        $result['msg']    = 'No Post Found';
                        $result['title_en']   = 'Success';
                        $result['title_ar']   = 'نجاح';
                        $result['msg_en'] = 'No Post Found';
                        $result['msg_ar'] = 'لا المشاركة وجدت';
                    }
                }
            }
            else{
                $result['status'] = error;
                $result['msg']    = 'Email not found';
                $result['title_en']   = 'Error';
                $result['title_ar']   = 'خطأ';
                $result['msg_en'] = 'Email not found';
                $result['msg_ar'] = 'البريد الإلكتروني غير موجود';
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
        
        if(!empty($this->input->get_post('lang'))){
            $eng = ($this->input->get_post('lang')=='en') ? 'english_' : '';       
            $vendors = $this->soghan_model->getVendors(1, $eng);
            
            if($vendors){
                $result['status']  = 'Success';
                $result['msg']     = 'Available';
                $result['title_en']   = 'Success';
                $result['title_ar']   = 'نجاح';
                $result['msg_en'] = 'Available';
                $result['msg_ar'] = 'متاح';
    
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
                $result['title_en']   = 'Error';
                $result['title_ar']   = 'خطأ';
                $result['msg_en'] = 'No Record Found';
                $result['msg_ar'] = 'لا يوجد سجلات';
            }            
        }
        else{
            $result['status'] = 'Error';
            $result['msg']    = 'Invalid Request';
        }
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    public function get_vendor_detail($id){
        
        $detail = $this->soghan_model->getVendorDetail($id);
        if($detail){
            $result['status']     = success;
            $result['msg']        = 'Available';
            $result['title_en']   = 'Success';
            $result['title_ar']   = 'نجاح';
            $result['msg_en']     = 'Available';
            $result['msg_ar']     = 'متاح';
            $result['vendor_detail'] = $detail;
        }
        else{
            $result['status'] = error;
            $result['msg']    = 'No Record Found';
            $result['title_en']   = 'Error';
            $result['title_ar']   = 'خطأ';
            $result['msg_en'] = 'No Record Found';
            $result['msg_ar'] = 'لا يوجد سجلات';
        }
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    public function update_views($id){
        $count = $this->soghan_model->updateViews($id);
        if($count){
            $result['status']     = success;
            $result['msg']        = 'Updated';
            $result['title_en']   = 'Success';
            $result['title_ar']   = 'نجاح';
            $result['msg_en'] = 'Successfully Updated';
            $result['msg_ar'] = 'تم التحديث بنجاح';
        }
        else{
            $result['status'] = error;
            $result['msg']    = 'could not be Updated';
            $result['title_en']   = 'Error';
            $result['title_ar']   = 'خطأ';
            $result['msg_en'] = 'Could not be Updated';
            $result['msg_ar'] = 'لا يمكن تحديث';
        }
        header('Content-Type: application/json');
        echo json_encode($result);        
    }
    
    public function get_events(){

        $events = $this->soghan_model->getEvents();
        $ar_events = $this->soghan_model->getEvents('', '', 'arabic_');

        if($events){
            $result['status']  = success;
            $result['msg']     = 'Available';
            $result['title_en']   = 'Success';
            $result['title_ar']   = 'نجاح';
            $result['msg_en'] = 'Available';
            $result['msg_ar'] = 'متاح';

            foreach($events as $key => $event){
                $result['events'][$key]['english'] = $events[$key];
                $result['events'][$key]['arabic'] = $ar_events[$key];
            }
        }
        else{
            $result['status'] = error;
            $result['msg']    = 'No Record Found';
            $result['title_en']   = 'Error';
            $result['title_ar']   = 'خطأ';
            $result['msg_en'] = 'No Record Found';
            $result['msg_ar'] = 'لا يوجد سجلات';
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
                $result['title_en']   = 'Error';
                $result['title_ar']   = 'خطأ';
                $result['msg_en'] = 'Already Exists';
                $result['msg_ar'] = 'موجود بالفعل';
            }
            else{
                $res = $this->soghan_model->saveRecord('tokens', array('token' => $this->input->get_post('token')));
                if($res){
                    $result['status'] = 'Success';
                    $result['msg']    = 'Successfully Saved';
                    $result['title_en']   = 'Success';
                    $result['title_ar']   = 'نجاح';
                    $result['msg_en'] = 'Successfully Saved';
                    $result['msg_ar'] = 'تم الحفظ بنجاح';
                }
                else{
                    $result['status'] = 'Error';
                    $result['msg']    = 'Could not be saved';
                    $result['title_en']   = 'Error';
                    $result['title_ar']   = 'خطأ';
                    $result['msg_en'] = 'Could not be saved';
                    $result['msg_ar'] = 'لا يمكن حفظ';
                }
            }
        }       
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function save_android_token(){

        if(empty($this->input->get_post('token')) && empty($this->input->get_post('device_id'))){
            $result['status'] = 'Error';
            $result['msg']    = 'Token & Device Id Required';
        }
        else if(empty($this->input->get_post('token'))){
            $result['status'] = 'Error';
            $result['msg']    = 'Token Required';
        }
        else if(empty($this->input->get_post('device_id'))){
            $result['status'] = 'Error';
            $result['msg']    = 'Device Id Required';
        }
        else{
            $token_data = array(
                'token' => $this->input->get_post('token'),
                'device_id' => $this->input->get_post('device_id')
            );

            $check = $this->soghan_model->checkRecord('tokens', array('device_id' => $this->input->get_post('device_id')));
            if($check){
                $this->soghan_model->updateRecord('tokens', 'device_id', $this->input->get_post('device_id'), array('token' => $this->input->get_post('token')));
                $result['status'] = 'Success';
                $result['msg']    = 'Successfully Updated';
            }
            else{
                $res = $this->soghan_model->saveRecord('tokens', $token_data);
                if($res){
                    $result['status'] = 'Success';
                    $result['msg']    = 'Successfully Saved';
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

    public function android_push($title, $body){

        $tokens = $this->soghan_model->getTokens();

        foreach($tokens as $tk){
            $ids[] = $tk['token'];
        }

        define( 'API_ACCESS_KEY', 'AIzaSyCFtmvA1HlvyavCFR5fQHaCwOGUDddjHl8');
        $registrationIds = $ids;

        $msg['notification'] = array
        (
            'title'     => $title,
            'message'   => $body
        );

        $fields = array
        (
            'registration_ids'  => $registrationIds,
            'data'              => $msg
        );

        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        return $result;
    }

    public function test_android_push(){

        $ids[] = $_POST['token'];

        define( 'API_ACCESS_KEY', 'AIzaSyBrYfbzbREBGug55vh4m-H5OpBf2HSGFoA');
        $registrationIds = $ids;

        $msg['notification'] = array
        (
            'title'     => $_POST['title'],
            'message'   => $_POST['body']
        );

        $fields = array
        (
            'registration_ids'  => $registrationIds,
            'data'              => $msg
        );

        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        echo $result;
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

        $this->send_email('vendor@soghan.ae', $this->input->get_post('contactname'), 'Soghan New Vendor Detais', $msg, $path); 
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