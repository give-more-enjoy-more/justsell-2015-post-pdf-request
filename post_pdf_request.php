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
			case 4:
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

	return "<p>Congrats, you just got motivated to the nth degree. Check your inbox! The url for $post_name is $post_permalink and the pdf location is $pdf_url</p>";
} /* END function send_post_pdf */


/* Wordpress function call to bind the shortcode '[postpdfrequest]' to the functions above. */
add_shortcode( 'postpdfrequest', 'post_pdf_resquest_control' );

?>