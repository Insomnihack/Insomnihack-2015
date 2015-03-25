<?php
  require('config.php');
  require('hackers.php');

  if (is_ajax()) {
    if (isset($_GET['hacker']) && isset($_GET['rel'])) {
      $rel=mysql_real_escape_string($_GET['rel']);
      $hacker=mysql_real_escape_string($_GET['hacker']);
      if ($rel==='inc_like'){
        mysql_query("UPDATE hackers SET l=l+1 WHERE handle='$hacker'");
        $r=mysql_query("SELECT l from hackers where handle='$hacker'");
        $value=mysql_fetch_array($r);
        echo json_encode($value);
      }
      if ($rel==='inc_dlike'){
        mysql_query("UPDATE hackers SET d=d+1 WHERE handle='$hacker'");
        $r=mysql_query("SELECT d from hackers where handle='$hacker'");
        $value=mysql_fetch_array($r);
        echo json_encode($value);
      }
      if ($rel==='follow'){
        $r=mysql_query("SELECT * from hackers where handle='$hacker'");       
        $row=mysql_fetch_assoc($r);
        $value=new hackers($row);
        setcookie("H4ck3rs",base64_encode(serialize($value)));
        setcookie("Following",$hacker);
      }
      if ($rel==='unfollow'){
        setcookie("H4ck3rs","",1);
        setcookie("Following","",1);
      }
      if ($rel==='refresh'){
        $r=mysql_query("SELECT l,d from hackers where handle='$hacker'");
        $value=mysql_fetch_array($r);
        echo json_encode($value);
      }
    }
  }

function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}
mysql_close($conn);
?>
