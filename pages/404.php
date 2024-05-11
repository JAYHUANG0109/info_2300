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

<?php if (!is_user_logged_in()) { ?>
    <div class = "login">
      <h2 class = "login-t">Log In</h2>
    <?php echo login_form('/upload', $session_messages);
    } ?>
    </div>

<div class = "msg444">
    <h1 class = "msg4">404</h1>
    <h1>Page Not Found</h1>
    <h2 class = "msg44">Oops! it seems this page does not exist. Check the URL and see if there is any error, or you can use the navigation bar to take you back to my website.</h2>
    </div>

    <?php if (is_user_logged_in()) { ?>
      <a class = "logout" href="<?php echo logout_url(); ?>">Log Out</a>
    <?php } ?>

</body>

</html>
