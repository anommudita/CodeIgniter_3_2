<?php


class User extends CI_Controller
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

        $data['title'] = 'My Profile';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }

    public function edit(){

        // mengambil data session where email
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // echo 'selamat datang ' . $data['user']['name'];

        $data['title'] = 'Edit Profile';

        // form validation rules
        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');

        // form validation
        if($this->form_validation->run() == false){
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates/footer');
        }else{
            

            //input name dan email
            $name = $this->input->post('name');
            $email = $this->input->post('email');

            // cek jika ada gambar yang akan diupload maka ada variable $_Files
            $upload_image = $_FILES['image']['name'];

            // user tidak boleh upload file berbahaya dan ukuran file yang besar
            if($upload_image){
                // file format yang boleh diupload
                $config['allowed_types'] = 'gif|jpg|png';

                // ukuran file yang boleh diupload lebih dari 2Mb
                $config['max_size']     = '2048';

                // lokasi penyimpanan file
                $config['upload_path'] = './assets/img/profile/';
                
                // ukuran file
                // $config['max_width'] = '1024';
                // $config['max_height'] = '768';

                $this->load->library('upload', $config);

                if($this->upload->do_upload('image')){

                    // agar tidak terjadi duplikasi gambar dan replace gambar 
                    // gambar lama
                    $old_image = $data['user']['image'];
                    // jika gambar lama tidak sama dengan default.svg
                    if($old_image != 'default.svg'){
                        // hapus gambar lama
                        unlink(FCPATH. 'assets/img/profile/'. $old_image);
                    }

                    // ambil gambar baru akan diupdate db
                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                }else{
                    // jika gagal
                    echo $this->upload->display_errors();
                }


            }

            // update table user
            $this->db->update('user', ['name' => $name], ['email' => $email]);


            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Your profile has been updated!</div>');

            redirect('user');
        }

        }


    // Method ChangePassword 
    public function changePassword(){
        // mengambil data session where email
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        
        // echo 'selamat datang ' . $data['user']['name'];
        $data['title'] = 'Change Password';
        
        $this->form_validation->set_rules('passwordcurrent', 'Current Password', 'required|trim');

        $this->form_validation->set_rules('newpassword1', 'New Password', 'required|trim|min_length[8]|matches[newpassword2]',
        [
            'matches' => 'Password dont match!',
            'min_length' => 'Password too short!'
        ]);

        $this->form_validation->set_rules('newpassword2', 'Confirm Password', 'required|trim|min_length[8]|matches[newpassword1]');

        if($this->form_validation->run() == false){

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/changepassword', $data);
            $this->load->view('templates/footer');

        }else{
            $curentPassword = $this->input->post('passwordcurrent');
            $newpassword = $this->input->post('newpassword1');

            if(!password_verify($curentPassword, $data['user']['password'])){
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong Current Password</div>');
                redirect('user/changepassword');
            }else{
                if($curentPassword == $newpassword){
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">New password cannot be the same as current </div>');
                    redirect('user/changepassword');
                }else{
                    // password sudah ok 
                    // password diterjemahkan
                    $password_hash = password_hash($newpassword, PASSWORD_DEFAULT);

                    $this->db->set('password', $password_hash);
                    $this->db->where('email', $this->session->userdata('email'));
                    $this->db->update('user');
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password Changes!</div>');
                    redirect('user/changepassword');
                }
            }

        }


    }

}

?>