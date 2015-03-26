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
        if(is_user_authorized('scans.create')) {
            $tokenid = $this->input->get('tokenid');
            if($tokenid === FALSE) {
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
                            ->set_header('Location: '.$this->config->item('fingerprints_server_url')."api/fingerprintsscans/$fingerprints_id")
                            ->set_output(json_encode(array('id' => $fingerprints_id)));
                    } else {
                        $this->output->set_status_header('500', 'Fingerprint not saved.');
                    }
                }
            }
        } else {
            $this->output->set_status_header('401');
        }
    }
    
    public function get_scan($id) {
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
                    log_message('error', "File $filename not found (serving scan for id {$id})");
                    $this->output->set_status_header('400', 'Image file not found');

                }
            } else {
                log_message('info', "Received request for non existing scan id: $id.");
                $this->output->set_status_header('400', 'Scan data not found');
            }
        } else {
            $this->output->set_status_header('401');
        }
    }
}
?>