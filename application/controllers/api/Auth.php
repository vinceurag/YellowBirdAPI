<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

// JWT.php goes in application/libraries
//example controller function
// test case: Annotator Authentication (https://github.com/okfn/annotator)
class Auth extends REST_Controller {

  function __construct(){
    parent::__construct();
    $this->load->model('UserModel');
  }

  public function index_post() {

    // header('Content-Type: application/json');

      //Read post data
      $data = $this->input->raw_input_stream;
      $data = json_decode($data, true);

      $username = $data['username'];
      $password = $data['password'];

      $result = $this->UserModel->get_user($username, $password);
      if($result == 1){

        $user_profile = $this->UserModel->get_userProfile($username, $password);

        $user_id = $user_profile[0]['id'];
        $user_name = $user_profile[0]['full_name'];

        $token = $this->generate_token($user_id, $username);
        $json_response = array(
          'id' => $user_id,
          'data' => ['name' => $user_name, 'username' => $username],
          'meta' => ['token' => $token]
        );
        $this->response($json_response, 200);
      }else {
        $this->response(array(
                                        'error' => ['title' => 'unauthorized', 'detail' => 'invalid credentials'],
                                        'id' => null,
                                        'meta' => ['token' => 'null']
                                      ), REST_Controller::HTTP_UNAUTHORIZED);
      }
  }

  public function generate_token($user_id, $username){
    date_default_timezone_set('Asia/Manila');
    $currDate = date('m/d/Y H:i:s A');


      $this->load->library('jwt');
      $CONSUMER_KEY = 'test';
      $CONSUMER_SECRET = SECRET_KEY;
      return $this->jwt->encode(array(
        'consumerKey'=>$CONSUMER_KEY,
        'userId'=>$user_id,
        'issuedAt'=>date($currDate),
        'data' =>[
          'username' => $username
        ]
      ), $CONSUMER_SECRET);
  }

  public function decode_token($token){
    try {
      $this->load->library('jwt');
      $decodedToken =  $this->jwt->decode($token, SECRET_KEY);
      $encoded = json_encode($decodedToken);
      $dec = json_decode($encoded, true);
      echo $dec['data']['username'];
    }catch(Exception $e){
      echo "Wrong key"."<br/>"."Reason: ";
      echo $e->getMessage();
    }

  }
}

?>
