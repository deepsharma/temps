<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Registration successful</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
<div>
<img src="http://qustn.com/wp-content/uploads/2015/11/logo-52121.png">
<p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px">Hey , <?php echo $username;?></p> 
<p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px"> Your registration is successful,  
We have listed you details below
</p>
<p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px">  
Name:<strong><?php echo $username;?></strong><br>
Enterprise:<strong><?php echo $enterprisename;?></strong><br>
Email:<strong><?php echo $email_to;?></strong><br>
Registered Domain:<strong><?php echo $domain;?></strong><br>
</p>
<strong>Note:</strong> The Capabiliti for Support will be appear on <strong><?php echo $domain;?></strong>
<br>
<br>
Thanks<br />
Team <?php echo webMasterName;?>
</div>
</body>
</html>