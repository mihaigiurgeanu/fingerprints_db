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
            
            $tokenid = $this->tokens_model->create_token();
            $this->output
                ->set_status_header('201')
                ->set_content_type('application/json')
                ->set_header('Location: '.$this->config->item('fingerprints_server_url')."api/tokens/$tokenid")
                ->set_output(json_encode(array('id' => $tokenid)));
        }
    }
        
    public function get_token($id = '') {
        log_message('info', "Atempt to read token id $id");
        $this->output->set_status_header('403');
    }
    
}
?>