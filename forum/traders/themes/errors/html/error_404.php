<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found | Simple Forum</title>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ruda:400,700,900">
    <style type="text/css">
    body { margin: 0; padding: 10px; background-color: #ed5565; font-family: "Ruda",sans-serif; color: #FFF; }
    .middle-box { margin: 0 auto; max-width: 400px; padding-top: 30px; z-index: 100; text-align: center; }
    .middle-box h1 { font-size: 170px; color:white; margin:0; }
    .btn { -webkit-border-radius: 28; -moz-border-radius: 28; border-radius: 28px; color: #ffffff; font-size: 20px; background: #3498db; padding: 10px 20px 10px 20px; text-decoration: none; }
    .btn:hover { background: #3cb0fd; text-decoration: none; }
    </style>
</head>

<body class="bg-theme04">
    <div class="middle-box animated fadeInDown">
        <h1>404</h1>
        <h3 style="font-weight:bold;">Requested page has not been found!</h3>
        <div style="color:#F5F5F5; margin-bottom:20px;">
            Sorry, but the page you are looking for might have been removed, or unavailable.
        </div>
        <a href="<?=config_item('base_url');?>" class="btn">Go to Home</a>
    </div>
</body>

</html>
