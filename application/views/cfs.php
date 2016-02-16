<!--<html>
<body>
<form name="multiimage" action="<?php echo base_url(); ?>enterprise/artifacts" method="post" enctype="multipart/form-data">
<input type ="file" name="image[]" multiple>
<input type="hidden" name="action" value="add" />
<input type="submit" value="submit" >
</form>
-->

<html>
<body>
<h1> CFS Login</h1>
<form name="multiimage" action="<?php echo base_url(); ?>users/login" method="post">
<input type="text" name="email" value="admin" /><br>
<input type="password" name="password" value="admin" />
<input type="submit" value="submit" >
<?php echo $this->session->flashdata('flashmessage');?>
</form>
<html>