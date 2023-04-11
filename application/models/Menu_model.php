<?php 


class Menu_model extends CI_Model{

    public function getSubMenu(){

    $this->load->library('session');
    $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();


        $query = "SELECT `user_sub_menu`.*,`user_menu`.`menu`
                    FROM `user_sub_menu` JOIN `user_menu`
                    ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
                    ";

        // menampilkan data semua join ke user_menu
        return $this->db->query($query)->result_array();
    }
}

?>