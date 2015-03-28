<?php
class Persons_model extends CI_Model {
    public function __construct()
    {
        $this->load->database();
        $this->load->model('tokens_model');
    }
    
    public function load_persons($search='', $page =1) {
        $num_rows = 50;
        $this->db->limit($num_rows, $num_rows*($page - 1));
        $this->db->like("first_name", $search);
        $this->db->or_like("last_name", $search);
        $query = $this->db->get("fp_persons");
        return $query->result_array();
    }
    
    public function add_person() {
        $this->load->helper('uuid');
        $id = uuid_v5('dea36198-055e-4fe2-9e40-a86d93aba1a4', uniqid('persons', TRUE));
        log_message('debug', "Saving new person with id $id");
        
        $data = array(
            'id'            => $id,
            'first_name'    => $this->input->post('first_name'),
            'last_name'     => $this->input->post('last_name'),
            'description'   => $this->input->post('description')
        );

        $scan_tokenid = $this->input->post('scan_tokenid');
        if($scan_tokenid) {
            log_message('debug', "Got scan_tokenid: $scan_tokenid");
            $token = $this->tokens_model->get_token($scan_tokenid);
            if($token) {
                if($token['token_type'] == 'scan') {
                    $data['fingerprints_id'] = $token['fingerprint_id'];
                } else {
                    log_message('error', "Token type ".$token['token_type']." not supported for creating person record. Excected type: 'scan'");
                }
            } else {
                log_message('error', "Token not found for $tokenid in Persons_model::add_person()");
            }
        } else {
            log_message('debug', "No scan_tokenid parameter when saving person $id");
        }

        $this->db->trans_start();
        $success = $this->db->insert('fp_persons', $data); 
        if($scan_tokenid) 
            $success = $success && $this->tokens_model->mark_consumed($scan_tokenid);
        $this->db->trans_complete();
        
        if($success) {
            return $id;
        }
        
        return FALSE;
    }
    
    public function find_by_id($id) {
        $query = $this->db->get_where('fp_persons', array("id" => $id));
        return $query->result_array();
    }
    
    
}
?>