<?php
session_start();

class Home
{
    function get()
    {
        if (!isset($_SESSION['access_token'])) {
            include 'template/login.php';
        } else {
            header('Location: /main');die;
        }
    }
}

class Logout
{
    function get()
    {
        $_SESSION = array();
        session_destroy();
        header('Location: /');die;
    }
}

class Login
{
  function get()
  {
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $request_token = $connection->getRequestToken(OAUTH_CALLBACK);
    $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
    switch ($connection->http_code) {
      case 200:
      /* Build authorize URL and redirect user to Twitter. */
        $url = $connection->getAuthorizeURL($token);
        header('Location: ' . $url);die;
        break;
      default:
        /* Show notification if something went wrong. */
        echo 'Could not connect to Twitter. Refresh the page or try again later.';
    }
  }
}

class CallBack
{
    function __call($method, $args)
    {
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
        $_SESSION['access_token'] = $access_token;
        unset($_SESSION['oauth_token']);
        unset($_SESSION['oauth_token_secret']);
        if (200 == $connection->http_code) {
            $_SESSION['status'] = 'verified';
            header('Location: /');die;
        } else {
            header('Location: /logout');die;
        }
    }
}

class Post
{
    function post()
    {
        if ( empty($_SESSION['access_token'])
          || empty($_SESSION['access_token']['oauth_token'])
          || empty($_SESSION['access_token']['oauth_token_secret'])
           ) {
            header('Location: /logout');die;
        }
        $access_token = $_SESSION['access_token'];

        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        print json_encode($connection->post('statuses/update', array('status' => $_POST['msg'])));
    }
}

class Update
{
    function post()
    {
        if ( empty($_SESSION['access_token'])
          || empty($_SESSION['access_token']['oauth_token'])
          || empty($_SESSION['access_token']['oauth_token_secret'])
           ) {
            header('Location: /logout');die;
        }
        $access_token = $_SESSION['access_token'];
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $since_id = preg_replace('@\D@', '', $_POST['since']);
        if (!(int) $since_id) {
            unset($since_id);
        }
        $timeline = $connection->get( $_POST['use'], compact('since_id') );
        if ($timeline) {
            foreach($timeline as $item) {
                if ($_POST['use'] == 'direct_messages') {
                    $item->user = $item->sender;
                }
                if ($_POST['use'] == 'direct_messages/sent') {
                    $item->user = $item->recipient;
                }
               include 'template/post.php';
            }
        }
    }
}

class Main
{
    function get()
    {
        if ( empty($_SESSION['access_token'])
          || empty($_SESSION['access_token']['oauth_token'])
          || empty($_SESSION['access_token']['oauth_token_secret'])
           ) {
            header('Location: /logout');die;
        }
        $access_token = $_SESSION['access_token'];

        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $profile = $connection->get('account/verify_credentials');
        if (isset($profile->error) && $profile->error) {
            die ($profile->error.' Go to <a href="http://twitter.com" target="_blank">twitter</a> page.');
        }
        $timeline = $connection->get('statuses/home_timeline');
        $lists = $connection->get($profile->screen_name . '/lists');
        usort($lists->lists, 'sort_lists');
        include 'template/main.php';
    }
}

function sort_lists($a, $b)
{
    if ( $a->mode != $b->mode ) {
        return $a->mode == 'private' ? -1 : 1;
    } else {
        return strcmp(strtolower($a->name), strtolower($b->name));
    }
    return 0;
}

function trata_tweet($msg) {
    $msg_final = $msg;

    // Topics
    preg_match_all('/#[\wà-úÀ-Ú]+/', $msg, $matches);
    if ($matches) { foreach ($matches[0] as $item) {
        $m   = substr($item, 1);
        $msg_final = str_replace($item, '<a href="http://twitter.com/#search?q=%23'.$m.'" target="_blank">'.$item.'</a>', $msg_final);
    } }

    // Users
    preg_match_all('/[^a-zA-Z0-9_\.-]@\w+/', ":$msg", $matches);
    if ($matches) { foreach ($matches[0] as $item) {
        $m = explode('@', $item);
        $m = '@'.trim($m[count($m)-1]);
        $msg_final = str_replace(trim($m), ' <a href="http://twitter.com/'.$m.'" target="_blank">'.$m.'</a>', $msg_final);
    } }

    // URLs
    preg_match_all('/http:\/\/[^ ]+/', $msg, $matches);
    if ($matches) { foreach ($matches[0] as $item) {
        $msg_final = str_replace($item, '<a href="'.$item.'" target="_blank">'.$item.'</a>', $msg_final);
    } }

    // URLs
    preg_match_all('/ www\.[^ ]+/', $msg, $matches);
    if ($matches) { foreach ($matches[0] as $item) {
        $msg_final = str_replace($item, ' <a href="http://'.trim($item).'" target="_blank">'.$item.'</a>', $msg_final);
    } }


    return $msg_final;
}

function niceTime($time) {
  $delta = time() - $time;
  if ($delta < 60) {
    return 'menos de um minuto atrás.';
  } else if ($delta < 120) {
    return 'apróx. um minuto atrás.';
  } else if ($delta < (45 * 60)) {
    return floor($delta / 60) . ' minutes ago.';
  } else if ($delta < (90 * 60)) {
    return 'apróx. uma hora atrás.';
  } else if ($delta < (24 * 60 * 60)) {
    return 'apróx ' . floor($delta / 3600) . ' horas atrás.';
  } else if ($delta < (48 * 60 * 60)) {
    return 'ontem.';
  } else {
    return floor($delta / 86400) . ' dias atrás.';
  }
}