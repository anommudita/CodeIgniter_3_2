<?php 
    function is_logged_in(){

        // instasiasi class CI
        // untuk memanggil Controller CI karena ini adalah helper yang kita buat sendiri
        $ci = get_instance();


        // memanggil session dari controller CI
        if(!$ci->session->userdata('email')){
            redirect('auth');
        }else{
            // role id
            $role_id = $ci->session->userdata('role_id');

            // menu atau mengambil controller
            // baca dokumentasi CI url segment bagian news,local, metro, crime_is_up
            $menu = $ci->uri->segment('1');


            // Query Table Menu
            $queryMenu = $ci->db->get_where('user_menu', ['menu' => $menu])->row_array();

            // mengambil id dari tabel menu
            $menu_id = $queryMenu['id'];

            // Query Table User Access Menu
            $userAccess = $ci->db->get_where('user_access_menu', ['role_id' => $role_id, 'menu_id' => $menu_id]);

            // lebih kecil dari 1 hasil 0 atau -1 jadinya tidak ada akses
            // jika lebih daripada 1 maka ada akses user 
            if($userAccess->num_rows() < 1){
                redirect('auth/blocked');
            }
        }
    }


    // function check_access yang menerima pramater id role dan id menu
    function check_access($role_id, $menu_id){

        // instasiasi class CI
        $ci = get_instance();

        // Query Table User Access Menu
        $result = $ci->db->get_where('user_access_menu', ['role_id' => $role_id, 'menu_id' => $menu_id]);

        // var_dump($result->num_rows());
        // die;

        // perkondisian jika ada hasilnya
        if($result->num_rows() > 0 ){
            // kirimkan nilai checked ke html
            return "checked='checked'";
        }
        ;
    }



?>