<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Packages</title>

    <!-- Bootstrap -->
    <link rel='stylesheet' href='/components/bootstrap/dist/css/bootstrap.min.css' />
    <link rel="stylesheet" href="/css/style.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  </head>
  <body>
  <nav class="navbar navbar-default " role="navigation">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="{{URL::to('/')}}">Home</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
              
              <ul class="nav navbar-nav navbar-right">
                <li><a href="/auth/login">Login</a></li>
                <li><a href="/auth/register">Register</a></li>
              </ul>
            </div><!--/.nav-collapse -->
          </div>
        </nav>
   
    <div class="auth_form">
    	<div class="container-fluid ">
			@yield('content')
		</div>
    </div>
  <script src="/js/require.js" data-main="/js/app"></script>
  </body>
</html>