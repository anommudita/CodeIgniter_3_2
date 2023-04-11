<?php


class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {

        //  jika sudah login maka tidak bisa mengakses halaman login lagi melalui url
        if($this->session->userdata('email')){
            redirect('user');
        }


        // form validation
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');


        if ($this->form_validation->run() == false) {
            $data['title'] = "Login Page";
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            // validasi sukses!
            // method private agar tidak bisa diakses di luar class
            $this->_login();
        }
    }


    // method private
    private function _login()
    {
        // ambil emal dan password dari form untuk divalidasi
        $email = $this->input->post('email', true);
        $password = $this->input->post('password', true);

        // ambil data user berdasarkan email yang diinputkan
        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        // var_dump($user);
        // die;

        // validasi if
        if ($user) {
            // usernya ada
            // jika usernya aktif
            if ($user['is_active'] == 1) {

                // active usernya
                // cek password
                // menggunakan password_verify untuk membandingkan password yang diinputkan dengan password yang ada di database
                if (password_verify($password, $user['password'])) {
                    // echo 'berhasil login';
                    // menentukan rule access session
                    $data = [
                        "email" => $user['email'],
                        "role_id" => $user['role_id']
                    ];
                    // set session jika dihalaman selanjutkan akan tersimpan datannya di session
                    $this->session->set_userdata($data);

                    // perkondisian untuk menetukan role_id apakah admin atau user!
                    if ($user['role_id'] == 1) {
                        redirect('admin');
                    } else if ($user['role_id'] == 2) {
                        redirect('user');
                    }
                   
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong password!</div>');
                
                    redirect('auth');
                }
            } else {
                // jika usernya tidak aktif}
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">This email has not been activated!</div>');
                redirect('auth');
            }
        } else {
            // usernya tidak ada 
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email is not registered!</div>');
            redirect('auth');
        }
    }


    public function registration()
    {

        //  jika sudah login maka tidak bisa mengakses halaman login lagi melalui url
        if ($this->session->userdata('email')) {
            redirect('user');
        }

        //trim agar tidak ada spasi diawal dan diakhir yang diinputan
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        // is_unique['nama_tabel.nama_field]
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => "This email has already registered!"
        ]);

        $this->form_validation->set_rules(
            'password1',
            'Password',
            'required|trim|min_length[5]|matches[password2]',
            [
                'matches' => 'Password dont match!',
                'min_length' => 'Password too short!'
            ] 
        );

        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Registration Page";
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email', true);
            $data = [
                "name" => htmlspecialchars($this->input->post('name', true)),
                "email" => htmlspecialchars($email),
                "image" => 'default.svg',
                "password" => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                "role_id" => 2,
                "is_active" => 0, // akfitasi email
                "date_created" => time()
            ];

            // siapkan token!
            // function base64_encode untuk mendeskripsi token agar bisa diakses di url
            $token= base64_encode(random_bytes(32));
            // var_dump($token);
            // die;

            // user token 
            $user_token = [
                'email' => $email,
                'token' => $token, 
                // untuk verifikasi dengan waktu saat ini!
                'date_created' => time()
            ];



            $this->db->insert('user', $data);
            $this->db->insert('user_token', $user_token);

            // setelah data berhasil diinputkan maka akan mengirimkan email ke user
            $this->_sendEmail($token, 'verify');

            $this->_sendEmail();
            

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Congratulation! your account has been created. Please Login</div>');
            redirect('auth');
        }
    }

    // Email Class yang ada didocumentasi di codeigniter!
    private function _sendEmail($token, $type){
        $config = [
            // menggunakan protokol smtp(simple mail transfer protocol)
            'protocol' => 'smtp',
            // gooogle
            'smtp_host' => 'ssl://smtp.googlemail.com',
            // email pengirim
            'smtp_user' => 'otweditor007@gmail.com',
            // password email pengirim
            'smtp_pass' => 'dnvyfkdmicynpflu',
            // port
            'smtp_port' => 25,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n"
        ];

        // load library email
        $this->load->library('email', $config);

        //inisialisasi email
        $this->load->initialize($config);

        // email pengirim
        $this->email->from('otweditor007@gmail.com', 'OTW Editor');
        
        // email tujuan
        $this->email->to($this->input->post('email'));


        // jika type emailnya verify 
        if($type == 'verify'){
            // subject email
            $this->email->subject('Account Verification');
            // isi message emailnya
            $this->email->message('Click this link to verify you account : <a href="' . base_url() . 'auth/verify?email=' .  $this->input->post('email') . '&token=' . urlencode($token) . ">Activate</a>");
        }

        // jika email berhasil dikirim
        if($this->email->send()){
            return true;
        }else{
            echo $this->email->print_debugger();
            die;
        }

    }

    // untuk verifikasi email
    public function verify(){
        // mengambil email dan token dari url
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user', [ 'email' => $email])->row_array();

        if($user){
            // jika user ada!
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if($user_token){
                // jika user token ada sesuai dengan database!
                // jika token belum kadaluarsa selama sehari 24 jam
                if(time() - $user_token['date_created'] < (60*60*24)){
                    // update is_active menjadi 1 ==> account is active
                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    $this->db->update('user');

                    // jika sudah aman account maka token dihapus karena tidak dibutuhkan lagi!
                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">'. $email .' has been activated! Please Login.</div>');
                    redirect('auth');

                }else{
                    // jika token sudah kadaluarsa
                    // hapus tokennya atau hapus usernya!

                    $this->db->delete('user', ['email' => $email]);
                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Token Expired</div>');
                    redirect('auth');
                }

            }else{
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Token in failed! Wrong Token</div>');
                redirect('auth');
            }
        }else{
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account activation failed! Wrong Email</div>');
            redirect('auth');
        }
    }


    public function logout()
    {
        // membersihkan session
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You been logged out!</div>');
        redirect('auth');
    }

    public function blocked(){
        $this->load->view('auth/blocked');
    }
}