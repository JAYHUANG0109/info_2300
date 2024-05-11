<?php

const pos = array(
  1 => 'PG',
  2 => 'SG',
  3 => 'SF',
  4 => 'PF',
  5 => 'C'
);

$id = $_GET['id'] ?? NULL;

$player_result = exec_sql_query($db,
  "SELECT
  player_stats.id AS 'player_stats.id',
  player_stats.roster_name AS 'player_stats.name',
  player_stats.file_ext AS 'player_stats.file_ext',
  player_stats.source AS 'player_stats.source'
  FROM player_stats
  WHERE (player_stats.id = :id);",
        array(
          ':id' => $id));

$player_records = $player_result->fetchAll();


$tag_result = exec_sql_query($db,
"SELECT
tags.id AS 'tags.id',
tags.name AS 'tags.name',
player_tags.tag_id AS 'player_tags.tag_id',
player_tags.player_id AS 'player_tags.player_id'
FROM tags
INNER JOIN player_tags ON (player_tags.tag_id = tags.id)
WHERE (player_tags.player_id = :id);",
        array(
          ':id' => $id));

$tag_records = $tag_result->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="/public/styles/site.css" media="all" />
  <style>
    @import url('https://fonts.cdnfonts.com/css/sports-jersey');
  </style>
</head>

<body>
  <?php include("includes/header.php"); ?>

  <div class = "content">
  <div class = "player-left">
  <?php if (!is_user_logged_in()) { ?>
    <div class = "login">
      <h2 class = "login-t">Log In</h2>
    <?php echo login_form('/upload', $session_messages);
    } ?>
    </div>

    <div class = "info">

    <div class = "wrap">
      <div class = "left">
    <?php
    foreach ($player_records as $record) { ?>
    <img
    class = "d_img"
    src = "public/uploads/player_stats/<?php echo htmlspecialchars($record['player_stats.id']). '.' . $record['player_stats.file_ext']; ?>"
    alt="<?php echo htmlspecialchars($record['player_stats.id']); ?>" />
    <a class = "source2" href = "<?php echo ($record['player_stats.source']); ?>">Source</a>
    <p class = "name2"><?php echo htmlspecialchars($record['player_stats.name']); ?></p>

    <?php } ?>
      </div>

    </div>
    </div>


<table>
    <tr>
      <th>Points/Game</th>
      <th>Rebounds/Game</th>
      <th>Assists/Game</th>
      <th>Steals/Game</th>
      <th>Blocks/Game</th>
    </tr>

    <?php $resultoo = exec_sql_query($db,
  "SELECT * FROM player_stats
  WHERE (id = :id);",
        array(
          ':id' => $id));

  $records2 = $resultoo->fetchAll();

?>
    <?php
    foreach ($records2 as $record2) { ?>
      <tr>
        <td><?php echo htmlspecialchars($record2['ppg']); ?></td>
        <td><?php echo htmlspecialchars($record2['rpg' ]); ?></td>
        <td><?php echo htmlspecialchars($record2["apg"] ); ?></td>
        <td><?php echo htmlspecialchars($record2["spg"] ); ?></td>
        <td><?php echo htmlspecialchars($record2["bpg"] ); ?></td>
      </tr>
    <?php } ?>

</table>
  </div>
      <div class = "sidebar2">
      <h3>Player Information</h3>
      <?php
      foreach ($tag_records as $record) { ?>
      <p class = "dt_tags"><?php echo htmlspecialchars($record['tags.name']); ?></p>

      <?php } ?>
      </div>

      <?php if (is_user_logged_in()) { ?>
      <a class = "logout" href="<?php echo logout_url(); ?>">Log Out</a>
    <?php } ?>

  </div>
</body>

</html>
