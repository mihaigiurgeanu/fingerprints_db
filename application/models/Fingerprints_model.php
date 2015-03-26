<?php
class Fingerprints_model extends CI_model {
    public function __construct()
    {
        $this->load->database();
    }
    
    public function create_fingerprint() {
        $this->load->helper('uuid');
        $id = uuid_v5('dea36198-055e-4fe2-9e40-a86d93aba1a4', uniqid('fingerprints', TRUE));
        
        $file = fopen($this->config->item('fp_images_folder').$id, "w");
        if(! $file) {
            return '';
        }
        
        fwrite($file, $this->input->raw_input_stream);
        fclose($file);
        
        $data = array(
            "id" => $id,
            "file_name" => $id
        );
        $this->db->insert('fp_fingerprints', $data);
        
        return $id;
    }
    
    public function get_fingerprint($id) {
        log_message('debug', "get_fingerprint: {$id}");
        $query = $this->db->get_where('fp_fingerprints', array('id' => $id));
        return $query->row_array();
    }
}
?>