<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <title>{{ Config('app.name') }}  Coming soon</title>
        <meta name="author" content="AchuWorld">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="{{ isset($page_meta_description) ? $page_meta_description : '' }}">
        <meta name="keywords" content="{{ isset($settings->meta_tag_keywords) ? $settings->meta_tag_keywords : '' }}" />
        <meta name="generator" content="Social Likes: http://social-likes.js.org/">

          <!-- Favicone Icon -->
        <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico">
        <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
        <link rel="icon" type="image/png" href="/img/favicon-96x96.png">
        <link rel="apple-touch-icon" href="/img/favicon-96x96.png">

        <!-- CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@900&display=swap" rel="stylesheet"> 
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Roboto:wght@900&display=swap" rel="stylesheet"> 

</head>
<style>

  /* Set height to 100% for body and html to enable the background image to cover the whole page: */
body, html {
  height: 100%
}

body {
  font-size: 14px;
  line-height: 1.7;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #e13b3f;
  font-weight: 600;
  font-family: 'Open Sans', sans-serif;
}



::selection {
  background-color: #222;
  color: #fff;
}


a:visited {
  background-color:none;
}


h1,
h2,
h3,
h4,
h5,
h6,
.h1,
.h2,
.h3,
.h4,
.h5,
.h6 {
    font-variant-ligatures: common-ligatures;
    margin-top: 0;
    font-weight: 700;
    font-family: 'Roboto', sans-serif;
    color: #e13b3f;
}

.bgimg {
  /* Background image */
  background-image: url('https://hautesignatures.com/uploads/8SpTjZM2wUq2OdkZDfXoxkXgAkU3VD31oKoL2tSP.jpeg');
  /* Full-screen */
  height: 100%;
  /* Center the background image */
  background-position: center;
  /* Scale and zoom in the image */
  background-size: cover;
  /* Add position: relative to enable absolutely positioned elements inside the image (place text) */
  position: relative;
  /* Add a white text color to all elements inside the .bgimg container */
  color: white;
  /* Add a font */
  font-family: "Courier New", Courier, monospace;
  /* Set the font-size to 25 pixels */
  font-size: 25px;
}

/* Position text in the top-left corner */
.topleft {
  position: absolute;
  top: 0;
  left: 16px;
}

/* Position text in the bottom-left corner */
.bottomleft {
  position: absolute;
  bottom: 0;
  left: 16px;
}

/* Position text in the middle */
.middle {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  color: #e13b3f;
}

/* Style the <hr> element */
hr {
  margin: auto;
  width: 40%;
} 

</style>


<body>


<div class="bgimg">
  <div class="topleft">
    <p></p>
  </div>
  <div class="middle">
    <h1> COMING SOON</h1>
    <p>Our website is almost here !!!</p>
    <p>Sign up below for first dibs on new arrivals ,vip surprises and more</p>

    <hr>
    <h1 class="target"></h1>
    <p>
     
      <form id="signup-form"  class="form-group" action="#" >

       <div class="form-row align-items-center">
          <div class="col-sm-3 my-1">
            <label class="sr-only" for="inlineFormInputName">First Name</label>
            <input type="text" class="form-control" id="inlineFormInputName" name="fname" placeholder="First Name">
          </div>
          <div class="col-sm-3 my-1">
            <label class="sr-only" for="inlineFormInputName">Last Name</label>
            <input type="text" class="form-control" id="inlineFormInputName"  name="lname" placeholder="Last Name">
          </div>
          <div class="col-sm-3 my-1">
            <label class="sr-only" for="inlineFormInputName">Email</label>
            <input type="email" class="form-control" id="inlineFormInputName"  name="email" placeholder="Email">
          </div>
        
          <div class="col-auto my-1">
            <button type="submit" style="background-color: #000000; color: #ffffff; outline: none; border: none;" class="btn btn-primary">Sign Up</button>
          </div>
        </div>
      </form>
    </p>
  </div>
  <div class="bottomleft">
    <p></p>
  </div>
</div> 
  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" ></script>

<script>

$().ready(function(){
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});


$("#signup-form").submit(function(e){
  e.preventDefault()
  
  $.ajax({
      url: "/newsletter/signup",
      type: 'POST',
      data:$(this).serialize(),
  }).done(function(res) {
      $('#signup-form').remove()
      $(".btn-primary").text("Loading......")
      $("h1.target").html(res.message)
  }).fail(function(){
    $("h1.target").html("We could not sign you up at the moment .Please try again")
  });
})


</script>
</body>
</html>