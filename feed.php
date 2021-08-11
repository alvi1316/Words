<?php
  session_start();

  if(!isset($_SESSION["username"])){
      header("Location: index.php");
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
    <link rel="stylesheet" type="text/css" href="CSS/feed.css">    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <title>Feed</title>
  </head>
  <body>
    <nav class="navbar navbar-dark bg-dark">
      <span class="navbar-brand mb-0 h1">Potato Rotato</span>
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
        <a class="d-block text-nowrap text-white" href="#">Alvi</a>
        <a class="d-block text-nowrap text-white" href="#">Mahin</a>
        <a class="d-block text-nowrap text-white" href="#">Alin</a>
        <a class="d-block text-nowrap text-white" href="#">Adiba</a>
        <a class="d-block text-nowrap text-white" href="#">Srv</a>
        <a class="d-block text-nowrap text-white" href="#">Sanjida</a>
        <a class="d-block text-nowrap text-white" href="#">Mim</a>
      </div>      
      
    </div>  

    <div class="container-fluid">
        <div class="row" style="height: 100vh;">

          <div class="followDiv h-100 col-md-2 bg-secondary border border-dark d-none d-xs-block d-md-block">
              <h4 class="text-white mt-2">Following</h4>
              <input id="searchFollowing2" onkeyup="searchFollowingList2()" class="form-control mr-sm-2" type="search" placeholder="Search following list">
              <div id="followList2">                
                <a class="d-block text-nowrap text-white" href="#">Alvi</a>
                <a class="d-block text-nowrap text-white" href="#">Mahin</a>
                <a class="d-block text-nowrap text-white" href="#">Alin</a>
                <a class="d-block text-nowrap text-white" href="#">Adiba</a>
                <a class="d-block text-nowrap text-white" href="#">Srv</a>
                <a class="d-block text-nowrap text-white" href="#">Sanjida</a>
                <a class="d-block text-nowrap text-white" href="#">Mim</a>            
              </div>
          </div>
          
          <div class="col-md-8 pt-3 h-100 postDiv">

            <span class="d-block d-md-none" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>      
            
            <div class="px-md-5 py-md-3 p-1 mb-3 border border-dark">
              <div class="form-group">
                <label for="postTitle">Post here...</label>
                <input id="postTitle" class="form-control" type="search" placeholder="Title">
                <textarea id="postTextArea" class="form-control my-2" placeholder="Start writing here" rows="3"></textarea>
                <div class="d-flex flex-column flex-md-row justify-content-end"><button id="postBtn" class="btn btn-success">Post</button></div>
              </div>
            </div>
            
            <div class="px-md-5 py-md-3 p-1 mb-3 border border-dark">
              <div>
                <a class="text-dark" href="#"><h5 class="d-inline">Username</h5></a>
                <p class="float-right">Date and Time</p>
              </div>
              <H4>Title</H4>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
              <div class="p-1">
                <img src="images/post/upvote.png" alt="upvote" style="width: 15px; height: 15px;">
                <p class="d-inline">26</p>
                <img src="images/post/downvote.png" alt="upvote" style="width: 15px; height: 15px;">
                <p class="d-inline">9</p>
                <img src="images/post/comment.png" alt="upvote" style="width: 15px; height: 15px;">
                <p class="d-inline">3</p>
                <a class="float-right" href="#">Read Post</a>
              </div>                
            </div>

            <div class="px-md-5 py-md-3 p-1 mb-3 border border-dark">
              <div>
                <a class="text-dark" href="#"><h5 class="d-inline">Username</h5></a>
                <p class="float-right">Date and Time</p>
              </div>
              <H4>Title</H4>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
              <div class="p-1">
                <img src="images/post/upvote.png" alt="upvote" style="width: 15px; height: 15px;">
                <p class="d-inline">26</p>
                <img src="images/post/downvote.png" alt="upvote" style="width: 15px; height: 15px;">
                <p class="d-inline">9</p>
                <img src="images/post/comment.png" alt="upvote" style="width: 15px; height: 15px;">
                <p class="d-inline">3</p>
                <a class="float-right" href="#">Read Post</a>
              </div>                
            </div>

            <div class="px-md-5 py-md-3 p-1 mb-3 border border-dark">
              <div>
                <a class="text-dark" href="#"><h5 class="d-inline">Username</h5></a>
                <p class="float-right">Date and Time</p>
              </div>
              <H4>Title</H4>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
              <div class="p-1">
                <img src="images/post/upvote.png" alt="upvote" style="width: 15px; height: 15px;">
                <p class="d-inline">26</p>
                <img src="images/post/downvote.png" alt="upvote" style="width: 15px; height: 15px;">
                <p class="d-inline">9</p>
                <img src="images/post/comment.png" alt="upvote" style="width: 15px; height: 15px;">
                <p class="d-inline">3</p>
                <a class="float-right" href="#">Read Post</a>
              </div>                
            </div>

            <button class="btn btn-success btn-block">Load more</button>

          </div>

          <div class="col-md-2 bg-secondary border border-dark">
            <h4 class="text-white mt-2">Option</h4>
            <a class="d-block text-white" href="profile.php?profilename=<?php echo($_SESSION["username"]);?>">Profile</a>
            <a class="d-block text-white" href="settings.php">Settings</a>
            <a class="d-block text-white" href="logout.php">Logout</a>
          </div>

        </div>       
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="JS/feed.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
  </body>
</html>