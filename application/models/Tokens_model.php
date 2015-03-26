<?php
class Tokens_model extends CI_model {
    public function __construct()
    {
        $this->load->database();
    }
    
    public function create_token() {
        $this->load->helper('uuid');
        $tokenid = uuid_v5('dea36198-055e-4fe2-9e40-a86d93aba1a4', uniqid('tokens', TRUE));
        
        $token_type = $this->input->post('type');
        
        $data = array(
            "id" => $tokenid,
            "token_type" => $token_type
        );
        if($this->db->insert('fp_tokens', $data)){
            log_message('info', "New token created: $tokenid.");
            return $tokenid;
        }
        log_message('error', "Token <$tokenid, $token_type> could not be inserted into database.");
        return FALSE;
    }
    
    public function get_token($tokenid) {
        $query = $this->db->get_where('fp_tokens', array('id' => $tokenid));
        return $query->row_array();
    }
}
?>