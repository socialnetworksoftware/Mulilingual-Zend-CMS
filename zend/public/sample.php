<?php
$mail = new Zend_Mail();
$mail->setBodyText('My first email!')
->setBodyHtml('My <b>first</b> email!')
->setFrom('rob@akrabat.com', 'Rob Allen')
->addTo('somebody@example.com', 'Some Recipient')
->setSubject('Hello from Zend Framework in Action!')
->send();
--------------------
$mail->setBodyText($this->view->render('support/text-email.phtml'));
$mail->setBodyHtml($this->view->render('support/html-email.phtml'));
?>
text-email.phtml
------------------------------

Hello <?php echo $user->name; ?>,
Your support ticket number is <?php echo $supportTicket->id; ?>
Your message was:

<?php echo $supportTicket->body; ?>

We will attend to this issue as soon as possible and if we have any further questions will
contact you. You will be sent a notification email when this issue is updated.

Thanks for helping us improve Places,
The Places Support team.

html-email.phtml
------------------------------
<p>Hello <?php echo $user->name; ?>,</p>
<p>Your support ticket number is <b><?php echo $supportTicket->id; ?></b></p>
<p>Your message was:</p>
<?php echo $supportTicket->body_formatted; ?>
<p>We will attend to this issue as soon as possible and if we have any further questions will
contact you. You will be sent a notification email when this issue is updated.</p>
<p>Thanks for helping us improve Places,</p>
<p><b>The Places Support team.</b></p>