<?php
/**
 * Created by PhpStorm.
 * User: adwisatya
 * Date: 9/15/2015
 * Time: 1:57 AM
 */

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
    public function send_success($data){
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
        $this->output->set_status_header(200);
        $this->output->set_content_type('application/json');
        echo json_encode($data);
    }

    public function send_error($desc,$tipe){
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
        $this->output->set_status_header($tipe);
        $this->output->set_content_type('application/json');
        echo json_encode($desc);
    }
    public function check_parameter($params){
        $checked = true;
        $error['error'] = "Request tidak sesuai format";
        if(is_array($params)){
            foreach($params as $parameter){
                if (is_null($parameter)) {
                    echo $parameter;
                    $checked = false;
                }
            }
        }else{
            if (!$this->input->get($params) && $this->input->get($params) !== '0') {
                $checked = false;
            }
        }
        if($checked == true){
            return true;
        }else{
            $this->send_error($error,400);
            exit();
        }
    }
}