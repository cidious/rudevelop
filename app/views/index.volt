<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8"/>
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=0.6, minimum-scale=0.5, maximum-scale=1.5">
 <meta content="Rudevelop test task" name="description"/>
 <meta content="Rudevelop,vacancy,test" name="keywords"/>
 <meta content="Dmitry Cidious <cidious@gmail.com>" name="author"/>
 <title>{{ TITLE }}</title>
 <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
 <link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
</head>
<body>
 <nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
   <div class="navbar-header" id="navbar-narrow">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
            data-target="#navbar" aria-expanded="false" aria-controls="navbar">
     <span class="sr-only">Toggle navigation</span>
     <span class="icon-bar"></span>
     <span class="icon-bar"></span>
     <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Rudevelop</a>
    <ul class="nav navbar-nav pull-right" id="navbar-narrow-ul" style="margin-right:20px">
    </ul>
   </div>
   <div id="navbar" class="navbar-collapse collapse">
    <ul class="nav navbar-nav">
     <li>{{ link_to('/', '#') }}</li>
     <li>{{ link_to('index/register', 'Регистрация') }}</li>
     <li>{{ link_to('index/profile', 'Профиль') }}</li>
    </ul>
   </div>
  </div>
 </nav>
 <div class="container-fluid">
  {{ content() }}
 </div>
 <hr/>
 <footer>
  <div class="container">
   <p>Rudevelop test task by <a href="mailto:cidious@gmail.com">Dmitry Cidious</a>, 2017</p>
  </div>
 </footer>
</body>
<script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
{{ javascript_include("js/index.js") }}
</html>
