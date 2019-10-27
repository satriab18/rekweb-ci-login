<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        
    }

    public function index(){
        $data['title'] = 'Zeeny Login';
        $this->load->view('templates/auth_header', $data);
        $this->load->view('auth/login');
        $this->load->view('templates/auth_footer');
    }

    public function register(){
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[user.email]',[
            'is_unique' => 'Email already Registered'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|matches[password2]', [
            'matches' => 'password didnt matches',
            'min_length' => 'Password to Short!'
        ]);
        $this->form_validation->set_rules('password2', 'Password Confirm', 'trim|required|min_length[6]|matches[password]');

        if ($this->form_validation->run()==false) {
            $data['title'] = 'Zeeny User Registration';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/register');
            $this->load->view('templates/auth_footer');
        }else{
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 1,
                'date_created' => time()
            ];

            $this->db->insert('user', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert"> Account Has Been Created ! Please Login </div>');
            redirect('auth');
        }
    }
}
