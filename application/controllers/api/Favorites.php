<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Favorites extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('UserModel');
    }

    public function index_get()
    {
        $token = null;
        $user_id = null;

        //Get headers
        $headers = apache_request_headers();

        //check if Authorization header exists
        if(isset($headers['Authorization'])){
            $unstrippedToken = $headers['Authorization'];
            $authToken = str_replace("Bearer ", '', $unstrippedToken);

            //Authorize
            try {
              $this->load->library('jwt');
              $decodedToken =  $this->jwt->decode($authToken, SECRET_KEY);      // SECRET_KEY is defined on constants.php
              $encoded = json_encode($decodedToken);
              $dec = json_decode($encoded, true);
              $user_id = $dec["userId"];
              $username = $dec["data"]["username"];
              $authorized = true;
            }catch(Exception $e){
              $this->response(array(
                                    'error' => ['title' => 'unauthorized', 'detail' => 'invalid token'],
                                    'id' => null,
                                    'meta' => ['token' => 'null']
                                  ), REST_Controller::HTTP_UNAUTHORIZED);
              $authorized = false;
            }

            if($authorized == true){
              $data['favorites'] = $this->UserModel->get_userFavorites($user_id);

              $favoriteFoods = array(
                'id' => $user_id,
                'username' => $username,
                'data' => [
                  'favoriteFoods' => $data['favorites'][0]['fav_food']
                  ]
              );

              $this->response($favoriteFoods, 200);


            }
        }else {
          $this->response(array(
                                'error' => ['title' => 'unauthorized', 'detail' => 'missing token'],
                                'id' => null,
                                'meta' => ['token' => 'null']
                              ), REST_Controller::HTTP_UNAUTHORIZED);
        }

    }



}
