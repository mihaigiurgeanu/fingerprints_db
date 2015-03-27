<?php
class Persons extends CI_Controller {
    
    public function add()
    {
        $data['sidebar_active'] = "persons";
        $data['page_scripts'] = ['addperson.js'];
        
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('first-name', 'First Name', 'required');
        $this->form_validation->set_rules('last-name', 'Last Name', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('news/create');
            $this->load->view('templates/footer');

        }
        else
        {
            $this->news_model->set_news();
            $this->load->view('news/success');
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('pages/add_person_form', $data);
        $this->load->view('templates/footer', $data);

    }
}
?>