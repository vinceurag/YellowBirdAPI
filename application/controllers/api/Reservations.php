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
class Reservations extends REST_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();
        $this->load->model('UserModel');
    }

    public function index_get() {
        $this->load->library('jwt');
        $authorizationResult = json_decode($this->jwt->check(), true);
        $authorization = $authorizationResult['authorization'];

        if($authorization == "authorized") {

          //Get ID parameter
          $id = $this->get('id');

          $data['reservations'] = $this->UserModel->get_reservations();
          $response = $data['reservations'];

          //Check if there's an ID parameter
          if ($id === NULL){
            //Return all if there's no ID parameter
            $this->response($response, REST_Controller::HTTP_OK);
          }else {
            //Get Record by ID
            foreach ($response as $key => $value) {
                if (isset($value['id']) && $value['id'] === $id) {
                    $reservation = $value;
                }
            }
            if (!empty($reservation)) {
                $this->set_response($reservation, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }else {
              $this->set_response(array(
                  'status' => FALSE,
                  'message' => 'User could not be found'
              ), REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
          }

        }else{
          $this->response(array(
                                'error' => array('title' => $authorization, 'detail' => 'invalid token'),
                                'id' => null,
                                'meta' => array('token' => 'null')
                              ), REST_Controller::HTTP_UNAUTHORIZED);
        }
    }


    public function index_post() {

          try {
            $data = $this->input->raw_input_stream;
            $data = json_decode($data, true);
            if(isset($data['date_f']) && isset($data['time_f']) && isset($data['name_mobile']) && isset($data['numOfSeats']) && isset($data['tableType']) ){
              $date_f = $data['date_f'];
              $time_f = $data['time_f'];
              $name_mobile = $data['name_mobile'];
              $numOfSeats = $data['numOfSeats'];
              $tableType = $data['tableType'];
            }else{
              throw new Exception('getHeaderDataError');
            }
          }catch(Exception $e){
            $this->response(array(
                                  'error' => array('title' => 'Header Error', 'detail' => 'no data passed')
                                ), REST_Controller::HTTP_BAD_REQUEST);
          }

          $reservationInfo = array(
            'date_f' => $date_f,
            'time_f' => $time_f,
            'name_mobile' => $name_mobile,
            'numOfSeats' => $numOfSeats,
            'tableType' => $tableType
          );
          $result = $this->UserModel->post_reservations($reservationInfo);
          if($result == TRUE){
            $this->response($reservationInfo, 201);
          }else {
            $this->response(array(
                                  'error' => array('title' => 'Database Error', 'detail' => 'not inserted')
                                ), REST_Controller::HTTP_BAD_REQUEST);
          }

    }



}
