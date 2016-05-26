<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Update student</title>
</head>
<body>
<form method="post" action="/SMS/index.php/Sms/Student/update">
<table>
<tr><td>student id</td><td><input type="text" value="<?php echo ($vo["id"]); ?>" maxlength=20 size=20 name="student_id"></td></tr>
<tr><td>name</td><td><input type="text" value="<?php echo ($vo["name"]); ?>" maxlength=20 size=20 name="name"></td></tr>
<tr><td>gender</td><td><input type="text" value="<?php echo ($vo["gender"]); ?>" maxlength=20 size=20 name="gender"></td></tr>
<tr><td>entrance age</td><td><input type="text" value="<?php echo ($vo["entrance_age"]); ?>" maxlength=20 size=20 name="entrance_age"></td></tr>
<tr><td>entrance year</td><td><input type="text" value="<?php echo ($vo["entrance_year"]); ?>" maxlength=20 size=20 name="entrance_year"></td></tr>
<tr><td>class</td><td><input type="text" value="<?php echo ($vo["class"]); ?>" maxlength=20 size=20 name="class"></td></tr>
<tr><td><input type="submit" value="ok"></td></tr>
</table>
</form>
</body>
</html>