<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $settings['page_title']; ?></title>        
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
        <?php echo $this->Html->css('um'); ?>        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>                    
                    <a class="navbar-brand" href="#"><?= $settings['org_title']; ?></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="<?= $baseURL?>">Home</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="<?= $baseURL?>/users/logout">Logout</a></li></ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>

        <div class="container">
<?php echo $this->fetch('content'); ?>
        </div>
        <?php echo $this->Html->script('//code.jquery.com/jquery-1.11.0.min.js'); ?>
        <?php echo $this->Html->script('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'); ?>
        <?php echo $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/knockout/3.1.0/knockout-min.js'); ?>
        <?php echo $this->Html->script('moment.min.js'); ?>
        
        <?php echo $this->fetch('script'); ?>
        
    </body>
</html>