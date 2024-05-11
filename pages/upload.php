<?php
if (is_user_logged_in()) {
define("MAX", 1000000);

$feedback = array(
  'general_error' => False,
  'size' => False
);



$form_values = array(
  'roster_name' => '',
  'ppg' => '',
  'rpg' => '',
  'apg' => '',
  'spg' => '',
  'bpg' => '',
  'file_ext' => '',
  'source' => ''
);

$selected_tags = array();


if (isset($_POST["upload"])) {

  $form_values['roster_name'] = trim($_POST['name']);
  $form_values['ppg'] = trim($_POST['ppg']);
  $form_values['rpg'] = trim($_POST['rpg']);
  $form_values['apg'] = trim($_POST['apg']);
  $form_values['spg'] = trim($_POST['spg']);
  $form_values['bpg'] = trim($_POST['bpg']);
  $form_values['checkbox_1'] = $_POST['checkbox_1'] ? true : false;
  $form_values['checkbox_2'] = $_POST['checkbox_2'] ? true : false;
  $form_values['checkbox_3'] = $_POST['checkbox_3'] ? true : false;
  $form_values['checkbox_4'] = $_POST['checkbox_4'] ? true : false;
  $form_values['checkbox_5'] = $_POST['checkbox_5'] ? true : false;
  $form_values['checkbox_6'] = $_POST['checkbox_6'] ? true : false;
  $form_values['checkbox_7'] = $_POST['checkbox_7'] ? true : false;
  $form_values['checkbox_8'] = $_POST['checkbox_8'] ? true : false;
  $form_values['checkbox_9'] = $_POST['checkbox_9'] ? true : false;
  $form_values['file_ext'] = trim($_POST['file_ext']);
  $source = trim($_POST['source']); // untrusted
    if (empty($usource)) {
      $form_values['source'] = NULL;
    } else {
      $form_values['source'] = trim($_POST['source']);
    }


    if ($form_values['checkbox_1']){
      array_push($selected_tags, 1);
    }
    if ($form_values['checkbox_2']){
      array_push($selected_tags, 2);
    }
    if ($form_values['checkbox_3']){
      array_push($selected_tags, 3);
    }
    if ($form_values['checkbox_4']){
      array_push($selected_tags, 4);
    }
    if ($form_values['checkbox_5']){
      array_push($selected_tags, 5);
    }
    if ($form_values['checkbox_6']){
      array_push($selected_tags, 6);
    }
    if ($form_values['checkbox_7']){
      array_push($selected_tags, 7);
    }
    if ($form_values['checkbox_8']){
      array_push($selected_tags, 8);
    }
    if ($form_values['checkbox_9']){
      array_push($selected_tags, 9);
    }


$upload = $_FILES['png-file'];

$form_valid = True;

  if ($upload['error'] == UPLOAD_ERR_OK) {

    $file_name = basename($upload['name']);

    $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (!in_array($file_type, array('png'))) {
      $form_valid = False;
      $feedback['general_error'] = True;
    }
  } else if (($upload['error'] == UPLOAD_ERR_INI_SIZE) || ($upload['error'] == UPLOAD_ERR_FORM_SIZE)) {
    $form_valid = False;
    $feedback['size'] = True;
  } else {
    $form_valid = False;
    $feedback['general_error'] = True;
  }

if ($form_valid) {
  $result = exec_sql_query(
    $db,
    "INSERT INTO player_stats (roster_name, ppg, rpg, apg, spg, bpg, file_ext, source) VALUES (:roster_name, :ppg, :rpg, :apg, :spg, :bpg, :file_ext, :source)",
    array(
      ':roster_name' => $form_values['roster_name'],
      ':ppg' => $form_values['ppg'],
      ':rpg' => $form_values['rpg'],
      ':apg' => $form_values['apg'],
      ':spg' => $form_values['spg'],
      ':bpg' => $form_values['bpg'],
      ':file_ext' => $form_values['file_ext'],
      ':source' => $source
    )
  );

  $entry_id = $db -> lastInsertId('id');

  foreach ($selected_tags as $tag){
    exec_sql_query($db,
    "INSERT INTO player_tags (player_id, tag_id) VALUES (:player_id, :tag_id)",
    array(
      ':player_id' => $entry_id,
      ':tag_id' => $tag
    )
    );
  }

  if ($result) {


    $path = 'public/uploads/player_stats/' . $entry_id . '.' . $form_values['file_ext'];

    if (move_uploaded_file($upload["tmp_name"], $path) == False) {
      error_log("Failed to permanently store the uploaded file on the file server. Please check that the server folder exists.");
    }
  }
}
}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title> Boston Celtics Roster Catalog</title>
  <link rel="stylesheet" type="text/css" href="/public/styles/site.css" media="all">
  <link href="https://fonts.cdnfonts.com/css/sports-jersey" rel="stylesheet">
  <!-- Source: https://www.cdnfonts.com/sports-jersey.font -->
</head>

<style>
  @import url('https://fonts.cdnfonts.com/css/sports-jersey');
</style>

<body>
<?php include ("includes/header.php"); ?>

<?php
if (is_user_logged_in()) {
?>

<div class = "upload">

    <h2>Upload Players</h2>

    <form action="/upload" method="post" enctype="multipart/form-data" class = "upload-form">

    <input type="hidden" name="MAX" value="<?php echo MAX; ?>">

    <div>
        <label for="upload-file">PNG File:</label>
        <input id="upload-file" type="file" name="png-file" accept="image/png">
    </div>

    <div>
        <label for="name" class ="txtt">Name:</label>
        <input id="name" type="text" name="name" >
    </div>
    <div>
        <label for="ppg" class ="txtt">Points per game:</label>
        <input id="ppg" type="text" name="ppg" >
    </div>
    <div>
        <label for="rpg" class ="txtt">Rebounds per game:</label>
        <input id="rpg" type="text" name="rpg" >
    </div>
    <div>
        <label for="apg" class ="txtt">Assists per game:</label>
        <input id="apg" type="text" name="apg" >
    </div>
    <div>
        <label for="spg" class ="txtt">Steals per game:</label>
        <input id="spg" type="text" name="spg" >
    </div>
    <div>
        <label for="bpg" class ="txtt">Blocks per game:</label>
        <input id="bpg" type="text" name="bpg" >
    </div>
    <div>
        <label for="cb1" class ="txtt">Starter</label>
        <input id="cb1" type="checkbox" name="checkbox_1" >
    </div>
    <div>
        <label for="cb2" class ="txtt">Sixth Man</label>
        <input id="cb2" type="checkbox" name="checkbox_2" >
    </div>
    <div>
        <label for="cb3" class ="txtt">Role Player</label>
        <input id="cb3" type="checkbox" name="checkbox_3" >
    </div>
    <div>
        <label for="cb4" class ="txtt">Bench Warmer</label>
        <input id="cb4" type="checkbox" name="checkbox_4" >
    </div>
    <div>
        <label for="cb5" class ="txtt">Point Guard</label>
        <input id="cb5" type="checkbox" name="checkbox_5" >
    </div>
    <div>
        <label for="cb6" class ="txtt">Shooting Guard</label>
        <input id="cb6" type="checkbox" name="checkbox_6" >
    </div>
    <div>
        <label for="cb7" class ="txtt">Small Forward</label>
        <input id="cb7" type="checkbox" name="checkbox_7" >
    </div>
    <div>
        <label for="cb8" class ="txtt">Power Forward</label>
        <input id="cb8" type="checkbox" name="checkbox_8" >
    </div>
    <div>
        <label for="cb9" class ="txtt">Center</label>
        <input id="cb9" type="checkbox" name="checkbox_8" >
    </div>
    <div>
        <label for="file_ext" class ="txtt">File type:</label>
        <input id="file_ext" type="text" name="file_ext" >
    </div>
    <div>
        <label for="upload-source" class="optional">Source URL:</label>
        <input id='upload-source' type="url" name="source" placeholder="URL where found. (optional)">
    </div>
    <div">
        <button class = "button1" type="submit" name="upload">Upload</button>
    </div>
    </div>
    </form>
    <a class = "logout" href="<?php echo logout_url(); ?>">Log Out</a>
</div>
<?php
} else {?>
  <p>Please login to access this feature, or click <a class = "home_but" href = "/">Home</a> to view the catalog</p>
  <div class = "login">
  <h2 class = "login-t">Log In</h2>

<?php echo login_form($_SERVER['REQUEST_URI'], $session_messages);
} ?>
</div>



</body>

</html>
