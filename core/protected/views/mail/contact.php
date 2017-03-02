<html>
Hello,<br/>
<br/>
There is a new mail from <?=$model->name;?> (<?=$model->email;?>)<br/>
Subject: <?=$model->subject;?><br/>
Message:<br/><br/>
<?=str_replace("\n","<br/>",$model->body);?>

</html>
