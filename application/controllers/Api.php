<?php
class Api extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tokens_model');
        $this->load->model('fingerprints_model');

        $this->load->helper('authorization');
    }

    public function tokens() {
        if(!is_user_authorized('tokens.create')) {
            $this->output->set_status_header('401');
        } else {
            
            
            // User is authorized to create tokens
            
            $tokenid = $this->tokens_model->create_token();
            $this->output
                ->set_status_header('201')
                ->set_content_type('application/json')
                ->set_output(json_encode(array('id' => $tokenid)));
        }
    }
    
    public function fingerprintsscans($id = '') {
        if($id === '') {
            if(is_user_authorized('scans.create')) {
                $tokenid = $this->input->get('tokenid');
                if(empty($tokenid) {
                    $this->output->set_status_header('401');
                } else {
                    $token = $this->tokens_model->get_token($tokenid);
                    if(empty($token) || $token["token_consumed"]) {
                        $this->output->set_status_header('401');
                    } else {
                        $fingerprints_id = $this->fingerprints_model->create_fingerprint();
                        if($fingerprints_id) {
                            $this->output
                                ->set_status_header('201')
                                ->set_content_type('application/json')
                                ->set_header('Location: '.$this->config->item('fingerprints_server_url'.'/api/fingerprints/$fingerprints_id'))
                                ->set_output(json_encode(array('id' => $fingerprints_id)));
                        } else {
                            $this->output->set_status_header('500', 'Fingerprint not saved.');
                        }
                    }
                }
            } else {
                $this->output->set_status_header('401');
            }
        } else {
            log_message('debug', "Getting image file for /api/fingerprintsscans/$id");
            if(is_user_authorized('scans.read')) {
                $fingerprint = $this->fingerprints_model->get_fingerprint($id);
                
                if($fingerprint) {
                    $filename = $this->config->item('fp_images_folder').$fingerprint["file_name"];
                    if(file_exists($filename)) {
                        
                        header("Content-type: image/bmp");
                        header("Expires: Mon, 1 Jan 2099 05:00:00 GMT");
                        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                        header("Cache-Control: no-store, no-cache, must-revalidate");
                        header("Cache-Control: post-check=0, pre-check=0", false);
                        header("Pragma: no-cache");

                        $size= filesize($filename);
                        header("Content-Length: $size bytes");

                        log_message('debug', "Sending $filename with size $size");
                        readfile($filename);
                    } else {
                        log_message('info', "File $filename not found (serving scan for id {$id})");
                        $this->output->set_status_header('400', 'Image file not found');

                    }
                } else {
                    $this->output->set_status_header('400', 'Scan data not found');
                }
            }
        }
    }
    
    public function photos() {
        
    }
}
?>