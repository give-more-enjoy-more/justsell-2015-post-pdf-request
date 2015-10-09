<?php
/**
 * @package Post_PDF_Request
 * @version 0.5
 */

/*
Plugin Name: Post PDF Request
Description: Uses the shortcode [postpdfrequest]
Author: Joe Gilbert
Version: 0.5
*/


/* Pull in the generic global functions needed for this script. */
require_once('/var/www/html/justsell.com/wp-content/themes/justsell/resources/includes/global-functions.php');


/*
 * Simply returns the pdf request input form when called.
 */
function display_post_pdf_resquest_form()
{
	/* Initialize variables */
	$post_title_test = get_the_title();

	/* Build the form and set it to the form_string variable. */
	$form_output_string = '
		<section class="post-pdf-request">
			<div class="post-pdf-request-form-container">
				<h3 class="title">Make yourself (and the people around you) better.</h3>
				<p class="subtitle">Get a PDF version of this content to print out or share.</p>
				<form action="'. $_SERVER['REQUEST_URI'] .'" method="post" name="pdfFormReqest" class="single-input-form" id="pdf-form-request">
					<input name="postPdfRequestEmail" type="text" placeholder="Enter your email here">
					<input name="postPdfRequestSubmit" type="submit" value="Get the PDF">
				</form>
			</div>
		</section>
	';

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
	$server_pdf_directory = 'http://dev.justsell.com/wp-content/themes/justsell/resources/pdfs/in-post/monthly-calendars/';

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
				$pdf_url = $server_pdf_directory . '2015-december.pdf';
				break;

			default:
				return '<p class="form-error">I\'m so sorry, a PDF was not found for this article. Please let us know at <a href="mailto:GoodThings@GiveMore.com">GoodThings@GiveMore.com</a></p>';
				break;

		endswitch;

		/* Pass all post specific variables to the send_post_pdf function. */
		return send_post_engagement_pdf($pdf_url, $pdf_request_email);
	}
} /* END function process_post_pdf_resquest_form */



/*
 * Sends the actual email with pdf attached.
 * @argument $pdf_url initiated in process_post_pdf_request_form() and sent via function call.
 */
