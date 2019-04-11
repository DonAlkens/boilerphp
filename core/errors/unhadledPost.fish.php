<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Request Error</title>
    <style>
        body {
            background-color: #f8f8f8;
        }
        .center-box {
            margin: 120px auto;
            width: 60%;
            text-align: center;
            padding: 40px 20px;
            background-color: #fff;
        }

        .center-box p {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="center-box">
        <h2>Unhandled Post Request</h2>
        <p>This url cannot handle a post request. this may be due to unregistered route. <br>
            Please make sure all request routes are registered in the route.php file.
        </p>
        <a href="">learn more.</a>
    </div>
</body>
</html>