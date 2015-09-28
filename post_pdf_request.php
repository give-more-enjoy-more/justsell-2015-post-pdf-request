<?php
/**
 * @package Post_PDF_Request
 * @version 0.4
 */

/*
Plugin Name: Post PDF Request
Description: Uses the shortcode [postpdfrequest]
Author: Joe Gilbert
Version: 0.4
*/

/*
 * Simply returns the pdf request input form when called.
 */
function display_post_pdf_resquest_form()
{
	/* Initialize variables */
	$post_title_test = get_the_title();

	/* Build the form and set it to the form_string variable. */
	$form_output_string = '
		<h3>Please enter your email below to get the '. $post_title_test .' printable PDF</h3>
		<form action="'. $_SERVER['REQUEST_URI'] .'" method="post" name="pdfFormReqest" id="pdf-form-request">
			<input name="postPdfRequestEmail" type="text" placeholder="Enter your email here">
			<input name="postPdfRequestSubmit" type="submit" value="Submit">
		</form>';

	return $form_output_string;
} /* END function display_post_pdf_resquest_form */


/*
 * Either calls the display form function, or the process form function.
 */
function post_pdf_resquest_control()
{
  if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || !isset ($_POST['postPdfRequestSubmit']) )
  {
		return display_post_pdf_resquest_form();
  }
  else
  {
		return process_post_pdf_resquest_form();
  }
} /* END function post_pdf_resquest_control */


/*
 * Processes the form after user submission. It will ultimately either display any errors, or control emailing the pdf.
 */
function process_post_pdf_resquest_form()
{
	/* Initialize variables */
	$error = array();
	$pdf_request_email = isset($_POST["postPdfRequestEmail"]) ? $_POST["postPdfRequestEmail"] : '';
	$post_id = get_the_ID();
	$pdf_url = '';
	$server_pdf_directory = '/var/www/html/justsell.com/wp-content/themese/justsell/resources/docs/pdfs/';

	/* Clean email address */
	if(strlen($pdf_request_email) <= 0){
		$error[] = "Please enter your email.";
	}else{
		if(!preg_match("/^([a-z0-9_]\.?)*[a-z0-9_]+@([a-z0-9-_]+\.)+[a-z]{2,3}$/i", stripslashes(trim($pdf_request_email)))) {$error[] = "Please enter a valid e-mail address.";}
	}

	/* Return errors found or assemble post specific variables and pass to the send_post_pdf function. */
	if(sizeof($error) > 0)
	{
		$size = sizeof($error);
		$error_message = '';
	
		for ($i=0; $i < $size; $i++)
		{
			if($i == 0)
				$error_message .= '<h3 class="form-error-title">Form Errors</h3>';
			
			$error_message .= '<p class="form-error">- '.$error[$i].'</p>';
		}

		return $error_message . display_post_pdf_resquest_form();
	}
	else
	{
		switch($post_id):
			case 5:
				$pdf_url = $server_pdf_directory . 'the-8-objections.pdf';
				break;
			default:
				return '<p class="form-error">I\'m so sorry, a PDF was not found for this article. Please let us know at <a href="mailto:GoodThings@GiveMore.com">GoodThings@GiveMore.com</a></p>';
				break;
		endswitch;

		/* Pass all post specific variables to the send_post_pdf function. */
		return send_post_engagement_pdf($pdf_url);
	}
} /* END function process_post_pdf_resquest_form */


/*
 * Sends the actual email with pdf attached.
 * @argument $pdf_url initiated in process_post_pdf_request_form() and sent via function call.
 */
