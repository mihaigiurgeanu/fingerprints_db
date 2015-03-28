<?php
class Tokens_model extends CI_model {
    public function __construct()
    {
        $this->load->database();
        $this->load->model("persons_model");
    }
    
    public function create_token() {
        $this->load->helper('uuid');
        $tokenid = uuid_v5('dea36198-055e-4fe2-9e40-a86d93aba1a4', uniqid('tokens', TRUE));
        
        $token_type = $this->input->post('type');        
        $data = array(
            "id" => $tokenid,
            "token_type" => $token_type
        );
        $this->db->trans_start();

        switch($token_type) {
            case "verify":
            // person fingerprint verification
            // 
            $person_id = $this->input->post('person_id');
            $person = $this->db->persons_model->find_by_id($person_id);
            if(!$person) {
                log_message('error', "Requested person id not found: $person_id");
                return FALSE;
            }
            $data['fingerprint_id'] = $person['fingerprints_id'];
            break;
        }

        
        if(!$this->db->insert('fp_tokens', $data)){
            log_message('error', "Token <$tokenid, $token_type> could not be inserted into database.");
            return FALSE;
        }
        
        log_message('info', "New token ($token_type) created: $tokenid.");            
        $this->db->trans_complete();
        return $tokenid;
    }
    
    public function put_scan($tokenid, $fingerprints_id) {
        $data['fingerprint_id'] = $fingerprints_id;

        $this->db->where('id',  $tokenid);
        $this->db->update('fp_tokens', $data);
    }

    public function mark_consumed($tokenid) {
        log_message('debug', "Marking token $tokenid as consumed");
        
        $data['token_consumed'] = TRUE;
        $this->db->where('id', $tokenid);
        return $this->db->update('fp_tokens', $data);
    }
    
    public function get_token($tokenid) {
        $query = $this->db->get_where('fp_tokens', array('id' => $tokenid));
        return $query->row_array();
    }
}
?>