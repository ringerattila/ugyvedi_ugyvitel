<?php
session_start(); //we need to start session in order to access it through CI
defined('BASEPATH') or exit('No direct script access felh allowed');

class Uzenetek extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Load form helper library
        $this->load->helper('form');

        // Load form validation library
        $this->load->library('form_validation');


        // Load database
        $this->load->model('uzenetek_model');

        // Load database
        $this->load->model('login_database');
    }


    public function index($irany)
    {
        $username = $_SESSION['userdata']['logged_in']['username'];
        $data['userdata'] = $this->login_database->read_user_information($username);
        $userid = $data['userdata'][0]->id;
        switch ($irany) {
            case 'erkezett':
                $data['query'] = $this->uzenetek_model->getData('erkezett', $userid);
                $data['title'] = 'Beérkezett üzenetek';
                $data['pageheading'] = 'Beérkezett üzenetek';
                break;
            case 'elkuldott':
                $data['query'] = $this->uzenetek_model->getData('elkuldott', $userid);
                $data['title'] = 'Elküldött üzenetek';
                $data['pageheading'] = 'Elküldött üzenetek';
                break;
            case 'minden':
                $data['query'] = $this->uzenetek_model->getData('minden', $userid);
                $data['title'] = 'Üzenetek';
                $data['pageheading'] = 'Üzenetlista';
                break;
        }
        $this->load->view('templates/header', $data);

        $this->load->view('templates/menu', $data);
        $this->load->view('uzenetlista', $data);
    }

    public function kuldes()
    { // Üzenetküldés
        //
        // Üzenetküldő form betöltése
        $username = $_SESSION['userdata']['logged_in']['username'];
        $data['userdata'] = $this->login_database->read_user_information($username);
        $data['title'] = 'Üzenetküldés';
        $data['pageheading'] = 'Üzenetküldés';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/menu', $data);
        $this->load->view('uzenetkuldes_form');
    }

    // Üzenetküldő form ellenőrzése, és üzenet elküldése
    public function uzenetkuldes()
    {

        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('cimzettid', 'Címzett', 'trim|required|xss_clean');
        $this->form_validation->set_rules('targy', 'Tárgy', 'trim|required|xss_clean');
        $this->form_validation->set_rules('uzenet', 'Üzenet', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $username = $_SESSION['userdata']['logged_in']['username'];
            $data['userdata'] = $this->login_database->read_user_information($username);
            $data['title'] = 'Üzenetküldés';
            $data['pageheading'] = 'Üzenetküldés';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu', $data);
            $this->load->view('uzenetkuldes_form');
        } else {
            $username = $_SESSION['userdata']['logged_in']['username'];
            $data['userdata'] = $this->login_database->read_user_information($username);
            $feladoid = $data['userdata'][0]->id;

            $data = array(
                'feladoid' => $feladoid,
                'cimzettid' => $this->input->post('cimzettid'),
                'targy' => $this->input->post('targy'),
                'uzenet' => $this->input->post('uzenet'),
            );
            $result = $this->uzenetek_model->uzenet_insert($data);
            if ($result == TRUE) {
                $data['message_display'] = 'Üzenet elküldve!';
                $username = $_SESSION['userdata']['logged_in']['username'];
                $data['userdata'] = $this->login_database->read_user_information($username);
                $userid = $data['userdata'][0]->id;
                $data['query'] = $this->uzenetek_model->getData('minden', $userid);
                $data['title'] = 'Üzenet lista';
                $data['pageheading'] = 'Üzenet lista';
                $this->load->view('templates/header', $data);
                $this->load->view('templates/menu', $data);
                $this->load->view('uzenetlista');
            } else {
                $data['message_display'] = 'Username already exist!';
                $this->load->view('registration_form', $data);
            }
        }
    }
}
