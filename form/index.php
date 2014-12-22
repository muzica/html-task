<?php 

session_start();
require_once 'validator.php';
$validator = new Validator();
$validator->set_error_delimiters('<div class="error">', '</div>');
$rules = array(
	array(
		'field' => 'user_name',
		'label' => 'You Name',
		'rules' => array(
                        'trim' => '', 
                        'strip_tags' => '', 
                        'required' => 'Field %s is require'
					)
	),
	array(
		'field' => 'user_email',
		'label' => 'Your e-mail',
		'rules' => array(
                        'trim' => '',
                        'required' => 'Field %s is require',
                        'valid_email' => 'Field %s is require valid email'
					)
	),
	array(
		'field' => 'user_url',
		'label' => 'URL your site',
		'rules' => array(
                        'trim' => '',
                        'valid_url' => 'Field %s must be correct'
					)
	),
    array(
		'field' => 'subject',
		'label' => 'Subject',
		'rules' => array(
                        'trim' => '',
                        'strip_tags' => '', 
                        'required' => 'Field %s is required to complete'
					)
	),
    array(
		'field' => 'text',
		'label' => 'Message',
		'rules' => array(
                        'trim' => '', 
                        'strip_tags' => '',
                        'required' => 'Field %s is require'
					)
	),
    array(
		'field' => 'keystring',
		'label' => 'Captcha',
		'rules' => array(
                        'trim' => '', 
                        'required' => 'Error captcha',
    					'valid_captcha[keystring]' => 'Please, verify symbol captcha'
					)
	)
);
$validator->set_rules($rules);
$message = '';

if($validator->run()){
	

	$to = 'ivan.muzica@gmail.com';
 
	$from = "=?UTF-8?b?" . base64_encode($validator->postdata('user_name')) . "?=";
	$subject = "=?UTF-8?b?" . base64_encode( $validator->postdata('subject') ) . "?=";
	
	$mail_body = "Hello, you receine new suggestion. \r\nAuthor has left information about:\r\n";
	
	foreach($rules as $rule){
		if($rule['field'] == 'keystring') continue;
		$mail_body .= $rule['label'].': '.$validator->postdata($rule['field'])."\r\n";
	}
	
	$header = "MIME-Version: 1.0\n";
	$header .= "Content-Type: text/plain; charset=UTF-8\n";
	$header .= "From: ". $from . " <" . $validator->postdata('user_email'). ">";

	if(mail($to, $subject, $mail_body, $header)){
		
		$message = '<div class="error">Your email has been send!</div>';
		

		$validator->reset_postdata();
	}
	else{
		
		$message = '<div class="error">Your email has not  send, Verify information!</div>';
	}
}
else{
	

	$message = $validator->get_string_errors();
	
	$errors = $validator->get_array_errors();

}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<style type="text/css">
<!--
.oneColFixCtrHdr #container {
	text-align: left; 
}
.oneColFixCtrHdr #header {
	background: #DDDDDD; 
	padding: 0 10px 0 20px;
}
.oneColFixCtrHdr #header h1 {
	margin: 0;
	padding: 10px 0;
}

.oneColFixCtrHdr #header p{padding-bottom: 10px;}
.oneColFixCtrHdr #mainContent {
	padding: 0 20px;
	position: relative;
}
}
.oneColFixCtrHdr #footer p {
	margin: 0;
	padding: 10px 0;
}

form.form div {
    padding:4px;
    margin: 4px 0;
    position:relative;
}

form.form input.text,
.textarea {
    padding:5px 10px;
    height:20px;
    border:1px solid #ddd;
    color:#333;
    background:url(images/bginput.jpg) repeat-x bottom #fff;
    position:relative;
    z-index:2;
}

form.form input.text {
    width:290px;
}

form.form .textarea {
    height:150px;
    width:290px;
}

form.form label {
    float:left;
    width:120px;
    text-align:right;
    margin-right:15px;
    font-weight:bold;
    font-size: 13px;
}

form.form .btn {
    display:block;
    height:31px;
    padding:0 10px;
    background:url(images/bgbtn.jpg) repeat-x;
    color:#565e62;
    font-weight:bold;
    font-size:12px;
    border:1px solid #e1e0df;
    outline:none;
    cursor: pointer;
}

/* CSS3 */
form.form .btn,
form.form .text,
form.form .textarea { 
-moz-border-radius:8px;
-webkit-border-radius:8px;
border-radius:8px;
}


div.error_field{
    background: rgba(255, 27, 0, 0.62);
    border: 1px black;
    width:500px;
}

div.errors .error{
    font-weight: bold;
    margin: 5px;
}


-->
</style>
</head>
<body class="oneColFixCtrHdr">

<div id="container">

  <div id="mainContent">

   <?=(!empty($message))? '<div class="errors">'.$message.'</div>': ''?>
   <form action="" method="post" class="form">
    <div <?=(!empty($errors['user_name']))? 'class="error_field"': '';?>>
    	<label>You Name:</label>
    	<input type="text" class = "text" name="user_name" value="<?=$validator->postdata('user_name');?>" />
    </div> 
    
    <div <?=(!empty($errors['user_email']))? 'class="error_field"': '';?>>
    	<label>Email:</label>
    	<input type="text" class = "text" name="user_email" value="<?=$validator->postdata('user_email');?>" />
    </div> 
    
    <div <?=(!empty($errors['user_url']))? 'class="error_field"': '';?>>
    	<label>URL:</label>
    	<input type="text" class = "text" name="user_url" value="<?=$validator->postdata('user_url');?>" />
    </div> 
    
    <div <?=(!empty($errors['subject']))? 'class="error_field"': '';?>>
    	<label>Subject:</label>
    	<input type="text" class = "text" name="subject" value="<?=$validator->postdata('subject');?>"/>
    </div> 
    
    <div class="area<?=(!empty($errors['text']))? ' error_field': '';?>">
    	<label>Message:</label>
    	<textarea cols="40" class = "textarea" rows="5" name="text"><?=$validator->postdata('text');?></textarea>
    </div> 
    
    <div <?=(!empty($errors['keystring']))? 'class="error_field"': '';?>>
    	<label class="captcha">Security code:</label>
    	<div class="capth_images"><?php require 'captcha.php';?></div>
    	<input type="text" class = "text" name="keystring" value=""/>
    </div> 

    <div>
    	<label>&nbsp;</label>
    	<input type="submit" class="btn" value="Send message" />
    </div>

	
 </form> 
 
</div>


</div>
<script type="text/javascript">
 google_ad_client = "ca-pub-4231692048579762";
google_ad_slot = "1331915711";
google_ad_width = 468;
google_ad_height = 15;

  </script>
<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-3866000-4']);
_gaq.push(['_trackPageview']);

(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
</script>
</body>
</html>