<?php if (!defined('BASEPATH')) exit('Közvetlen elérés letiltva!');

// class Felhasznalok_model extends CI_Model {
class Uzenetek_model extends CI_Model
{


  // Üzenetk adatbázisba beszúrása
  public function uzenet_insert($data)
  {

    // Query to insert data in database
    $this->db->insert('uzenetek', $data);
    return true;
  }



  function getData($irany, $userid)
  {
    $username = $_SESSION['userdata']['logged_in']['username'];

    switch ($irany) {
      case 'erkezett':
        $query = $this->db->query('SELECT uzenetek.*, '
          . 'f_ul.user_realname as felado, c_ul.user_realname as cimzett '
          . 'FROM uzenetek '
          . 'INNER JOIN felhasznalok f_ul ON uzenetek.feladoid = f_ul.id '
          . 'INNER JOIN felhasznalok c_ul ON uzenetek.cimzettid = c_ul.id '
          . ' WHERE uzenetek.cimzettid=' . $userid . ' ORDER BY uzenetek.feladkelt DESC');


        break;
      case 'elkuldott':
        $query = $this->db->query('SELECT uzenetek.*, '
          . 'f_ul.user_realname as felado, c_ul.user_realname as cimzett '
          . 'FROM uzenetek '
          . 'INNER JOIN felhasznalok f_ul ON uzenetek.feladoid = f_ul.id '
          . 'INNER JOIN felhasznalok c_ul ON uzenetek.cimzettid = c_ul.id '
          . ' WHERE uzenetek.feladoid=' . $userid . ' ORDER BY uzenetek.feladkelt DESC');
        break;
      case 'minden':
        $query = $this->db->query('SELECT uzenetek.*, '
          . 'f_ul.user_realname as felado, c_ul.user_realname as cimzett '
          . 'FROM uzenetek '
          . 'INNER JOIN felhasznalok f_ul ON uzenetek.feladoid = f_ul.id '
          . 'INNER JOIN felhasznalok c_ul ON uzenetek.cimzettid = c_ul.id '
          . ' ORDER BY uzenetek.feladkelt DESC');
        break;
    }

    return $query->result_array();
  }
}

/* End of file uzenetek_model.php */
/* Location: ./application/models/uzenetek_model.php */
