<?php
class Fingerprintsscans extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tokens_model');
        $this->load->model('fingerprints_model');

        $this->load->helper('authorization');
    }

    public function add_new_scan() {
        log_message('debug', "add_new_scan()");
        if(is_user_authorized('scans.create')) {
            $tokenid = $this->input->get('tokenid');
            log_message('debug', "add_new_scan(): user is authorized: $tokenid");
            if($tokenid === FALSE) {
                log_message('info', 'Request to add_new_scan() without tokenid');
                $this->output->set_status_header('403');
            } else {
                $kind = $this->input->get('kind');
                if($kind === FALSE || ($kind != 'scan' && $kind != 'template')) {
                    log_message('info', 'Request dose not have the "kind" query parameter');
                    $thid->output
                        ->set_status_header('400')
                        ->set_output('"kind" query parameter expected to be either "scan" or "template"');
                }
                $token = $this->tokens_model->get_token($tokenid);
                if($token) {
                    log_message('debug', "Token found for $tokenid");
                    log_message('debug', "$tokenid - token_consumed: ".$token['token_consumed']);
                    log_message('debug', "$tokenid - token_type: ".$token['token_type']);
                }
                if(empty($token) || $token["token_consumed"] || $token['token_type'] != 'scan') {
                    log_message('info', "Token invalid or consumed: $tokenid");
                    $this->output->set_status_header('403');
                } else {
                    log_message('debug', 'Saving the fingerprint');
                    if($token["fingerprint_id"]) {
                        if($this->fingerprints_model->update_fingerprint()) {
                            $this->output->set_status_header(204);
                        }
                    } else {
                        $fingerprints_id = $this->fingerprints_model->create_fingerprint();    
                        if($fingerprints_id) {
                            $json_body = json_encode(array('id' => $fingerprints_id));
                            log_message('debug', "add_new_scan() sending json response: $json_body");
                            $this->output
                                ->set_status_header('201')
                                ->set_content_type('application/json')
                                ->set_header('Location: '.$this->config->item('fingerprints_server_url')."api/fingerprintsscans/$fingerprints_id")
                                ->set_output($json_body);
                        } else {
                            $this->output->set_status_header('500', 'Fingerprint not saved.');
                        }

                    }
                    
                }
            }
        } else {
            $this->output->set_status_header('401');
        }
    }
    
    private function render_fingerprint($fingerprint) {
        $filename = $this->config->item('fp_images_folder').$fingerprint["file_name"];
        if(file_exists($filename)) {
            $data['filename'] = $filename;
            $this->load->view('bitmap', $data);
        } else {
            log_message('error', "File $filename not found");
            $this->output->set_status_header('404', 'Image file not found');
        }

    }
    
    public function get_scan($id) {
        log_message('debug', "Getting image file for /api/fingerprintsscans/$id");
        if(is_user_authorized('scans.read')) {
            $fingerprint = $this->fingerprints_model->get_fingerprint($id);

            if($fingerprint) {
                $this->render_fingerprint($fingerprint);
            } else {
                log_message('info', "Received request for non existing scan id: $id.");
                $this->output->set_status_header('404', 'Scan data not found');
            }
        } else {
            $this->output->set_status_header('401');
        }
    }
    
    public function get_scan_by_tokenid() {
        log_message('debug', "get_scan_by_tokenid() - controller");
        if(is_user_authorized('scans.read') && is_user_authorized('tokens.read')) {
            $tokenid = $this->input->get('tokenid');
            if($tokenid) {
                log_message('debug', "get_skan_by_tokenid - $tokenid");
                $fingerprint = $this->fingerprints_model->get_fingerprint_by_tokenid($tokenid);
                if($fingerprint) {
                    $this->render_fingerprint($fingerprint);
                } else {
                    log_message('info', "There is no scan for tokenid: $tokenid.");
                    $this->output->set_status_header('404', 'Scan data not found');
                }
            } else {
                $this->output->set_status_header('400', 'Tokenid is required');
            }
        }
    }
}
?>