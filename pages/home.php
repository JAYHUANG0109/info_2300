<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');

$filter = $_GET['filter'] ?? NULL;

$tags = exec_sql_query($db,
"SELECT * FROM tags");

if ($filter){
  $result = exec_sql_query($db,
  "SELECT
  player_stats.id AS 'player_stats.id',
  player_stats.roster_name AS 'player_stats.name',
  player_stats.file_ext AS 'player_stats.file_ext',
  player_stats.source AS 'player_stats.source',
  player_tags.tag_id AS 'player_tags.tag_id',
  player_tags.player_id AS 'player_tags.player_id'
  FROM player_stats
  INNER JOIN player_tags ON (player_tags.player_id = player_stats.id)
  WHERE (player_tags.tag_id = :filter_id);",
        array(
          ':filter_id' => $filter));
} else {
  $result = exec_sql_query($db,
  "SELECT
  player_stats.id AS 'player_stats.id',
  player_stats.roster_name AS 'player_stats.name',
  player_stats.file_ext AS 'player_stats.file_ext',
  player_stats.source AS 'player_stats.source'
  FROM player_stats");
}

$records = $result->fetchAll();


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


<div class = "content">
  <div class = "sidebar">
    <div class = "filter_class">
        <h3>Filter By Tag</h3>
        <p class = "ft"><a class = "after_filter" href="/">All</a></p>
        <?php foreach ($tags as $tag) { ?>
        <a class = "after_filter" href="/?<?php echo http_build_query(array('filter' => $tag['id'])); ?>"><?php echo htmlspecialchars($tag['name']); ?></a>
        <?php } ?>
        <a class = "after_filter" href = "/upload">Upload Players</a>
    </div>
  </div>

  <?php if (!is_user_logged_in()) { ?>
    <div class = "login">
      <h2 class = "login-t">Log In</h2>
    <?php echo login_form('/upload', $session_messages);
    } ?>
    </div>


  <div class="catalog">
    <?php
      foreach ($records as $record) {
    ?>
    <div class = "catalog-stuff">
        <a href="/details?<?php echo http_build_query(array('id' => $record['player_stats.id'])); ?>">
          <img
          class="c_img"
          src="public/uploads/player_stats/<?php echo htmlspecialchars($record['player_stats.id']). '.' . $record['player_stats.file_ext']; ?>"
          alt = "<?php echo htmlspecialchars($record['player_stats.name']);?>"/>
          <a class = "source" href = "<?php echo ($record['player_stats.source']); ?>">Source</a>
          <p class = "name"><?php echo htmlspecialchars($record['player_stats.name']); ?></p>
        </a>
    </div>
    <?php } ?>
  </div>



  <?php if (is_user_logged_in()) { ?>
      <a class = "logout" href="<?php echo logout_url(); ?>">Log Out</a>
    <?php } ?>


</div>

</body>

</html>