function send_post_engagement_pdf($pdf_url)
{
	/* Initialize variables */
	$post_permalink = get_permalink();
	$post_name = get_the_title();
//	global $pdf_request_email;

	/* [ Imports the necessary scripts to control MIME being sent. Use 'find . -name swift_required.php' to find location via ssh ] */
	require_once '/etc/apache2/sites-available/vendor/swiftmailer/swiftmailer/lib/swift_required.php';
	
	/* [ Sets the transport method to PHP Mail ] */
	$transport = Swift_MailTransport::newInstance();
		
	/* [ Create the Mailer using the created Transport ] */
	$mailer = Swift_Mailer::newInstance($transport);
	
	/* [ Create the message ] */
	$message = Swift_Message::newInstance('How To Run Better Meetings Guide')
	  ->setFrom(array('JustSell@JustSell.com' => 'JustSell.com'))
	  ->setTo(array('joegilbert85@gmail.com'))
//				->setBcc(array('jim@givemore.com', 'sam@givemore.com'))
	
		/* [ Create HTML Version ] */
		->setBody('
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			</head>
			
			<body style="background:#FFFFFF; padding:0; margin:0;">

				<!-- Gray BG -->
				<div style="background:#F2F2F2; width:100%;"><table width="100%" border="0" cellspacing="0" bgcolor="#F2F2F2" cellpadding="0" align="center" style="background:#F2F2F2; width:100%;"><tr><td>
					
					<!-- White BG Wrapper -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750"><tr><td>
							
						<!-- ETF Content -->
						<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
														
							<!-- Spacer -->
							<tr><td height="40">&nbsp;</td></tr>
							
							<!-- Copy -->
							<tr><td align="center">
								<p style="color:#666666; font-family:helvetica, arial, sans-serif; font-size:17px; font-weight:300; line-height:25px; margin-top:0; margin-bottom:1.5em; text-align:center;">
									Remember, we\'re here to make good things happen for other people.<br />
									We do that, it all works.
								</p>

								<p style="color:#666666; font-family:helvetica, arial, sans-serif; font-size:17px; font-weight:300; line-height:25px; margin-top:0; margin-bottom:1.5em; text-align:center;">
									If you need anything at all, please call us in Richmond, Virginia at 1-866-952-4483, chat with us on our website (<a href="http://www.givemore.com/?utm_source=gm-meeting-pdf&utm_medium=email&utm_content=text_givemore&utm_campaign=run-better-meetings-pdf" style="color:#1A80D3;">www.GiveMore.com</a>), or email us at <a href="mailto:GoodThings@GiveMore.com" style="color:#1A80D3;">GoodThings@GiveMore.com</a>. We\'d love to talk with you (really).
								</p>
								
								<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:17px; font-weight:300; line-height:25px; margin-top:0; margin-bottom:0; text-align:center;">
									Smovingly,<br />
									The GiveMore.com Team
								</p>
							</td></tr>
			
						</table> <!-- END ETF Content -->
				
						<!-- Real People, Copyright Footer -->
						<table align="center" bgcolor="#262626" border="0" cellpadding="20" cellspacing="0" style="margin:0 auto;" width="750">
							<tr><td align="center">
								<font face="helvetica, arial, sans-serif" style="color:#FFFFFF; font-size:20px; font-weight:500; line-height:22px; text-align:center;">
									We\'re real people here and we\'d love to help you. Really.<br /><br />
								</font>
				
								<font face="helvetica, arial, sans-serif" style="color:#656565; font-size:14px; line-height:22px; text-align:center;">
									&copy; by Give More Media Inc. &nbsp;|&nbsp; <a href="http://www.givemore.com/?utm_source=gm-meeting-pdf&utm_medium=email&utm_content=footer_givemore&utm_campaign=run-better-meetings-pdf" style="color:#656565;">www.GiveMore.com</a> &nbsp;|&nbsp; 115 South 15th Street, Suite 502, Richmond, VA 23219
								</font>
							</td></tr>
						</table> <!-- END Real People, Copyright Footer -->
				
					</td></tr></table><!-- END White BG Wrapper -->

				</td></tr></table></div> <!-- END Gray BG -->
			
			</body>
			</html>
		', 'text/html')
	
		/* [ Create TXT Version (purposely not indented) ] */
		->addPart('

Thanks for requesting a copy of our '. $post_name .' guide!

--
Download the PDF
'. $pdf_url .'
--

Remember, we\'re here to make good things happen for other people. We do that, it all works.

If you need anything at all, please call us in Richmond, Virginia at 1-866-952-4483, chat with us on our website (http://www.JustSell.com), or email us at GoodThings@GiveMore.com. We\'d love to talk with you (really).

Smovingly,
The JustSell.com Team

-------------
We\'re real people here and we\'d love to help you. Really.

(c) by Give More Media Inc. | http://www.JustSell.com | 2500 Gaskins Rd, Richmond, VA, 23238 (USA)
		', 'text/plain')

	; /* [ END of message creation ] */
	

	
	/* [ Send the message ] */
	$sent = $mailer->send($message, $failures);
	
		
	/* [ If the email was sent display thank you message and capture email ] */
	if($sent){
	
//		return "<p>Congrats, you just got motivated to the nth degree. Check your inbox! The url for $post_name is $post_permalink and the pdf location is $pdf_url</p>";

		return "<p>You should have it!</p>";
		
		/* [ Calls the capture email function ] */
//		capture_meeting_pdf_email($meetingPDFContactEmail);
		
	} else {
	 //	die("Sorry but the email could not be sent. Please go back and try again!");
	  echo "Failures:";
	  print_r($failures);
	}

} /* END function send_post_pdf */


/* Wordpress function call to bind the shortcode '[postpdfrequest]' to the functions above. */
add_shortcode( 'postpdfrequest', 'post_pdf_resquest_control' );

?>