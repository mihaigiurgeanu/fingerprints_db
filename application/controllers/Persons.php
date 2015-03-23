<?php
class Persons extends CI_Controller {
    
    public function add()
    {
        $data['sidebar_active'] = "persons";

        $this->load->view('templates/header', $data);
        $this->load->view('pages/person.php', $data);
        $this->load->view('templates/footer', $data);

    }
}
?>