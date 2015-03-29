<?php
class Fingerprints_model extends CI_model {
    public function __construct()
    {
        $this->load->database();
        $this->load->model('tokens_model');
    }
    
    private function save_image_file($id) {
        $filename = $this->config->item('fp_images_folder').$id;
        log_message('info', "Saving new image scan to $filename.");
        $file = fopen($filename, "w");
        if(! $file) {
            log_message('error', "File $filename could not be open for writing.");
            return FALSE;
        }
        
        fwrite($file, $this->input->raw_input_stream);
        fclose($file);
        log_message('info', "fingerprint image file saved: $filename");
        
        return $filename;
    }
    
    public function create_fingerprint() {
        $tokenid = $this->input->get('tokenid');
        
        $this->load->helper('uuid');
        $id = uuid_v5('dea36198-055e-4fe2-9e40-a86d93aba1a4', uniqid('fingerprints', TRUE));
        $data['id'] = $id;
        $this->db->trans_start();
        
        switch($this->input->get('kind')) {
            case 'scan':
            log_message('info', "creating fingerprint $id from image data");
            $filename = $this->save_image_file($id);
            if($filename) {
                $data['file_name'] = $id;
                if($this->db->insert('fp_fingerprints', $data)) {
                    $this->tokens_model->put_scan($tokenid, $id);
                    $this->db->trans_complete();
                    return $id;
                }
            }
            break;
            
            case 'template':
            $data['template'] = $this->input->raw_input_stream;
            log_message('debug', "creating fingerprint $id from template data");
            if($this->db->insert('fp_fingerprints', $data)) {
                $this->tokens_model->put_scan($tokenid, $id);
                $this->db->trans_complete();
                return $id;
            }
            break;
        }       
        
        return FALSE;
    }
    
    public function update_fingerprint() {
        $tokenid = $this->input->get('tokenid');
        $fingerprint = $this->get_fingerprint_by_tokenid($tokenid);
        
        if($fingerprint === FALSE) {
            log_message('error', "Could not get the fingerprint for the token $tokenid");
        }
        $id = $fingerprint['id'];
        $this->db->where('id', $id);
        switch($this->input->get('kind')) {
            case 'scan':
            log_message('info', "update fingerprint $id with image data");
            $filename = $this->save_image_file($id);
            if($filename) {
                $data['file_name'] = $id;
                if($this->db->update('fp_fingerprints', $data)) {
                    return $id;
                }
            }
            break;
            
            case 'template':
            $data['template'] = $this->input->raw_input_stream;
            log_message('debug', "update fingerprint $id with template data");
            if($this->db->update('fp_fingerprints', $data)) {
                return $id;
            }
            break;
        }
    }
    
    public function get_fingerprint($id) {
        log_message('debug', "get_fingerprint: {$id}");
        $query = $this->db->get_where('fp_fingerprints', array('id' => $id));
        return $query->row_array();
    }
    
    public function get_fingerprint_by_tokenid($tokenid) {
        log_message('debug', "get_fingerprint_by_tokenid($tokenid)");
        $this->db->select('fp_fingerprints.id, fp_fingerprints.file_name, fp_fingerprints.template');
        $this->db->from('fp_fingerprints');
        $this->db->join('fp_tokens', 'fp_tokens.fingerprint_id = fp_fingerprints.id');
        $this->db->where('fp_tokens.id', $tokenid);
        $query = $this->db->get();
        return $query->row_array();
    }
}
?>