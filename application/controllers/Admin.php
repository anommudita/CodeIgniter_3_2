<?php


class Admin extends CI_Controller
{

    // method construct
    public function __construct(){
        parent::__construct();
        
        // function helper untuk cek akses
        is_logged_in();

        // cek jika user belum login
        // if(!$this->session->userdata('email')){
        //     //redirect atau tentang ke halaman login
        //     redirect('auth');
        // }
    }

    public function index()
    {

        // mengambil data session where email
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // echo 'selamat datang ' . $data['user']['name'];

        $data['title'] = 'Dashboard';


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer');
    }

    public function role()
    {

        // bisa taruh di construct biar gak diulang!
        // mengambil data session where email
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // echo 'selamat datang ' . $data['user']['name'];

        $data['title'] = 'Role';

        $data['role'] = $this->db->get('user_role')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role', $data);
        $this->load->view('templates/footer');
    }


    public function roledelete($id){
        // $this->db->where('id', $id);
        $this->db->delete('user_role', ['id' => $id]);

        // set flash data
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Role has been deleted!</div>');
        redirect('admin/role');
    }


    public function roleAccess($role_id)
    {

        // bisa taruh di construct biar gak diulang!
        // mengambil data session where email
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();



        $data['title'] = 'Role Access';

        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        // chekc access pada admin dihilangkan karena admin tidak bisa dihapus
        $this->db->where('id !=', 1);

        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('templates/footer');
    }

    // method admin
    public function changeAccess(){


        // data yang dikirimkan jika tidak ada data
        if(empty($this->input->post('menuId')) || empty($this->input->post('roleId'))){
            redirect('auth/blocked');
        }

        // data yang dikirimkan oleh ajax difooter.php
        $menu_id = $this->input->post('menuId');
        $role_id= $this->input->post('roleId');

        // ambil data dari tabel user_access_menu
        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];
        
        // query apakah ada atau tidak. jika ada mak hapus dan jika tidak ada maka tambahkan
        $result = $this->db->get_where('user_access_menu', $data);

        if($result->num_rows() < 1){
            // query insert 
            $this->db->insert('user_access_menu', $data);
        }else{
            //query delete
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Acess Changed!</div>');
        
    }
}
