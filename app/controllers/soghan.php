<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');
class Soghan extends CI_Controller {    

    public function __construct() {
        
        parent::__construct();

        $this->load->model('soghan_model');
        $this->load->library('session');

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        
        $GLOBALS['video_link'] = 'https://www.googleapis.com/youtube/v3/search?part=snippet&key=AIzaSyD6Hw3aOpudY5BfG-T1729qEiCVHJ38y8k&channelId=UCRFhf8TCBy1xWYFC64mJwiA';
    }

    public function index() {
        
        $result['youtube'] = json_decode(file_get_contents($GLOBALS['video_link'].'&maxResults=3&order=date'));
       
        $data['title'] = ' صوغان';
        $data['slider'] = base_url() . 'assets/images/img1.jpg';        
        
        $result['countries'] = $this->soghan_model->getMaidanCountries(); 
        $result['posts']   = $this->soghan_model->getPosts(0, 36);
        $result['vendors'] = $this->soghan_model->getVendors();
        $result['cats']    = $this->soghan_model->getAllCategories();
        $result['sub_cats'] = $this->soghan_model->getAllSubCategories();
        
        $this->load->view('includes/header', $data);
        $this->load->view('index', $result);
        $this->load->view('includes/footer-home');
    }

    public function home() {
        $this->login_check();
        
        $this->load->view('includes/header');
        $this->load->view('home');
        $this->load->view('includes/footer');
    }

    public function login_check() {

        if ($this->session->userdata('user_id') == '' && $this->session->userdata('username') == '') {
            $this->session->set_userdata('error', 'أنت لم تسجل الدخول، الرجاء تسجيل الدخول أولا!');
            redirect('login');
        }
    }

    public function check_already_loggedin() {

        if ($this->session->userdata('user_id') == TRUE && $this->session->userdata('username') == TRUE) {
            redirect('/');
        }
    }
    
    public function user_login() {        
        
        if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->check_already_loggedin();
            
            if (isset($_GET['status'])) {
                $this->load->library('encrypt');
                $ad_id = str_replace(' ', '+', $_GET['status']);
                $decode_id = $this->encrypt->decode($ad_id);
                
                $this->session->set_userdata('ad_id', $decode_id);
            }
            else{
                $this->session->unset_userdata('ad_id');
            }           
            
            $data['title'] = ' صوغان';
            $data['slider'] = base_url().'assets/images/img1.jpg';
            
            $this->load->view('includes/header', $data);
            $this->load->view('signin');
            $this->load->view('includes/footer');            
        }
        else{
            $this->form_validation->set_rules('email', 'Email', 'trim|required|email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $a = str_replace('<p>', '', validation_errors());
                $a = explode('</p>', $a);
                echo json_encode($a); exit;
            }
            else{            
                $login_data = array(
                    'email'    => $_POST['email'],
                    'password' => $_POST['password']
                );

                $check = $this->soghan_model->checkRecord('users', $login_data);
                if($check){
                    if($check->status==0){
                        echo json_encode(array('حسابك غير مفعل'));
                    }
                    else{
                        $this->session->set_userdata('user_id', $check->user_id);
                        $this->session->set_userdata('username', $check->first_name);                    
                        
                        $url = ($this->session->userdata('ad_id') == TRUE) ? base_url().'market_place_detail/'.$this->session->userdata('ad_id') : base_url();   
                        echo json_encode(array(1, $url));
                    }
                }else{
                    echo json_encode(array('اسم المستخدم أو كلمة المرور غير صحيحة'));
                }
            }
        }
    }    

    public function logout() 
    {
      $this->session->unset_userdata('user_id');
      $this->session->unset_userdata('username');
      $this->session->sess_destroy();
      redirect('/');
    }
    
    public function user_register() {
        
        if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->check_already_loggedin();
            
            $data['title'] = ' صوغان';
            $data['slider'] = base_url().'assets/images/img1.jpg';
            
            $result['countries'] = $this->soghan_model->getAllCountries();
            
            $this->load->view('includes/header', $data);
            $this->load->view('signup', $result);
            $this->load->view('includes/footer');            
        }
        else{
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
            $this->form_validation->set_rules('familyname', 'Family Name', 'trim|required');
            if($this->session->userdata('user_id')==FALSE){
                $this->form_validation->set_rules('email', 'Email', 'trim|required|email');
                $this->form_validation->set_rules('password', 'Password', 'trim|required');
                $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]');
            }
            $this->form_validation->set_rules('mobilenumber', 'Mobile', 'trim|required');
