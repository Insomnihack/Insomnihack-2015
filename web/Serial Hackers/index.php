<?php
require('config.php');
require('hackers.php');

$sql = "SELECT * FROM hackers ORDER BY l DESC,d DESC" ;
$result = mysql_query($sql);
$rows = array();
while ($row = mysql_fetch_assoc($result)){
  $rows[$row['handle']] = new hackers($row);
}
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      html, body{
        height:100%;
      }
      .navbar{
        margin-bottom: 0px;
      }
      .navbar-right{
        margin-right: 15px;
      }
      .jumbotron {
        color: #FFF;
        margin-bottom:0;
        background:rgba(0,0,0,0);
      }
      .glyphicon-large{
        font-size:35px;
      }
      .glyphicon-small{
        font-size:20px;
      }
      .glyphicon-star{
        color:#FF0;
      }
      .glyphicon-star-empty{
        color:#FF0;
      }
      .table{
        color:#FFF;
        margin-top:40px;
      }
      #btn-dlike{
        cursor: hand; 
        color:#FFF;
      }
      #btn-like{
        cursor: hand; 
        color:#FFF;
      }
      #btn-fav{
        cursor: hand; 
        color:#FF0;
      }
      .thumbnail{
        border:none;
        background-color: transparent;
        text-decoration: none;
        display: inline;
      }
      .img-thumbnail{
        height: 80px;
        width: 80px;
      }
    </style>
  </head>
  <body>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <nav class="navbar navbar-inverse">
      <div class="navbar-header">
        <a class="navbar-brand navbar-left" href="/"><?php echo isset($_GET['hacker']) ? "Go back": "Serial Hackers DB"; ?></a>
      </div>
      <?php
        if(isset($_COOKIE['Following'])){
          $c=unserialize(base64_decode($_COOKIE['H4ck3rs']));
      ?>
      <div class="navbar-right alert alert-success">
          <?php
            echo '<a href="/?hacker=' . $c->get_handle() . '" class="thumbnail">';
            echo '<img src="' . $c->get_photo() . '" class="avatar img-circle img-thumbnail pull-left" style="height:50px;width:50px;">';
            echo '</a>';
            echo '<div class="pull-right">';
            echo '<h5>Following: ' . $c->get_handle() . '</h5>';
            echo '<span id="btn-follow-dlike" class="glyphicon glyphicon-small glyphicon-thumbs-down pull-right" aria-hidden="true"><small>' . $c->get_dlike() . '</small></span>';
            echo '<span id="btn-follow-like" class="glyphicon glyphicon-small glyphicon-thumbs-up pull-left" aria-hidden="true"><small>' . $c->get_like() . '</small></span>';
            echo '</div>';
          ?>
      </div>
      <?php
           }
      ?>
    </nav>
    <div class="jumbotron">
     <?php
      if(isset($_GET['hacker'])){
    ?>
      <div class="container">
        <h2 class="page-header" id="handle"><?php echo (is_null($rows[$_GET['hacker']]) ? exit('No such user') : $rows[$_GET['hacker']]->get_handle()); ?></h2>
        <div class="row">
          <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="text-center">
              <img src="<?php echo $rows[$_GET['hacker']]->get_photo(); ?>" class="avatar img-circle img-thumbnail">
              <br>
              <br>
              <h5>Rate your hacker</h5>
              <br>
              <span id="btn-like" class="glyphicon glyphicon-small glyphicon-thumbs-up" ><br><?php echo $rows[$_GET['hacker']]->get_like(); ?></span>
              <span style="padding:10px">|</span>
              <span id="btn-dlike" class="glyphicon glyphicon-small glyphicon-thumbs-down" ><br><?php echo $rows[$_GET['hacker']]->get_dlike(); ?></span>
              <br>
              <br>
              <h5>Follow your favorite hacker</h5>
              <br>
              <?php 
              if(isset($_COOKIE['Following'])){
                if($_COOKIE['Following']===$_GET['hacker']){
                  echo '<span id="btn-fav" class="glyphicon glyphicon-large glyphicon-star" aria-hidden="true"></span>';
                }
                else{
                  echo '<span id="btn-fav" class="glyphicon glyphicon-large glyphicon-star-empty" aria-hidden="true"></span>';
                }
              } else{
                  echo '<span id="btn-fav" class="glyphicon glyphicon-large glyphicon-star-empty" aria-hidden="true"></span>';
                }
              ?>
            </div>
          </div>
          <div class="col-md-8 col-sm-6 col-xs-12 personal-info">
          <?php if(!isset($_COOKIE['H4ck3rs'])){ ?>
            <div class="alert alert-info alert-dismissable">
              <a class="panel-close close" data-dismiss="alert">x</a> 
              <i class="fa fa-coffee"></i>
              Don't forget to rate and / or follow your favorite hacker !
            </div>
            <?php } ?>
            <h3>Personal information</h3>
            <form class="form-horizontal" role="form">
              <div class="form-group">
                <label class="col-lg-3 control-label">Age:</label>
                <div class="col-lg-8 control-label " style="text-align:left">
                  <?php echo $rows[$_GET['hacker']]->get_age(); ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Quote:</label>
                <div class="col-lg-8 control-label " style="text-align:left">
                  <?php echo $rows[$_GET['hacker']]->get_quote(); ?>
                </div>
              </div>
              <br>
              <h3> Skillz </h3>
              <label class="col-lg-3 control-label">Web:</label>
              <div class="progress">
                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo $rows[$_GET['hacker']]->get_web();?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $rows[$_GET['hacker']]->get_web();?>%">
                </div>
              </div>
              <label class="col-lg-3 control-label">Reverse:</label>
              <div class="progress">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $rows[$_GET['hacker']]->get_rev();?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $rows[$_GET['hacker']]->get_rev();?>%">
                </div>
              </div>
              <label class="col-lg-3 control-label">Guessing:</label>
              <div class="progress">
                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $rows[$_GET['hacker']]->get_guess();?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $rows[$_GET['hacker']]->get_guess();?>%">
                </div>
              </div>
              <label class="col-lg-3 control-label">Beer Drinking:</label>
              <div class="progress">
                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $rows[$_GET['hacker']]->get_beer();?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $rows[$_GET['hacker']]->get_beer();?>%">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php 
      } else { 
      ?>
      <div class="container" >
        <h1 class="page-header">Serial Hackers DB</h1>
        <h4>Rate your favorite hackers here !<br><br>As this list will grow fast, we added a new feature to follow your favorite hacker. </h4>
        <br>
        <br>
        <br>
        <div class="row">
        <?php
          foreach($rows as $r){
            echo '<div class="col-xs-6 col-md-3" style="text-align:center">';
            echo '<a href="/?hacker='.$r->get_handle().'" class="thumbnail">';
            echo '<img src="' . $r->get_photo() . '" class="avatar img-circle img-thumbnail" alt="avatar">';
            echo '</a>';
            echo '<span class="glyphicon glyphicon-small glyphicon-thumbs-up pull-left">'.$r->get_like().'</span>';
            if(isset($_COOKIE['Following'])){
              if($_COOKIE['Following']===$r->get_handle()){
                echo '<span class="glyphicon glyphicon-large glyphicon-star" aria-hidden="true"></span>';
              } else{
                echo '<span class="glyphicon glyphicon-large glyphicon-star-empty" aria-hidden="true"></span>';
              }
            } else{
              echo '<span class="glyphicon glyphicon-large glyphicon-star-empty" aria-hidden="true"></span>';
            }
            echo '<span class="glyphicon glyphicon-small glyphicon-thumbs-down pull-right">'.$r->get_dlike().'</span>';
            echo '</br></br>';
            echo '</div>';
          }
	echo '</div>';
        } 
        ?>
       </div>
     </div>
    <script>
      $(function() {
        $.ajaxSetup({ cache: false });
        setInterval(function(){
          $.ajax({
            type: "GET",
            url:  "update.php",
            dataType: "json",
            data: { hacker: $.cookie("Following"), rel: "refresh" }, 
            success: function(data){
                     $("span#btn-follow-like").html("<small>"+data['l']+"</small>");
                     $("span#btn-follow-dlike").html("<small>"+data['d']+"</small>");
                     }
          });
        },5000);
        $("span#btn-like").click(function(){
          $.ajax({
    	    type: "GET",
            url:  "update.php",
            dataType: "json",
            data: { hacker: $("h2#handle").text(), rel: "inc_like" }, 
            success: function(data){
                     $("span#btn-like").html("<br>" + data['l']);
                     }
          });
        });
        $("span#btn-dlike").click(function(){
          $.ajax({
            type: "GET",
            url:  "update.php",
            data: { hacker: $("h2#handle").text(), rel: "inc_dlike" }, 
            dataType: "json",
            success: function(data){
                     $("span#btn-dlike").html("<br>" + data['d']);
                     }
          });
        });
        $("span#btn-fav").click(function(){
          if ($("span#btn-fav").hasClass("glyphicon-star-empty")){
            $.ajax({
              type: "GET",
              url:  "update.php",
              data: { hacker: $("h2#handle").text(), rel: "follow" }, 
              success: function(data){
                         $("span#btn-fav").removeClass("glyphicon-star-empty").addClass("glyphicon-star");
                         location.reload();
                       }
          
          });
          } else { 
            $.ajax({
              type: "GET",
              url:  "update.php",
              data: { hacker: $("h2#handle").text(), rel: "unfollow" }, 
              success: function(data){
                         $("span#btn-fav").removeClass("glyphicon-star").addClass("glyphicon-star-empty");
                         location.reload();
                       }
          });
          }
        });
      });
    </script>
  </body>
</html>
<?php
mysql_close($conn);
?> 
