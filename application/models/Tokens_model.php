<?php
class Tokens_model extends CI_model {
    public function __construct()
    {
        $this->load->database();
    }
    
    public function create_token() {
        $this->load->helper('uuid');
        $tokenid = uuid_v5('dea36198-055e-4fe2-9e40-a86d93aba1a4', uniqid('tokens', TRUE));
        
        $data = array(
            "id" => $tokenid,
            "token_type" => $this->input->post('type')
        );
        $this->db->insert('fp_tokens', $data);
        
        return $tokenid;
    }
    
    public function get_token($tokenid) {
        $query = $this->db->get_where('fp_tokens', array('id' => $tokenid));
        return $query->row_array();
    }
}
?>