//            $this->form_validation->set_rules('country', 'Country', 'trim|required');
//            $this->form_validation->set_rules('city', 'City', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
//                $a = str_replace('<p>', '', validation_errors());
                $a[] = '2';
                $a[] = explode('</p>', validation_errors());
                
                echo json_encode($a); exit;
            }
            
//            $country = $this->soghan_model->getCountryByCity($_POST['city']);
            
            if($this->session->userdata('user_id')==TRUE){
                $user_data = array(
                    'first_name'    => $this->input->get_post('firstname'),
                    'middle_name'   => $this->input->get_post('middlename'),
                    'family_name'   => $this->input->get_post('familyname'),
                    'mobile'        => $this->input->get_post('mobilenumber')
//                    'country_name'  => $country->country_name,
//                    'city_name'     => $country->city_name
                );
                $res = $this->soghan_model->updateRecord('users', 'user_id', $this->session->userdata('user_id'), $user_data);
                if($res){
                    echo 'Successfully Updated!';
                }else{
                    echo 'No changes occurred!';
                }
            }
            else{
                $check = $this->soghan_model->checkRecord('users', array('email' => $this->input->get_post('email')));        
                if($check){
                    echo json_encode(array('2', 'البريد الالكتروني موجود بالفعل'));
                }
                else{
                    $user_data = array(
                        'first_name'   => $this->input->get_post('firstname'),
                        'middle_name'  => $this->input->get_post('middlename'),
                        'family_name'  => $this->input->get_post('familyname'),
                        'email'        => $this->input->get_post('email'),
                        'password'     => $this->input->get_post('password'),
                        'mobile'       => $this->input->get_post('mobilenumber')
//                        'country_name' => $country->country_name,
//                        'city_name'    => $country->city_name
                    );
                    
                    $res = $this->soghan_model->saveRecord('users', $user_data);            
                    if($res){
                        // $this->load->library('encrypt');
                        // $enc_email = $this->encrypt->encode($this->input->get_post('email'));
//                        $msg = 'Please click the given link to verfiy your email<br><a href="'. base_url() .'verification/?status='.$this->input->get_post('email').'" targer="blank">Click Here!</a>';

//                        $msg = 'Dear User, <br><br>
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
                                                            <p>اسم المستخدم: '.ucwords($this->input->get_post('firstname')).' '.ucwords($this->input->get_post('lastname')).'</p>
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
                        
                        echo json_encode(array('0', 'توقيع بنجاح، يرجى التحقق من بريدك الالكتروني للتحقق'));                    
                    }else{
                        echo json_encode(array('2', 'حدث بعض الخطأ'));                    
                    }            
                }
            }

        }
    }
    
    public function user_profile(){
        
        $this->login_check();
        
        $data['title'] = ' صوغان';
        $data['slider'] = base_url() . 'assets/images/img1.jpg';
        
        $result['countries'] = $this->soghan_model->getAllCountries();
        $result['user']      = $this->soghan_model->checkRecord('users', array('user_id' => $this->session->userdata('user_id')));
        $result['cities']    = $this->soghan_model->getCitiesByCountryName($result['user']->country_name);
        
        $this->session->set_userdata('email', $result['user']->email);
        
        $this->load->view('includes/header', $data);
        $this->load->view('view_profile', $result);
        $this->load->view('includes/footer'); 
    }
    
    public function change_password(){
        
        $this->login_check();
        
        $data['title'] = ' صوغان';
        $data['slider'] = base_url() . 'assets/images/img1.jpg';
        
        $user = $this->soghan_model->checkRecord('users', array('user_id' => $this->session->userdata('user_id')));
        $this->session->set_userdata('password', $user->password);
                
        $this->load->view('includes/header', $data);
        $this->load->view('change_password');
        $this->load->view('includes/footer'); 
    }        
    
    public function check_password(){
        $this->login_check(); 
        
        if($_POST['cur'] != $this->session->userdata('password')){
            echo 'كلمة السر الحالية لا تتطابق';
        }
        else{
            return 1;
        }                 
    }        
    
    public function save_password(){       
        $this->login_check();            
        
        $this->form_validation->set_rules('current_password', 'Current Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required');
        
        if ($this->form_validation->run() == FALSE)
        {
            $data['title'] = ' صوغان';
            $data['slider'] = base_url() . 'assets/images/img1.jpg';
            
            $this->load->view('includes/header', $data);
            $this->load->view('change_password');
            $this->load->view('includes/footer');
        }
        else{            
            if($this->session->userdata('password') != $_POST['current_password']){
                $this->session->set_userdata('msg', "كلمة السر الحالية لا تتطابق!");
                redirect('change_password');
            }
            $result = $this->soghan_model->updateRecord('users', 'user_id', $this->session->userdata('user_id'), array('password' => $_POST['new_password']));
            if($result != 0){
                $this->session->unset_userdata('user_id');
                $this->session->unset_userdata('username');
                $this->session->set_userdata('msg', 'تغيير كلمة المرور بنجاح، يرجى تسجيل الدخول!');
                redirect('login');
            }else{
                $this->session->set_userdata('msg', 'كلمة سر لا يمكن تغيير!');
                redirect('change_password');
            }
        }
    }    
      
    public function forgot_password(){
        
        if(!isset($_POST['forgot']) && empty($this->input->get_post('status'))){
                        
            $data['title'] = ' صوغان';
            $data['slider'] = base_url() . 'assets/images/img1.jpg';
            
            $this->load->view('includes/header', $data);
            $this->load->view('forgot_password');
            $this->load->view('includes/footer');
        }
        else{
            $check = $this->soghan_model->checkRecord('users', array('email' => $this->input->get_post('email')));
            if($check){
                if($check->status == 0){
                    if($this->input->get_post('status')==1){
                        $result['status'] = 'pending';
                        $result['msg'] = 'Not verified';
                        $result['title_en']   = 'Pending';
                        $result['title_ar']   = 'ريثما';
                        $result['msg_en'] = 'Not verified';
                        $result['msg_ar'] = 'لم يتم التحقق';
                        
                        header('Content-Type: application/json');
                        echo json_encode($result);
                    }
                    else{
                        $this->session->set_userdata('msg', 'حساب لم يتم التحقق!');
                        redirect('forgot');
                    }
                }
                else{          
                    
                    if (strpos($this->input->get_post('lang'), 'ar') !== false) {
                        $lang = '';
                    }else if(strpos($this->input->get_post('lang'), 'en') !== false){
                        $lang = 'en/';
                    }
                    
//                    $msg = 'Please click the given link to Reset Password<br><a href="'. base_url() .$lang.'reset/?status='.$this->input->get_post('email').'" targer="blank">Click Here!</a>';
                    
//                    $msg = 'Dear User,<br><br><br>Click on the button given below to reset password.<br>
//                    <a href="'. base_url() .$lang.'reset/?status='.$this->input->get_post('email').'"><img src="'.base_url().'assets/images/soghan_reset.png" width="135" height="35"></a>
//                    <br><br><br><br>Regards,<br>Soghan';                    
                    
                    $msg = $this->load->view('reset_email', '', true);
                    $msg.='
                        <table width="500" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td width="500" valign="top" class="class100p">
                                    <table width="500" border="0" cellpadding="0" cellspacing="0" class="class100p">
                                        <tr>
                                            <td valign="top">
                                                <table width="500" border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td style="padding: 0 20px 10px 10px;">
                                                            <p style="text-align: center">عزيزي المستخدم</p>
                                                            <p style="text-align: center">الرجاء الضغط على الرابط لتجديد كلمة السرالخاصة بكم</p>
                                                            <a href="'. base_url() .$lang.'reset/?status='.$this->input->get_post('email').'" style="background: #9b4e46; color: #fff; padding: 10px 20px; width: 150px; display: block; text-align: center; text-decoration: none; margin: 0 auto; border-radius: 3px;">تجديد كلمة السر</a>
                                                            <p style="text-align: center">لا تنسى ان تشارك اصدقائك بتطبيق صوغان!</p>
                                                            <p style="text-align: center">ولكم فائق الاحترام والتقدير. </p>
                                                            <p style="text-align: center">تفريق صوغان  </p>
                                                            <p style="text-align: center">تاتسجيلكم في صوغان يمثل قبولكن لأحكام وشروط الاستخدام </p>
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
                    
                    $this->send_email($this->input->get_post('email'), $check->first_name, 'صوغان: تجديد كلمة السر', $msg);                
                    
                    if($this->input->get_post('status')==1){
                        $result['status'] = 'Success';
                        $result['msg'] = 'Email Sent';
                        $result['title_en']   = 'Email Sent';
                        $result['title_ar']   = 'نجاح';
                        $result['msg_en'] = 'Please check your email';
                        $result['msg_ar'] = 'يرجى التحقق من بريدك الالكتروني';
                        
                        header('Content-Type: application/json');
                        echo json_encode($result);
                    }
                    else{
                        $this->session->set_userdata('msg', 'تم ارسال البريد الإلكتروني، يرجى التحقق من البريد الإلكتروني الخاص بك!');
                        redirect('forgot');
                    }
                }
            }else{
                if($this->input->get_post('status')==1){
                    $result['status'] = 'Error';
                    $result['msg'] = 'Email not found';
                    $result['title_en']   = 'Error';
                    $result['title_ar']   = 'خطأ';
                    $result['msg_en'] = 'Email not found';
                    $result['msg_ar'] = 'البريد الإلكتروني غير موجود';
                    
                    header('Content-Type: application/json');
                    echo json_encode($result);
                }
                else{
                    $this->session->set_userdata('msg', 'البريد الإلكتروني غير موجود!');
                    redirect('forgot');
                } 
            }
        }
    }
    
    public function reset_password(){
        $data['title'] = ' صوغان';
        $data['slider'] = base_url() . 'assets/images/img1.jpg';
        
        if(!isset($_POST['reset'])){
                        
            // $enc_email = str_replace('reset/', '', $this->uri->uri_string());
//             
            // $this->load->library('encrypt');
            // $email = $this->encrypt->decode($enc_email);
            
            $email = $_GET['status'];
            $this->session->set_userdata('email', $email);
            
            $this->load->view('includes/header', $data);
            $this->load->view('reset_password');
            $this->load->view('includes/footer');
        }
        else{
            $this->form_validation->set_rules('password', 'Password', 'trim|required');

            if ($this->form_validation->run() == FALSE) {

                $this->load->view('includes/header', $data);
                $this->load->view('reset_password');
                $this->load->view('includes/footer');
            }
            else{
                if($this->session->userdata('email') == TRUE){
                    $result = $this->soghan_model->updateRecord('users', 'email', $this->session->userdata('email'), array('password' => $_POST['password']));                
                    if ($result) {
                        $this->session->unset_userdata('email');
                        $this->session->set_userdata('msg', 'إعادة تعيين كلمة المرور بنجاح، يرجى تسجيل الدخول!');
                        redirect('login');
                    } else {
                        $this->session->set_userdata('msg', 'بعض خطأ حدث!');
                        redirect('reset');
                    }
                }
                else{
                    $this->session->set_userdata('msg', 'غير صالحة !');
                    redirect('reset');
                }
            }
        }        
    }
    
    function send_email($to, $f_name='', $subject, $msg){
        
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
              
        $mail->MsgHTML($body);
        $mail->Send();
    }
    
    public function get_posts(){
        $posts = $this->soghan_model->getAllPosts();
                                
        $index = array_keys($posts[0]);

        $id = $posts[0]['post_id'];
        $res = 0;
        $start = 0;
        for ($i = $start; $i < count($posts) - 1; $i++) {
            if ($posts[$i]['post_id'] == $id) {

                foreach ($index as $val) {
                    if ($val != 'picture') {
                        $result['camels_list'][$res][$val] = $posts[$i][$val];
                    }
                }

                $count = 1;
                for ($j = $start; $j < count($posts); $j++) {
                    if ($posts[$j]['post_id'] == $id) {
                        $result['camels_list'][$res]['pictures_'.$count] = $posts[$j]['picture'];
                        $count++;
                    } else {
                        $start = $j;
                        $id = $posts[$j]['post_id'];
                        $j = count($posts) + 2;
                    }
                }
                $res++;
            }
        }
        
        $this->load->view('includes/header');
        $this->load->view('posts', $result);
        $this->load->view('includes/footer');
    }
    
    public function get_vendors(){
        
        $result['vendors'] = $this->soghan_model->getVendors();
        $this->load->view('includes/header');
        $this->load->view('posts', $result);
        $this->load->view('includes/footer');        
    }
    
    public function get_vendor_detail($id){
        
        $result['details'] = $this->soghan_model->getVendorDetail($id);
        $this->load->view('includes/header');
        $this->load->view('posts', $result);
        $this->load->view('includes/footer');
    }
    
    public function get_cities_by_country(){
        
        $result['cities'] = $this->soghan_model->getCitiesByCountry($_POST['c'], $_POST['s']);
        if(!empty($_POST['c']) && empty($_POST['s'])){
            return $this->load->view('partials/cities', $result);
        }
        else if(!empty($_POST['c']) && !empty($_POST['s'])){
            if(count($result['cities']) == 0){
                $result['maidans'] = $this->soghan_model->getMaidans('country_id', $_POST['c']);
                return $this->load->view('partials/maidans', $result);
            }
            else{
                return $this->load->view('partials/cities', $result);
            } 
        }
        else{
            return $this->load->view('partials/default_cities', $result);
        }
    }
    
    public function search_cities_by_country(){
        
        $result['cities'] = $this->soghan_model->getSearchCitiesByCountry($_POST['c']);

        if($result['cities']){
            return $this->load->view('partials/search_cities', $result);
        }
    }
    
    public function get_cities_by_country_home(){
        
        $result['cities'] = $this->soghan_model->getCitiesByCountry($_POST['c'], $_POST['s']);
        if(!empty($_POST['c']) && empty($_POST['s'])){
            return $this->load->view('partials/cities', $result);
        }
        else if(!empty($_POST['c']) && !empty($_POST['s'])){
            if(count($result['cities']) == 0){
                $result['maidans'] = $this->soghan_model->getMaidans('country_id', $_POST['c']);
                return $this->load->view('partials/maidans', $result);
            }
            else{
                return $this->load->view('partials/cities', $result);
            } 
        }
        else{
            return $this->load->view('partials/default_cities', $result);
        }
    }
    
    public function get_maidan_by_city(){
        
        $result['maidans'] = $this->soghan_model->getMaidans('city_id', $_POST['c']);
        if(!empty($_POST['c'])){
            return $this->load->view('partials/maidans', $result);
        }
        else{
            return $this->load->view('partials/defaul_maidans', $result);
        }
    }
    
    public function get_subcat_by_cat(){
        
        $result['subcats'] = $this->soghan_model->getSubCatByCat($_POST['c']);
        if(!empty($_POST['c'])){
            return $this->load->view('partials/subcats', $result);
        }else{
            return $this->load->view('partials/default_subcats', $result);
        }
    }
    
    public function get_types_by_subcat(){
        
        $sub_cat = explode('-',$_POST['c']);
        $result['types'] = $this->soghan_model->getTypeBySubCat($sub_cat[0]);

        if(!empty($result['types'][0]['type_name'])){
            return $this->load->view('partials/search_types', $result);
        }else{
            return '0';
        }
    }
    
    public function get_genders_by_subcat(){
        
        $sub_cat = explode('-',$_POST['c']);
        $result['genders'] = $this->soghan_model->getGenderBySubCat($sub_cat[0], '');

        return $this->load->view('partials/search_gender', $result);
    }
    
    public function get_genders_by_type(){
        
        $type = explode('-',$_POST['c']);
        $result['genders'] = $this->soghan_model->getGenderBySubCat('', $type[1]);

        return $this->load->view('partials/search_gender', $result);
    }
    
    public function vendors_list(){
        
        $data['title'] = ' صوغان';
        $data['slider'] = base_url() . 'assets/images/img8.jpg';
        
        $result['vendors'] = $this->soghan_model->getVendors();
        
        $total = $this->soghan_model->getVendorsList(array('vendor_details.vendor_id' => $this->uri->segment(2)));
        $per_pg = 10;
        $offset = $this->uri->segment(3);

        $this->load->library('pagination');
        
        $config['base_url'] = base_url().'vendors/'.$this->uri->segment(2);
        $config['total_rows'] = count($total);
        $config['per_page'] = $per_pg;
        $config['uri_segment'] = 3;
        $config['full_tag_open'] = '<nav class="pagination-wrap"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>'; 
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $result['links'] = $this->pagination->create_links();
        
        
        $result['list'] = $this->soghan_model->getVendorsList(array('vendor_details.vendor_id' => $this->uri->segment(2)), $per_pg, $offset);
        $this->load->view('includes/header', $data);
        $this->load->view('vendors_list', $result);
        $this->load->view('includes/footer');
    }
    
    public function vendor_detail(){
        
        $id = end($this->uri->segments);
        
        $data['title'] = ' صوغان';
        $data['slider'] = base_url() . 'assets/images/img8.jpg';
        
        $result['detail'] = $this->soghan_model->getVendorsList(array('vendor_detail_id' => $id));
        
        $this->load->view('includes/header', $data);
        $this->load->view('vendor_detail', $result);
        $this->load->view('includes/footer');
    }
    
    public function market_places(){
                
        $data['title'] = ' صوغان';
        $data['slider'] = base_url() . 'assets/images/img8.jpg';
        
        $total = $this->soghan_model->getPosts();
        $per_pg = 10;
        $offset = (empty($this->uri->segment(2))) ? '0' : $this->uri->segment(2);
                
        $this->load->library('pagination');
        
        $config['base_url'] = base_url().'market_place';
        $config['total_rows'] = count($total);
        $config['per_page'] = $per_pg;
        $config['uri_segment'] = 2;
        $config['full_tag_open'] = '<nav class="pagination-wrap"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>'; 
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $result['links'] = $this->pagination->create_links();        
        
        $result['countries'] = $this->soghan_model->getMaidanCountries();
        $result['sub_cats'] = $this->soghan_model->getAllSubCategories();
        
        // $result['countries'] = $this->soghan_model->getAllCountries();
        $result['posts'] = $this->soghan_model->getPosts($offset, $per_pg);        
        $result['cats']  = $this->soghan_model->getAllCategories();
        $this->load->view('includes/header', $data);
        $this->load->view('ads_list', $result);
        $this->load->view('includes/footer');
    }
    
    public function ad_detail($id){
        
        $data['title'] = ' صوغان';
        $data['slider'] = base_url() . 'assets/images/img8.jpg';
        
        $result['detail'] = $this->soghan_model->getPostDetail($id); 
        
        if($this->session->userdata('user_id') ==  FALSE){
            $this->load->library('encrypt');
            $result['ad_id'] = $this->encrypt->encode($id);
        }
                
        $this->load->view('includes/header', $data);
        $this->load->view('ad_detail', $result);
        $this->load->view('includes/footer');
    }
    
    public function search_post(){
        
        
        if(!empty($_POST['type'])){
            $type = explode('-', $_POST['type']);
            $this->session->set_userdata('type', $type[1]);
        }else{
            $this->session->unset_userdata('type');
        }
        if(!empty($_POST['sub_cat'])){
            $sub_cat = explode('-', $_POST['sub_cat']);
            $this->session->set_userdata('sub_cat', $sub_cat[1]);
        }else{
            $this->session->unset_userdata('sub_cat');
        }
        if(!empty($_POST['country_h'])){
            $this->session->set_userdata('country', $_POST['country_h']);
        }else{
            $this->session->unset_userdata('country');
        }
        if(!empty($_POST['city'])){
            $this->session->set_userdata('city', $_POST['city']);
        }else{
            $this->session->unset_userdata('city');
        }
        if(!empty($_POST['gender'])){
            $this->session->set_userdata('gender', $_POST['gender']);
        }else{
            $this->session->unset_userdata('gender');
        }
                
        // if(!empty($_POST['country_h']) && !empty($_POST['sub_cat'])){
            // $this->session->set_userdata('country', $_POST['country_h']);
            // $this->session->set_userdata('sub_cat', $_POST['sub_cat']);
        // }
        // else if(!empty($_POST['cnt'])){
            // $this->session->set_userdata('country', $_POST['country_h']);
            // $this->session->unset_userdata('sub_cat');
        // }
        // else if(!empty($_POST['cat'])){
            // $this->session->set_userdata('sub_cat', $_POST['sub_cat']);
            // $this->session->unset_userdata('country');
        // }
                
        $total = $this->soghan_model->searchPost($this->session->userdata('country'), $this->session->userdata('city'), $this->session->userdata('sub_cat'), $this->session->userdata('type'), $this->session->userdata('gender'), 1);
        $per_pg = 10;
        $offset = (empty($this->uri->segment(2))) ? '0' : $this->uri->segment(2);
                        
        $this->load->library('pagination');
        
        $config['base_url'] = '#';
        $config['total_rows'] = count($total);
        $config['per_page'] = $per_pg;
        $config['uri_segment'] = 2;
        $config['full_tag_open'] = '<nav class="pagination-wrap"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>'; 
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $result['links'] = $this->pagination->create_links();        
        
        $result['posts'] = $this->soghan_model->searchPost($this->session->userdata('country'), $this->session->userdata('city'), $this->session->userdata('sub_cat'), $this->session->userdata('type'), $this->session->userdata('gender'), $per_pg, $offset);
        
        // echo '<pre>'; print_r($result['posts']); die;
        
        if($this->uri->segment(1) == 'quick_search'){
            $data['title'] = ' صوغان';
            $data['slider'] = base_url() . 'assets/images/img8.jpg';
            
            $result['cats']  = $this->soghan_model->getAllCategories();
            
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
                return $this->load->view('partials/search_ads', $result);
            }
            else{
                $this->load->view('includes/header', $data);
                $this->load->view('ads_list', $result);
                $this->load->view('includes/footer');
            }
        }
        else{
            return $this->load->view('partials/search_ads', $result);
        }
    }
    
    public function get_links(){
        
        $total = $this->soghan_model->getLinks();
        $per_pg = 10;
        $offset = (empty($this->uri->segment(2))) ? '0' : $this->uri->segment(2);
                        
        $this->load->library('pagination');
        
        $config['base_url'] = base_url().'links';
        $config['total_rows'] = count($total);
        $config['per_page'] = $per_pg;
        $config['uri_segment'] = 2;
        $config['full_tag_open'] = '<nav class="pagination-wrap"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>'; 
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $result['links'] = $this->pagination->create_links();    
        
        $result['data'] = $this->soghan_model->getLinks($per_pg, $offset);
        
        $data['title'] = ' صوغان';
        $data['slider'] = base_url() . 'assets/images/img8.jpg';

        $this->load->view('includes/header', $data);
        $this->load->view('links', $result);
        $this->load->view('includes/footer');  
    }
    
    public function get_link_detail($id){
        
        $data['title'] = ' صوغان';
        $data['slider'] = base_url() . 'assets/images/img8.jpg';
        $result['detail'] = $this->soghan_model->getLinkDetail($id);
        
        $this->load->view('includes/header', $data);
        $this->load->view('link_details', $result);
        $this->load->view('includes/footer');
    }
    
    public function calendar(){
        
        $data['title'] = ' صوغان';
        $events = $this->soghan_model->getEvents();
        $data['events'] = $this->soghan_model->getEvents(date('Y-m-d'), '1', 'arabic_');
        $data['date'] = date('Y-m-d');        
        $result['date'] = date('Y-m-d');        
        
        foreach($events as $key => $row){
            
            if($row['all_day'] == 0){
                $result['events'][$key]['title'] = $row['title'];
                $result['events'][$key]['start'] = date('Y-m-d', strtotime($row['start_time']));
                $result['events'][$key]['end']   = date('Y-m-d', strtotime($row['end_time']));
            }
            else{
                $result['events'][$key]['title'] = $row['title'];
                $result['events'][$key]['start'] = date('Y-m-d', strtotime($row['event_date']));
                $result['events'][$key]['end']   = date('Y-m-d', strtotime($row['event_date']));                
            }
        }
        
        $this->load->view('includes/header', $data);
        $this->load->view('calendar', $data);
        $this->load->view('includes/footer', $result);
    }
    
    public function get_events(){

        // header('Content-Type: text/html; charset=utf-8');
         $standard = array("0","1","2","3","4","5","6","7","8","9");
         $eastern_arabic_symbols = array("٠","١","٢","٣","٤","٥","٦","٧","٨","٩");
//         $current_date = date('d', strtotime($_POST['date'])).'-'.date('m', strtotime($_POST['date'])).'-'.date('Y', strtotime($_POST['date']));
         $eng_date = str_replace($eastern_arabic_symbols, $standard, $_POST['date']);
        
        $result['events'] = $this->soghan_model->getEvents($eng_date, $_POST['s'], 'arabic_');
        $result['date'] = $eng_date;
        return $this->load->view('partials/events', $result);
    }
    
    public function event_detail($id){
        
        $data['title'] = ' صوغان';
        $data['slider'] = base_url() . 'assets/images/img8.jpg';
        
        $result['event'] = $this->soghan_model->getEventDetail($id);   
        
        $this->load->view('includes/header', $data);
        $this->load->view('calendar_detail', $result);
        $this->load->view('includes/footer');
    }
    
    public function youtube_videos(){
        
        $this->session->unset_userdata('nextPage');
        $this->session->unset_userdata('maidan');
        $this->session->unset_userdata('date');
        
        $data['title'] = ' صوغان';
        $data['slider'] = base_url() . 'assets/images/img8.jpg';
        $result['countries'] = $this->soghan_model->getMaidanCountries();        
        $result['youtube']  = json_decode(file_get_contents($GLOBALS['video_link'].'&maxResults=20&order=date'));
        
        $this->session->set_userdata('nextPage', $result['youtube']->nextPageToken);
        $this->load->view('includes/header', $data);
        $this->load->view('videos', $result);
        $this->load->view('includes/footer');
    }
    
    public function watch_video($id){
        
        $data['title'] = ' صوغان';
        $data['slider'] = base_url() . 'assets/images/img8.jpg';
        $result['youtube']  = json_decode(file_get_contents($GLOBALS['video_link'].'&videoId='.$id.'&maxResults=1&order=date'));
        
        $this->load->view('includes/header', $data);
        $this->load->view('video_details', $result);
        $this->load->view('includes/footer');
    }
    
    public function load_videos(){
                
        if($this->session->userdata('maidan')==TRUE || $this->session->userdata('date')==TRUE){
            if($this->session->userdata('nextPage')!=''){
                $a = urlencode($this->session->userdata('maidan'));
                $result['youtube'] = json_decode(file_get_contents($GLOBALS['video_link'].'&pageToken='.$this->session->userdata('nextPage').'&maxResults=20&order=date&q='.$this->session->userdata('date').$a));
            }
        }
        else{            
            $result['youtube'] = json_decode(file_get_contents($GLOBALS['video_link'].'&pageToken='.$this->session->userdata('nextPage').'&maxResults=20&order=date'));
        }
        $this->session->set_userdata('nextPage', $result['youtube']->nextPageToken);
        return $this->load->view('partials/videos_list', $result);        
    }
    
    public function search_videos(){
        
        if(!empty($_POST['maidan']) && !empty($_POST['date'])){
            $date = date('Y', strtotime($_POST['date'])).'-'.date('m', strtotime($_POST['date'])).'-'.date('d', strtotime($_POST['date']));
            $this->session->set_userdata('maidan', $_POST['maidan']);
            $this->session->set_userdata('date', $date);
            $a = urlencode($this->session->userdata('maidan'));
        }
        else if(!empty($_POST['maidan']) && empty($_POST['date'])){
            $this->session->set_userdata('maidan', $_POST['maidan']);
            $this->session->unset_userdata('date', $_POST['date']);
            $a = urlencode($this->session->userdata('maidan'));
        }
        else if(empty($_POST['maidan']) && !empty($_POST['date'])){
            $date = date('Y', strtotime($_POST['date'])).'-'.date('m', strtotime($_POST['date'])).'-'.date('d', strtotime($_POST['date']));
            $this->session->set_userdata('date', $date);
            $this->session->unset_userdata('maidan', $_POST['maidan']);
        }   
        
        $result['youtube'] = json_decode(file_get_contents($GLOBALS['video_link'].'&maxResults=20&order=date&q='.$this->session->userdata('date').$a));

        $this->session->set_userdata('nextPage', $result['youtube']->nextPageToken);
        return $this->load->view('partials/videos_list', $result);
    }

    public function news(){
        $data['title'] = ' صوغان';
        $this->load->view("includes/header", $data);
        $this->load->view("news");
        $this->load->view("includes/footer");
    }

    function getAllSubCategories()
    {
        // $this->db->select(', arabic_country_name');
        $this->db->order_by('arabic_sub_cat_name', 'ASC');
        $query = $this->db->get('sub_categories');
        return $query->result_array();
    }
    
    function testEmail(){
        
        if(isset($_POST['submit'])){             
                        
            $msg = $this->input->get_post('body');
            $this->send_email($this->input->get_post('to'), '', 'Soghan Test Email', $msg);
            $this->session->set_userdata('msg', 'Successfully Sent');
        }

        $this->load->view('testEmail');    
    }
    
  
}