function send_post_engagement_pdf($pdf_url, $pdf_request_email)
{
	/* Initialize variables */
	$post_permalink = get_permalink();
	$post_name = get_the_title();
	$subject_line = $post_name . ' from JustSell.com';

	/* [ Imports the necessary scripts to control MIME being sent. Use 'find . -name swift_required.php' to find location via ssh ] */
	require_once '/etc/apache2/sites-available/vendor/swiftmailer/swiftmailer/lib/swift_required.php';
	
	/* [ Sets the transport method to PHP Mail ] */
	$transport = Swift_MailTransport::newInstance();
		
	/* [ Create the Mailer using the created Transport ] */
	$mailer = Swift_Mailer::newInstance($transport);
	
	/* [ Create the message ] */
	$message = Swift_Message::newInstance($subject_line)
	  ->setFrom(array('JustSell@JustSell.com' => 'JustSell.com'))
	  ->setTo(array($pdf_request_email))
//				->setBcc(array('jim@givemore.com', 'sam@givemore.com'))
	
		/* [ Create HTML Version ] */
		->setBody('
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			</head>

			<body style="background:#F2F2F2; padding:0; margin:0;">

				<!-- Hidden text that will show in email excerpt snippets -->
				<span style="color:#F2F2F2; font-size:0px;">Remind your team what better work looks like. Our posters and mini-posters help you remind everyone to be more motivated, committed, and kind ... all the things that make a more enjoyable (and profitable) workday. Shop Posters. GiveMore.com </span>

				<!-- Gray BG -->
				<div style="background:#F2F2F2; width:100%;"><table width="100%" border="0" cellspacing="0" bgcolor="#F2F2F2" cellpadding="0" align="center" style="background:#F2F2F2; width:100%;"><tr><td>

					<!-- White BG Wrapper -->
					<table width="750" border="0" cellspacing="0" bgcolor="#FFFFFF" cellpadding="0" align="center" style="margin:0 auto;"><tr><td>

						<!-- Main Content -->
						<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="500">

							<!-- Spacer -->				
							<tr><td height="35">&nbsp;</td></tr>

							<!-- Intro Copy -->
							<tr><td align="center">
								<p style="color:#474747; font-family:\'HelveticaNeue-Light\', \'Helvetica Neue Light\', \'Helvetica Neue\', helvetica, arial, sans-serif; font-size:40px; font-weight:300; line-height:50px; margin-bottom:0; margin-top:0; text-align:center;">
									You\'re only one click away!
								</p>
								
								<p style="color:#666666; font-family:\'HelveticaNeue-Light\', \'Helvetica Neue Light\', \'Helvetica Neue\', helvetica, arial, sans-serif; font-size:22px; font-weight:300; line-height:30px; margin-bottom:1.5em; margin-top:0; text-align:center;">
									Thanks for requesting a copy of our \''. $post_name .'\' printable PDF.
								</p>
																
								<p style="color:#666666; font-family:\'HelveticaNeue-Light\', \'Helvetica Neue Light\', \'Helvetica Neue\', helvetica, arial, sans-serif; font-size:18px; font-weight:300; line-height:26px; margin-bottom:1em; margin-top:0; text-align:center;">
									Click the link below to download.
								</p>
										
								<p style="color:#FFFFFF; font-family:\'Helvetica Neue\', helvetica, arial, sans-serif; font-size:20px; font-weight:300; line-height:30px; margin-bottom:0; margin-top:0; text-align:center;">
									<a href="'.$pdf_url.'" style="background-color:#1A80D3; border:2px solid #1A80D3; color:#FFFFFF; display:inline-block; padding:0.5em 1.5em; text-decoration:none;">Download the PDF</a>
								</p>
							</td></tr>

						</table> <!-- END Main Content -->

						<!-- Closing Content -->
						<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="500">

							<!-- Spacer -->
							<tr><td height="20">&nbsp;</td></tr>
							
							<!-- URL & Number -->
							<tr><td align="center">
								<p style="color:#666666; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:center;">
									<a href="http://www.justsell.com/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=text+-+justsell-dot-com&utm_campaign=justsell+post+pdf+request" style="color:#1A80D3;">www.JustSell.com</a><br />
									<a href="tel:18669524483" style="color:#666666; text-decoration:none;">1-866-952-4483</a>
								</p>
							</td></tr>

						</table> <!-- END Closing Content -->


						<!-- Book Footer -->
						<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">

							<!-- Line Spacer -->
							<tr><td height="40" style="border-bottom:1px solid #D7D7D7;">&nbsp;</td></tr>
							<tr><td height="40">&nbsp;</td></tr>

							<!-- Book Footer Header -->
							<tr><td align="center">
								<p style="color:#262626; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; line-height:35px; margin-bottom:0; margin-top:0; text-align:center;">
									Ideas to motivate people...
								</p>
							</td></tr>

							<!-- Spacer -->
							<tr><td height="10">&nbsp;</td></tr>

							<!-- Book Images -->
							<tr><td>
								<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
									<tr>
										<td align="center" width="124">
											<a href="http://www.givemore.com/212-the-extra-degree/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+212+books+image&utm_campaign=justsell+post+pdf+request"><img src="http://www.givemore.com/images/dedicateds/books/212-book-140x140.jpg" width="118" height="118" alt="212&deg; the extra degree&reg; Books" title="212&deg; the extra degree&reg; Books" border="0" /></a><br />

											<p style="color:#262626; font-family:helvetica, arial, sans-serif; font-size:12px; line-height:18px; margin-top:0; text-align:center;">
												Inspire a little extra effort and attention.<br />
												<a href="http://www.givemore.com/212-the-extra-degree/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+212+the+extra+degree&utm_campaign=justsell+post+pdf+request" style="color:#1A80D3;">212&deg; the extra degree</a>
											</p>
										</td>

										<td align="center" width="130">
											<a href="http://www.givemore.com/smile-and-move/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+smile+and+move+books+image&utm_campaign=justsell+post+pdf+request"><img src="http://www.givemore.com/images/dedicateds/books/sm-book-140x140.jpg" width="118" height="118" alt="Smile &amp; Move&reg; Books" title="Smile &amp; Move&reg; Books" border="0" /></a><br />

											<p style="color:#262626; font-family:helvetica, arial, sans-serif; font-size:12px; line-height:18px; margin-top:0; text-align:center;">
												Encourage better attitudes and service.<br />
												<a href="http://www.givemore.com/smile-and-move/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+smile+and+move&utm_campaign=justsell+post+pdf+request" style="color:#1A80D3;">Smile &amp; Move</a>
											</p>
										</td>

										<td align="center" width="130">
											<a href="http://www.givemore.com/cross-the-line/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+cross+the+line+booklets+image&utm_campaign=justsell+post+pdf+request"><img src="http://www.givemore.com/images/dedicateds/books/ctl-booklet-140x140.jpg" width="118" height="118" alt="Cross The Line&reg; Booklets" title="Cross The Line&reg; Booklets" border="0" /></a><br />

											<p style="color:#262626; font-family:helvetica, arial, sans-serif; font-size:12px; line-height:18px; margin-top:0; text-align:center;">
												Inspire commitment, effort, and resilience.<br />
												<a href="http://www.givemore.com/cross-the-line/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+cross+the+line&utm_campaign=justsell+post+pdf+request" style="color:#1A80D3;">Cross The Line</a>
											</p>
										</td>

										<td align="center" width="130">
											<a href="http://www.givemore.com/love-your-people/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+love+your+people+booklets+image&utm_campaign=justsell+post+pdf+request"><img src="http://www.givemore.com/images/dedicateds/books/lyp-booklet-140x140.jpg" width="118" height="118" alt="Love Your People&reg; Booklets" title="Love Your People&reg; Booklets" border="0" /></a><br />

											<p style="color:#262626; font-family:helvetica, arial, sans-serif; font-size:12px; line-height:18px; margin-top:0; text-align:center;">
												Encourage more trust and accountability.<br />
												<a href="http://www.givemore.com/love-your-people/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+love+your+people&utm_campaign=justsell+post+pdf+request" style="color:#1A80D3;">Love Your People</a>
											</p>
										</td>

										<td align="center" width="136">
											<a href="http://www.givemore.com/lead-simply/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+lead+simply+books+image&utm_campaign=justsell+post+pdf+request"><img src="http://www.givemore.com/images/dedicateds/books/ls-book-140x140.jpg" width="118" height="118" alt="Lead Simply&trade; Books" title="Lead Simply&trade; Books" border="0" /></a><br />

											<p style="color:#262626; font-family:helvetica, arial, sans-serif; font-size:12px; line-height:18px; margin-top:0; text-align:center;">
												No fluff. No parables.<br />No matrixes. Just truth.<br />
												<a href="http://www.givemore.com/lead-simply/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+lead+simply&utm_campaign=justsell+post+pdf+request" style="color:#1A80D3;">Lead Simply</a>
											</p>
										</td>
									</tr>
								</table>
							</td></tr>
						</table> <!-- END Book Footer -->

						<!-- Speaker Footer -->
						<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">

							<!-- Line Spacer -->
							<tr><td height="40" style="border-bottom:1px solid #D7D7D7;">&nbsp;</td></tr>
							<tr><td height="40">&nbsp;</td></tr>

							<!-- Speaker Header -->
							<tr><td align="center">
								<p style="color:#262626; font-family:helvetica, arial, sans-serif; font-size:30px; font-weight:300; line-height:35px; margin-bottom:0; margin-top:0; text-align:center;">
									Need a speaker for your next event?
								</p>
							</td></tr>

							<!-- Spacer -->
							<tr><td height="10"></td></tr>

							<!-- Discuss keynote copy -->
							<tr><td align="center">
								<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:400; line-height:24px; margin-bottom:1em; margin-top:0; text-align:center;">
									Sam\'s keynotes have inspired thousands of people in all types<br />of organizations and all types of organizational roles.
								</p>

								<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:400; line-height:24px; margin-bottom:0; margin-top:0; text-align:center;">
									If you could use a fresh voice and message to help people care more<br />about their work and the people they work with and for, let\'s talk.
								</p>
							</td></tr>

							<!-- Spacer -->
							<tr><td height="20">&nbsp;</td></tr>

							<!-- Sam Headshot & Phone Number -->
							<tr><td>
								<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="490">

									<tr>
										<td valign="middle" width="150">
											<a href="http://www.givemore.com/speaking/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+sam+headshot+image&utm_campaign=justsell+post+pdf+request"><img src="http://www.givemore.com/images/email/icons/speaking-sam-headshot-150x150.jpg" alt="Sam Parker Headshot" width="150" height="150" border="0" /></a>
										</td>

										<!-- Spacer -->
										<td width="30">&nbsp;</td>

										<td valign="middle" width="310">
											<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:30px; font-weight:300; line-height:35px; margin-bottom:0; margin-top:0; text-align:left;">
												<a href="http://www.givemore.com/speaking/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+learn+about+sam&utm_campaign=justsell+post+pdf+request" style="color:#1A80D3; text-decoration:none;">+ Learn about Sam</a><br />
											</p>
											<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:30px; font-weight:300; line-height:35px; margin-bottom:0; margin-top:0; text-align:left;">
												or call <a href="tel:18669524483" style="color:#666666; text-decoration:none;">(866) 952-4483</a>
											</p>
										</td>
									</tr>

								</table>
							</td></tr>

						</table> <!-- END Speaker Footer -->

						<!-- Upcoming Meetings Footer -->
						<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">

							<!-- Line Spacer -->
							<tr><td height="50" style="border-bottom:1px solid #D7D7D7;">&nbsp;</td></tr>
							<tr><td height="40">&nbsp;</td></tr>

							<!-- Upcoming Meetings Header -->
							<tr><td align="center">
								<p style="color:#262626; font-family:helvetica, arial, sans-serif; font-size:30px; font-weight:300; line-height:35px; margin-bottom:0.25em; margin-top:0; text-align:center;">
									Upcoming meeting, project, or event?
								</p>
							</td></tr>

							<!-- Spacer -->
							<tr><td height="10">&nbsp;</td></tr>

							<!-- Upcoming Meetings Intro -->
							<tr><td align="center">
								<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:400; line-height:24px; margin:0; text-align:center;">
									Our fresh no-fluff messages, handouts, and themes can help you kick it off<br />or support it by making it more interesting and meaningful.
								</p>
							</td></tr>

							<!-- Spacer -->
							<tr><td height="5"></td></tr>

							<!-- Product Links -->
							<tr><td>
								<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="550">

									<tr>
										<td width="75" valign="top" align="center">
											<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; line-height:30px; text-align:center;">
												<a href="http://www.givemore.com/books-and-booklets/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+books&utm_campaign=justsell+post+pdf+request" style="color:#1A80D3; text-decoration:none;">Books</a><br />
												<a href="http://www.givemore.com/videos/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+videos&utm_campaign=justsell+post+pdf+request" style="color:#1A80D3; text-decoration:none;">Videos</a>
											</p>
										</td>

										<td width="180" valign="top" align="center">
											<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; line-height:30px; text-align:center;">
												<a href="http://www.givemore.com/meetings-discussions/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+meeting+packages&utm_campaign=justsell+post+pdf+request" style="color:#1A80D3; text-decoration:none;">Meeting Packages</a><br />
												<a href="http://www.givemore.com/presentations/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+powerpoint+slides&utm_campaign=justsell+post+pdf+request" style="color:#1A80D3; text-decoration:none;">PowerPoint&reg; Slides</a>
											</p>
										</td>

										<td width="130" valign="top" align="center">
											<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; line-height:30px; text-align:center;">
												<a href="http://www.givemore.com/category/pocket-cards/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+pocket+cards&utm_campaign=justsell+post+pdf+request" style="color:#1A80D3; text-decoration:none;">Pocket Cards</a><br />
												<a href="http://www.givemore.com/category/wristbands/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+wristbands&utm_campaign=justsell+post+pdf+request" style="color:#1A80D3; text-decoration:none;">Wristbands</a>
											</p>
										</td>

										<td width="165" valign="top" align="center">
											<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; line-height:30px; text-align:center;">
												<a href="http://www.givemore.com/category/posters-and-prints/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+posters+and+banners&utm_campaign=justsell+post+pdf+request" style="color:#1A80D3; text-decoration:none;">Posters &amp; Banners</a><br />
												<a href="http://www.givemore.com/gear/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+gifts+and+gear&utm_campaign=justsell+post+pdf+request" style="color:#1A80D3; text-decoration:none;">Gifts &amp; Gear</a>
											</p>
										</td>
									</tr>

								</table>
							</td></tr>

							<!-- Spacer -->
							<tr><td height="30">&nbsp;</td></tr>

						</table> <!-- END Upcoming Meetings Footer -->

						<!-- Connect With Us Footer -->
						<table align="center" bgcolor="#E5E5E5" border="0" cellpadding="20" cellspacing="0" style="margin:0 auto;" width="750">
							<tr><td align="center">
								<p style="color:#262626; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:300; line-height:40px; margin-bottom:0.1em; margin-top:0; text-align:center;">
									Connect with us:
								</p>
								<a href="https://www.facebook.com/nogomos" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/facebook-30x30.png" alt="Facebook" width="30" height="30" border="0" /></a>
								<a href="https://twitter.com/give_more" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/twitter-30x30.png" alt="twitter" width="30" height="30" border="0" /></a>
								<a href="https://plus.google.com/114883118757655241133/" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/google-plus-30x30.png" alt="Google Plus" width="30" height="30" border="0" /></a>
								<a href="http://www.linkedin.com/company/givemore-com" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/linkedin-30x30.png" alt="LinkedIn" width="30" height="30" border="0" /></a>
								<a href="http://instagram.com/givemoreenjoymore" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/instagram-30x30.png" alt="Instagram" width="30" height="30" border="0" /></a>
								<a href="http://www.pinterest.com/givemoremedia/"><img src="http://www.givemore.com/images/email/social-media/pinterest-30x30.png" alt="Pinterest" width="30" height="30" border="0" /></a>
							</td></tr>
						</table> <!-- END Connect With Us Footer -->

						<!-- Real People, Copyright Footer -->
						<table align="center" bgcolor="#262626" border="0" cellpadding="20" cellspacing="0" style="margin:0 auto;" width="750">
							<tr><td align="center">
								<p style="font-family:helvetica, arial, sans-serif;margin-top:1em; margin-bottom: 1.5em; color:#FFFFFF; font-size:20px; font-weight:500; line-height:22px; text-align:center;">
									We\'re real people here and we\'d love to help you. Really.
								</p>

								<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:14px; line-height:22px; margin-bottom:1.5em; margin-top:0; text-align:center;">
									&copy; by Give More Media Inc. &nbsp;|&nbsp; <a href="http://www.justsell.com/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+justsell&utm_campaign=justsell+post+pdf+request" style="color:#656565;">www.JustSell.com</a><br />
									115 South 15th Street, Suite 502, Richmond, VA 23219
								</p>
							</td></tr>
						</table> <!-- END Real People, Copyright Footer -->

					</td></tr></table> <!-- END White BG Wrapper -->

				</td></tr></table></div> <!-- END Gray BG -->

			</body>
			</html>
		', 'text/html')
	
		/* [ Create TXT Version (purposely not indented) ] */
		->addPart('

You\'re only one click away!

Thanks for requesting a copy of our '. $post_name .' printable PDF.

--
Download the PDF
'. $pdf_url .'
--

-------------

Ideas to motivate people...

Inspire a little extra effort and attention. 212 the extra degree
http://www.givemore.com/212-the-extra-degree/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+212+the+extra+degree&utm_campaign=justsell+post+pdf+request

Encourage better attitudes and service. Smile & Move
http://www.givemore.com/smile-and-move/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+smile+and+move&utm_campaign=justsell+post+pdf+request

Inspire commitment, effort, and resilience. Cross The Line
http://www.givemore.com/cross-the-line/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+cross+the+line&utm_campaign=justsell+post+pdf+request

Encourage more trust and accountability. Love Your People
http://www.givemore.com/love-your-people/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+love+your+people&utm_campaign=justsell+post+pdf+request

No fluff. No parables. No matrixes. Just truth. Lead [simply]
http://www.givemore.com/lead-simply/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+lead+simply&utm_campaign=justsell+post+pdf+request

-------------


Need a speaker for your next event?
Sam\'s thoughts and ideas have inspired thousands of people. He\'s the guy behind this stuff. Maybe he can help your organization.


Click below to learn about Sam or call (866) 952-4483
http://www.givemore.com/speaking/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+learn+about+sam&utm_campaign=justsell+post+pdf+request


-------------


Upcoming meeting, project, or event?

Our fresh no-fluff messages, handouts, and themes can help you kick it off or support it by making it more interesting and meaningful.

------
Books
http://www.givemore.com/books-and-booklets/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+books&utm_campaign=justsell+post+pdf+request

------
Videos
http://www.givemore.com/videos/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+videos&utm_campaign=justsell+post+pdf+request

------
Meeting Packages
http://www.givemore.com/meetings-discussions/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+meeting+packages&utm_campaign=justsell+post+pdf+request

------
PowerPoint(R) Slides
http://www.givemore.com/presentations/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+powerpoint+slides&utm_campaign=justsell+post+pdf+request

------
Pocket Cards
http://www.givemore.com/category/pocket-cards/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+pocket+cards&utm_campaign=justsell+post+pdf+request

------
Wristbands
http://www.givemore.com/category/wristbands/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+wristbands&utm_campaign=justsell+post+pdf+request

------
Posters & Banners
http://www.givemore.com/category/posters-and-prints/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+posters+and+banners&utm_campaign=justsell+post+pdf+request

------
Gifts & Gear
http://www.givemore.com/gear/?utm_source=js-post-pdf-request&utm_medium=email&utm_content=footer+-+gifts+and+gear&utm_campaign=justsell+post+pdf+request


-------------

Connect with us:
------

Facebook: https://www.facebook.com/nogomos

Twitter: https://twitter.com/give_more

Google+: https://plus.google.com/114883118757655241133/

LinkedIn: http://www.linkedin.com/company/givemore-com

Instagram: http://instagram.com/givemoreenjoymore

Pinterest: http://www.pinterest.com/givemoremedia/

-------------
We\'re real people here and we\'d love to help you. Really.

(c) by Give More Media Inc. | http://www.JustSell.com | 115 South 15th Street, Suite 502, Richmond, VA 23219 USA
		', 'text/plain')

	; /* [ END of message creation ] */
	

	/* [ Send the message ] */
	$sent = $mailer->send($message, $failures);
	
		
	/* [ If the email was sent display thank you message and capture email ] */
	if($sent){

		/* process_capture arguments: $captured_email, $captured_name, $capture_type, $capture_id */
		/* process_capture is in global functions file */
		process_capture($pdf_request_email, null, 'js-post-pdf-request', null);

		return '
			<section class="post-pdf-request">
				<h3 class="title">Please check your inbox.</h3>
				<p class="subtitle">The printable PDF is on its way.</p>
			</section>
		';
		
	} else {
	 //	die("Sorry but the email could not be sent. Please go back and try again!");
	  echo "Failures:";
	  print_r($failures);
	}

} /* END function send_post_pdf */


/* Wordpress function call to bind the shortcode '[postpdfrequest]' to the functions above. */
add_shortcode( 'postpdfrequest', 'post_pdf_resquest_control' );

?>