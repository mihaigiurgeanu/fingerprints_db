<?php
class Persons extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('fingerprints_model');
        $this->load->model('persons_model');

        $this->load->helper('authorization');
    }
    
    public function add()
    {
        $data['sidebar_active'] = "persons";
        
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $data['page_scripts'] = ['addperson.js'];
    
            $this->load->view('templates/header', $data);
            $this->load->view('pages/add_person_form', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $this->persons_model->add_person();
            $data['persons'] = $this->persons_model->load_persons();
            $data['page_scripts'] = ['verify_person.js'];
            
            
            $this->load->view('templates/header', $data);
            $this->load->view('pages/persons', $data);
            $this->load->view('templates/footer', $data);
        }
        

    }
    
    public function persons($id='') {
        $data['sidebar_active'] = 'persons';
        $data['page_scripts'] = ['verify_person.js'];

        $this->load->view('templates/header', $data);
        if($id) {
            $data['person'] = $this->persons_model->load_person($id);
            $this->load->view('pages/person', $data);
        } else {
            $data["persons"] = $this->persons_model->load_persons();
            $this->load->view('pages/persons', $data);
        }
        
        $this->load->view('templates/footer', $data);
    }
}
?>