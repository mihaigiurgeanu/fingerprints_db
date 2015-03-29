<?php
class Tokens extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tokens_model');
        $this->load->helper('authorization');
    }

    public function create_token() {
        if(!is_user_authorized('tokens.create')) {
            $this->output->set_status_header('403');
        } else {
            
            
            // User is authorized to create tokens
            $valid = true;
            $token_type = $this->input->post('type');        
            switch ($token_type) {
                case 'verify':
                $person_id = $this->input->post('person_id');
                log_message('debug', "Checking valid person_id in request: '$person_id'");
                if(! $person_id) {
                    $this->output->set_status_header('400', 'Expected person_id');
                    $valid = false;
                }
            }
            
            if($valid) {
                $tokenid = $this->tokens_model->create_token();
                if($tokenid) {
                    $this->output
                        ->set_status_header('201')
                        ->set_content_type('application/json')
                        ->set_header('Location: '.$this->config->item('fingerprints_server_url')."api/tokens/$tokenid")
                        ->set_output(json_encode(array('id' => $tokenid)));
                } else {
                    $this->output->set_status_header('500', 'Token could not be created');
                }                
            }
            
        }
    }
        
    public function get_token($id = '') {
        log_message('info', "Atempt to read token id $id");
        $this->output->set_status_header('403');
    }
    
}
?>