<?php

require_once('_check_ip.php');

function logout()
{
    $_SESSION = array('authenticated' => false);
}

function stripslashes_deep($value)
{
    if (is_array($value)) {
        return array_map('stripslashes_deep', $value);
    } else {
        return stripslashes($value);
    }
}

if (get_magic_quotes_gpc()) {
    $_POST = stripslashes_deep($_POST);
}

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$nounce   = isset($_POST['nounce'])   ? $_POST['nounce']   : '';

$ini = parse_ini_file('config.php', true);

session_start();

if (isset($_POST['logout']) || isset($_GET['logout'])) {
    logout();
}

if (isset($_SESSION['nounce']) && $nounce == $_SESSION['nounce'] 
    && isset($ini['users'][$username])
) {
    if (strchr($ini['users'][$username], ':') === false) {
        $_SESSION['authenticated'] = ($ini['users'][$username] == $password);
    } else {
        list($fkt, $salt, $hash) = explode(':', $ini['users'][$username]);
        $_SESSION['authenticated'] = ($fkt($salt . $password) == $hash);
    }
}

if (!isset($_SESSION['authenticated'])) {
    $_SESSION['authenticated'] = false;
}

if (!$_SESSION['authenticated']) {
    $_SESSION['nounce'] = mt_rand();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Amasty Support Tools</title>
    </head>
<body>
<form action="<?php print($_SERVER['PHP_SELF']) ?>" method="post">
<fieldset>
    <legend>Authentication</legend>
    <?php
    if (!empty($username)) {
        echo "  <p>Login failed, please try again:</p>\n";
    } else {
        echo "  <p>Please login:</p>\n";
    }
    ?>

  <label for="username">Username:</label>
  <input name="username" id="username" type="text" autofocus value="<?php echo $username ?>"><br>
  <label for="password">Password:</label>
  <input name="password" id="password" type="password">
  <p><input type="submit" value="Login"></p>
  <input name="nounce" type="hidden" value="<?php echo $_SESSION['nounce']; ?>">

</fieldset>
</form>

<?php
die();
}
?>
