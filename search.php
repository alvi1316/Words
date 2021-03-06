<?php
  session_cache_limiter('private_no_expire');
  session_start();
  $error = false;
  $row = null;

  if(!isset($_SESSION["username"])){
    header("Location: index.php");
  }

  if(isset($_POST["keyword"])){
    require_once('dbmanager.php');
    require_once('connectionsingleton.php');

    $dbmanager = new dbmanager();        
    $con = connectionSingleton::getConnection();
    $followList = $dbmanager->getFollowingList($con, $_SESSION["u_id"]);
    $row = $dbmanager->searchUser($con, $_POST["keyword"]);

    if(count($row)==0){
      $error = true;
    }

  }else{
    $error = true;
  }
  
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="CSS/search.css">
    
    <link rel="icon" href="images/other/logo3.png">
    <title>Word's | Search</title>
  </head>
  <body>
    <nav class="navbar navbar-dark bg-dark">
      <img src="images/other/logo2.gif" width="80" height="45" alt="Word's">
      <form action="search.php" method="post" class="form-inline my-2 my-lg-0">
        <input name="keyword" class="form-control mr-sm-2" type="search" placeholder="Search">
        <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Search</button>
      </form>
    </nav>

    <div id="mySidenav" class="sidenav bg-secondary">
      <h4 class="text-white mt-2">Following</h4>
      <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>        
      <input id="searchFollowing1" onkeyup="searchFollowingList1()" class="form-control mr-sm-2" type="search" placeholder="Search following list">  
      <div id="followList1">
        <?php
          foreach($followList as $follow){
            echo("<a class='d-block text-nowrap text-white' href='profile.php?profilename=".$follow["username"]."'>".$follow["username"]."</a>");
          }
        ?>  
      </div>
      
      
    </div>  

    <div class="container-fluid">
        <div class="row" style="height: 100vh;">

            <div class="followDiv h-100 col-md-2 bg-secondary border border-dark d-none d-xs-block d-md-block">
                <h4 class="text-white mt-2">Following</h4>
                <input id="searchFollowing2" onkeyup="searchFollowingList2()" class="form-control mr-sm-2" type="search" placeholder="Search following list">
                <div id="followList2">                
                  <?php
                    foreach($followList as $follow){
                      echo("<a class='d-block text-nowrap text-white' href='profile.php?profilename=".$follow["username"]."'>".$follow["username"]."</a>");
                    }
                  ?>              
                </div>
            </div>

            <div class="col-md-8 h-100 postDiv">

              <span class="d-block d-md-none" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
              
              <?php
                if($error){
                  echo("
                        <div class='row p-2 m-md-3 m-1 justify-content-center'>
                          <h3 class='d-inline text-danger'>No results found!</h3>
                        </div>"
                      );
                }else{
                  foreach ($row as $array) {                    
                    echo("
                      <div class='row p-2 m-md-3 m-1 border border-dark'>
                        <div class='col-md-6'>
                          <h5 class='d-inline'><a href='profile.php?profilename=".$array["username"]."' class='text-dark'>".$array["username"]."</a></h5>
                        </div>
                        <div class='col-md-3'>
                          <p class='d-inline'>Writer Badge:</p>
                          <img src='images/rank/".$array["reader_badge"].".png' alt='".$array["reader_badge"]."' style='width: 40px; height: 40px;'>
                        </div>  
                        <div class='col-md-3'>
                          <p class='d-inline'>Reader Badge:</p>
                          <img src='images/rank/".$array["writter_badge"].".png' alt='".$array["writter_badge"]."' style='width: 40px; height: 40px;'>
                        </div>
                      </div>  
                    ");
                  }
                }
              ?>             

            </div>

            <div class="col-md-2 bg-secondary border border-dark">
              <h4 class="text-white mt-2">Option</h4>
              <a class="d-block text-white" href="feed.php">Home</a>
              <a class="d-block text-white" href="settings.php">Settings</a>
              <a class="d-block text-white" href="logout.php">Logout</a>
            </div>

        </div>       
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="JS/search.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
  </body>
</html>