<?php
// Settings
  $mainURL = "https://website.com/";
	$PostsDirectory = getcwd() . '/posts/';

	$recaptcha_site_key = "Recaptcha public site key";
  $recaptcha_secret = "Recaptcha private secret key";
 // End Settings 
  
  $UserSubmission = _INPUT("s", false);
  $GoTo = _INPUT("g", "0");
  $GoTo = intval($GoTo);
  $post_summary = "";
  $thisURL = "";

  if(isset($_POST['g-recaptcha-response'])) {
          $captcha=$_POST['g-recaptcha-response'];
  }
  if(!$captcha && $UserSubmission){
    echo '<h2>Please check the the captcha form.</h2>';
    exit;
  }

  $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $recaptcha_secret . "&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
  if($response['success'] == false)
  {
    $UserSubmission = 0;
  }
  else
  {
    if ($UserSubmission) {
		  user_posts($PostsDirectory, $UserSubmission);
	  }
  }

  function get_post($directory) {
    global $GoTo;

    if ($GoTo > 0) {
      print_post($directory, $GoTo);
    } else {
      print_post($directory, rand_post($directory));
    }
  }

  function create_reply_links($text) {
    $text = preg_replace("/(?:@)([0-9]*)(?:\s|\n)/", '<a href="' . $mainURL . 'index.php?g=\1">\0</a>', $text);

    return $text;
  }

	function print_post($directory , $post_number) {
  	global $post_summary;
  	global $thisURL;
  	global $mainURL;

		$file_contents = file_get_contents($directory . $post_number . ".txt");

		$file_contents = create_reply_links($file_contents);

		$post_summary = substr($file_contents, 0, 90) . '...';
		$post_summary = trim(preg_replace('/\s\s+/', ' ', $post_summary));
		$post_summary = urlencode($post_summary);
		$thisURL = $mainURL . "index.php?g=" . $post_number;
    echo '<h2><a href="' . $thisURL . '">#' . $post_number . '</a></h2>';
		echo nl2p($file_contents);
	}

	function permalink() {
  	global $thisURL;
  	echo $thisURL;
	}

	function post_summary() {
  	global $post_summary;
  	echo $post_summary;
	}

	function print_alert($text) {
    echo '<div id="alert">';
    echo $text;
    echo '</div>';
	}

	function rand_post($directory) {
		$rand_post = -1;

		$post_number = get_post_count($directory);

		if ($post_number >= 1) {
			$rand_post = rand(1, $post_number);
		} else {
			$rand_post = -2;
		}

		return $rand_post;
	}

	function get_post_count($directory){
		$post_number = 0;

		if (file_exists($directory)){
			$post_list = glob($directory . "*.txt");
			if ($post_list){
				$post_number = count($post_list);
			}
		}
		return $post_number;
	}

	function user_posts($directory, $user_text) {
  	$user_text = strip_tags($user_text);
		$new_post_number = get_post_count($directory) + 1;
		$file_name = $directory . $new_post_number . ".txt";
		if (!(file_exists($file_name))) {
			file_put_contents($file_name, $user_text);
		}
	}

// grabbed from http://stackoverflow.com/questions/7409512/new-line-to-paragraph-function
	function nl2p($string, $line_breaks = true, $xml = true) {

  $string = str_replace(array('<p>', '</p>', '<br>', '<br />'), '', $string);

  // It is conceivable that people might still want single line-breaks
  // without breaking into a new paragraph.
  if ($line_breaks == true)
      return '<p>'.preg_replace(array("/([\n]{2,})/i", "/([^>])\n([^<])/i"), array("</p>\n<p>", '$1<br'.($xml == true ? ' /' : '').'>$2'), trim($string)).'</p>';
  else
      return '<p>'.preg_replace(
      array("/([\n]{2,})/i", "/([\r\n]{3,})/i","/([^>])\n([^<])/i"),
      array("</p>\n<p>", "</p>\n<p>", '$1<br'.($xml == true ? ' /' : '').'>$2'),

      trim($string)).'</p>';
  }

  // Recaptcha functions
  function recaptcha_site_key() {
    global $recaptcha_site_key;

    echo $recaptcha_site_key;
  }

	function _INPUT($name, $default)
	{
		if (isset($_REQUEST[$name])) {
			return strip_tags($_REQUEST[$name]);
		}else{
			return $default;
		}
	}
?>