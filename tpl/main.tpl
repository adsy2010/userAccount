<!DOCTYPE html>
<html>

    <head>
        <title>00freebuild.info</title>
        <link rel="stylesheet" href="./tpl/css.css"/>
        <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css"/>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">00freebuild.info</a>
                </div>

                <div id="navbar" class="navbar-collapse collapse ">
                    <div id="userbar" class="navbar-collapse collapse">
                        <div class="loginForm {SHOW_LOGON}">
                            <div class="navbar-form navbar-right">
                                <div class="form-group">
                                    <input type="text" placeholder="Email" id="email" name="email" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="password" placeholder="Password" id="password" name="password" class="form-control">
                                </div>
                                <button type="button" id="signin" class="btn btn-success">Sign in</button>
                            </div>
                        </div>
                        <div id="userGreeting" class="navbar-form navbar-right text-muted">
                            {USER}
                        </div>

                    </div>
                </div><!--/.navbar-collapse -->
            </div>
        </nav>
        <div class="container">
            <div id="status">

            </div>
            <div class="row">
                {ALL}
            </div>
        </div>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="tpl/login.js"></script>
    </body>
</html>