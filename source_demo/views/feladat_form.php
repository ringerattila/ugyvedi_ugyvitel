<div id="main">
  <?php
  echo "<div class='error_msg'>";
  echo validation_errors();
  echo "</div>";

  $attributes = array('class' => 'feladat_form');
  echo form_open('feladatok/feladat_modositas', $attributes);

  echo form_hidden('feladatid', $feldata[0]['feladatid']);

  ?>
  <input type="hidden" name="felvalt" id="felvalt" value="nem" />
  <input type="hidden" name="status" id="status" value="<?php echo $feldata[0]['status']; ?>" />

  <h2><?php echo $pageheading . ' - ' . $feldata[0]['sorszam']; ?></h2>
  <hr />

  <?php
  // a bejövő státusz változó megőrzése
  $prevstatus = $feldata[0]['status'];

  if ($jogok['gazda']) {
    echo form_label('Feladatgazda: ');
    echo "<br/>";

    $users_array = $this->login_database->get_ugyintezok();

    $cimzettek = '<select name="gazdaid" onchange="my_aktival(value)">';

    // ha még nincs feladatgazda    
    if ($feldata[0]['gazdaid'] < 1) {
      $cimzettek .= '<option value="">Feladatgazda választása</option>';
    }

    foreach ($users_array as $row) {
      $cimzettek .= '<option value="' . $row['id'] . '"';
      if ($feldata[0]['gazdaid'] === $row['id']) {
        $cimzettek .= ' selected ';
      }
      $cimzettek .= '>' . $row['user_realname'] . '</option>';
    }
    $cimzettek .= '</select>';

    echo $cimzettek;

    echo "<div class='error_msg'>";
    if (isset($message_display)) {
      echo $message_display;
    }
    echo "</div>";
    echo "<br/>";
  } // end of if

  if ($jogok['vegrehajto']) {

    echo form_label('Végrehajtó: ');
    echo "<br/>";
    $users_array = $this->login_database->get_vegrehajtok();

    $cimzettek = '<select name="vegrehajtoid" onchange="my_kiadom(value)">';

    // ha még nincs végrehajtó    
    if ($feldata[0]['vegrehajtoid'] < 1) {
      $cimzettek .= '<option value="">Végrehajtó választása</option>';
    }

    foreach ($users_array as $row) {
      $cimzettek .= '<option value="' . $row['id'] . '"';
      if ($feldata[0]['vegrehajtoid'] === $row['id']) {
        $cimzettek .= ' selected ';
      }
      $cimzettek .= '>' . $row['user_realname'] . '</option>';
    }
    $cimzettek .= '</select>';

    echo $cimzettek;

    echo "<div class='error_msg'>";
    if (isset($message_display)) {
      echo $message_display;
    }
    echo "</div>";
    echo "<br/>";
  } // end of if

  echo form_label('Helyszín: ');
  echo "<br/>";
  echo form_input('helyszin', $feldata[0]['helyszin']);
  echo "<div class='error_msg'>";
  if (isset($message_display)) {
    echo $message_display;
  }
  echo "</div>";
  echo "<br/>";

  echo form_label('Feladat: ');
  echo "<br/>";
  ?>
  <textarea height="100%" id="feladat_text" name="feladat" rows="4" cols="30" onchange="feladat_valtozott(value)"><?php echo $feldata[0]['uzenet']; ?></textarea>

  <?php

  // echo form_input('uzenet');
  echo "<div class='error_msg'>";
  if (isset($message_display)) {
    echo $message_display;
  }
  echo "</div>";
  echo "<br/>";

  if ($jogok['hatarido']) {
    echo form_label('Határidő: ');
    echo "<br/>";
    echo form_input('hatarido', $feldata[0]['hatarido']);
    echo "<div class='error_msg'>";
    if (isset($message_display)) {
      echo $message_display;
    }
    echo "</div>";
    echo "<br/>";
  } // end of if

  if ($jogok['kategoria']) {
    echo form_label('Kategória: ');
    echo "<br/>";

    $kat_array = $this->feladatok_model->get_aktiv_kategoriak();
    $kategoria = '<select name="kategoriaid"><option value="">Feladat kategória választása</option>';

    foreach ($kat_array as $row) {
      $kategoria .= '<option value="' . $row['kategoriaid'] . '"';
      if ($feldata[0]['kategoriaid'] === $row['kategoriaid']) {
        $kategoria .= ' selected ';
      }

      $kategoria .= '>' . $row['kategorianev'] . '</option>';
    }
    $kategoria .= '</select>';

    echo $kategoria;

    echo "<br/>";
    echo "<br/>";
  } //end of if


  if ($jogok['cimke']) {
    echo form_label('Címkék: ');
    echo "<br/>";
    echo form_input('tags', $feldata[0]['tags']);
    echo "<br/>";
  } // end of if

  // echo"<br/>";
  // echo form_submit('submit', 'Beküldés');
  echo "<br/>";

  echo form_label('Hozzászólás: ');
  echo "<br/>";
  ?>
  <textarea height="100%" id="huzenet_text" name="huzenet" rows="4" cols="30"></textarea>

  <?php
  echo "<br/>";
  echo "<br/>";

  ?>
  <input type="submit" id="bekuld" name="bekuld" value="Módosítás" />
  <?php
  echo "<br/>";

  echo form_close();
  ?>
</div>