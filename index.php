<!doctype html>
<?php
  require 'wall.php';
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?php get_title(); ?></title>
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta property="og:title" content="" />
  <meta property="og:description" content="" />
  <link rel="stylesheet" href="css/style.css">
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body>
  <div id=header>
    <h1><?php get_title(); ?></h1>
    <p>description</p>

  </div>
  <div id=mainArea>
  <?php
    if ($UserSubmission) {
		  print_alert("Submission accepted. It'll show up eventually.");
	  }
	?>
  <div id=content>
  <?php	get_post($PostsDirectory); ?>
    <p id=share-buttons>
      <a href="https://twitter.com/share?url=<?php permalink(); ?>&amp;text=<?php post_summary(); ?>&amp;hashtags=fuckBlueCross" target="_blank">
        <img src="https://simplesharebuttons.com/images/somacro/twitter.png" alt="Twitter" />
      </a>
      <a href="http://www.facebook.com/sharer.php?u=<?php permalink(); ?>" target="_blank">
        <img src="https://simplesharebuttons.com/images/somacro/facebook.png" alt="Facebook" />
      </a>
      <a href="http://reddit.com/submit?url=<?php permalink(); ?>&amp;title=<?php post_summary(); ?>" target="_blank">
        <img src="https://simplesharebuttons.com/images/somacro/reddit.png" alt="Reddit" />
      </a>
    </p>
  </div>
  <div id=below><a href="index.php">Give me another!</a></div>
  <div id=submitStory>
  <form action="index.php" method="post">
    <textarea name="s" cols="40" rows="5" placeholder="What's your story? Replying? Use @[post_number] to autolink."></textarea><br />
    <div class="g-recaptcha" data-sitekey="<?php recaptcha_site_key(); ?>"></div>
    <input id="submitButton" type="submit" value="Share Story">
  </form>
  </div>
  </div>
<!--   <script src="js/scripts.js"></script> -->
</body>
</html>