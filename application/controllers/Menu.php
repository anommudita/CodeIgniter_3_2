<?php


class Menu extends CI_Controller
{
    // method construct
    public function __construct()
    {
        parent::__construct();

        // function helper untuk cek akses
        is_logged_in();

        // cek jika user belum login
        // if (!$this->session->userdata('email')) {
        //     //redirect atau tentang ke halaman login
        //     redirect('auth');
        // }
    }

    public function index()
    {

        // mengambil data session where email
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // echo 'selamat datang ' . $data['user']['name'];

        $data['menu'] = $this->db->get('user_menu')->result_array();

        $data['title'] = 'Menu Management';

        $this->form_validation->set_rules('menu', 'Menu', 'required');

        if($this->form_validation->run() == false){
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('templates/footer');
        }else{
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('menu', '<div class="alert alert-success" role="alert">New menu added!</div>');
            // $this->session->set_flashdata('flash', 'Added');
            redirect('menu');
        }
    }

    public function delete($id){
        
        // $this->menu->deletedMenu($id);
        $this->db->delete('user_menu', ['id'=>$id]);
        $this->session->set_flashdata('menu', '<div class="alert alert-success" role="alert">Delete menu!</div>');
        // $this->session->set_flashdata('flash', 'Deleted');
        redirect('menu');
    }


    // Sub Menu
    public function Submenu(){

        // $this->load->library('session');
        $data['title'] = 'Submenu Management';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // load Model
        $this->load->model('Menu_model', 'menu');
        $data['subMenu'] = $this->menu->getSubMenu();
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu_id', 'required');
        $this->form_validation->set_rules('url', 'Url', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');
        // $this->form_validation->set_rules('is_active', 'Is_active', 'required');


        if($this->form_validation->run() == false){
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('menu/submenu', $data);
        $this->load->view('templates/footer');
        }else{
            // daya yang diinputkan
            $data = [
                'title' => htmlspecialchars($this->input->post('title', true)),
                'menu_id' => htmlspecialchars($this->input->post('menu_id', true)),
                'url' => htmlspecialchars($this->input->post('url', true)),
                'icon' => htmlspecialchars($this->input->post('icon', true)),
                'is_active' => htmlspecialchars($this->input->post('is_active', true))

            ];

            $this->db->insert('user_sub_menu', $data);
            $this->session->set_flashdata('menu',
                '<div class="alert alert-success" role="alert">Added submenu!</div>'
            );
            redirect('menu/submenu');
        }
    }

    public function deleteSubmenu($id)
    {

        // $this->menu->deletedMenu($id);
        $this->db->delete('user_sub_menu', ['id' => $id]);
        $this->session->set_flashdata('menu', '<div class="alert alert-success" role="alert">Delete submenu!</div>');
        // $this->session->set_flashdata('flash', 'Deleted');
        redirect('menu/submenu');
    }


    public function edit($id){

        $this->db->update('user_menu', ['menu' => $this->input->post('menu')], ['id' => $id]);
        
    }

    public function getedit(){
        $result = json_encode($this->db->get_where('user_menu', ['id' => $this->input->post('id')])->row_array());
        echo $result;
    
    }


}
