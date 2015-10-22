<?php
/**
 * @package Post_Book_Excerpt_Request
 * @version 1.0
 */

/*
Plugin Name: Post Book Excerpt Request
Description: Uses the shortcode [postbookexcerptrequest]
Author: Joe Gilbert
Version: 1.0
*/


/* Pull in the generic global functions needed for this script. */
require_once('/var/www/html/justsell/wp-content/themes/justsell/resources/includes/global-functions.php');


/*
 * Simply returns the excerpt request input form when called.
 * @argument $atts is an optional shortcode attribute that will default to 'tt' if one isn't provided.
 */
function display_post_book_excerpt_request_form($book_brand)
{

	/* Build the form and set it to the form_output_string variable. */
	$form_output_string = '
		<section class="post-pdf-request" id="post-book-excerpt-request-form">
			<div class="post-book-excerpt-request-form-container">
				<h3 class="title">Print it out. Stay inspired.</h3>
				<p class="subtitle">Get a printable version of this content to post or share.</p>
				<form action="'. $_SERVER['REQUEST_URI'] .'" method="post" name="bookExcerptFormReqest" class="single-input-form" id="book-excerpt-form-request">
					<input name="postBookExcerptRequestEmail" type="text" placeholder="Enter your email here">
					<input name="postBookExcerptRequestBrand" type="hidden" value="'. $book_brand .'">
					<input name="postBookExcerptRequestSubmit" type="submit" value="Get it now!">
				</form>
			</div>
		</section>
	';

	return $form_output_string;
} /* END function display_post_book_excerpt_request_form */


/*
 * Either calls the display form function, or the process form function.
 */
function post_book_excerpt_request_control( $atts )
{

	/* Initialize variables */
  $shortcode_attributes = shortcode_atts( array(
      'brand' => 'tt'
  ), $atts );

	$book_brand = $shortcode_attributes['brand'];

	/* Calls the display form function if form hasn't been submitted */
  if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || !isset ($_POST['postBookExcerptRequestSubmit']) )
  {
		return display_post_book_excerpt_request_form($book_brand);
  }
  else
  {
		return process_post_book_excerpt_request_form($book_brand);
  }
} /* END function post_pdf_request_control */


/*
 * Processes the form after user submission. It will ultimately either display any errors, or control emailing the pdf.
 */
function process_post_book_excerpt_request_form($book_brand)
{
	/* Initialize variables */
	$error = array();
	$book_excerpt_capture_id = 'post-'  . $book_brand . '-excerpt-request';
	$book_excerpt_request_email = isset($_POST["postBookExcerptRequestEmail"]) ? $_POST["postBookExcerptRequestEmail"] : '';

	/* Clean email address */
	if(strlen($book_excerpt_request_email) <= 0){
		$error[] = "Please enter your email.";
	}else{
		if(!preg_match("/^([a-z0-9_]\.?)*[a-z0-9_]+@([a-z0-9-_]+\.)+[a-z]{2,3}$/i", stripslashes(trim($book_excerpt_request_email)))) {$error[] = "Please enter a valid e-mail address.";}
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

		return $error_message . display_post_book_excerpt_request_form();
	}
	else
	{
		switch($book_brand):
		
			case "ctl":
				$book_title = 'Cross The Line';
				$excerpt_email_snippet = 'Cross The Line by Sam Parker. Better results, better relationships, more opportunities ... that\'s the goal. (is anything more important?) Below is a 1.5-minute sample of the message that\'s helping tens of thousands of people and organizations commit to going beyond mediocrity in everything they do (by the bestselling author of 212 the extra degree and Lead Simply).';
				$excerpt_subject_line = 'Cross The Line (sample)';
				$excerpt_html_content = '
					<!-- Book Header -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						<tr>
							<td style="line-height:0px;">
								<a href="http://www.givemore.com/cross-the-line/?utm_source=js-ctl-excerpt&utm_medium=email&utm_content=header+-+booklet+image&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-ctl-excerpt-1-302x370.jpg" alt="Cross The Line" width="302" height="370" border="0" /></a><a href="http://www.givemore.com/product/cross-the-line-booklet/?utm_source=js-ctl-excerpt&utm_medium=email&utm_content=header+-+buy+the+booklet&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-ctl-excerpt-2-162x370.jpg" alt="Cross The Line" width="162" height="370" border="0" /></a><a href="http://www.givemore.com/cross-the-line/?utm_source=js-ctl-excerpt&utm_medium=email&utm_content=header+-+watch+the+video&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-ctl-excerpt-3-176x370.jpg" alt="Cross The Line" width="176" height="370" border="0" /></a><a href="http://www.givemore.com/cross-the-line/?utm_source=js-ctl-excerpt&utm_medium=email&utm_content=header+-+final+piece&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-ctl-excerpt-4-109x370.jpg" alt="Cross The Line" width="109" height="370" border="0" /></a>
							</td>
						</tr>
					</table> <!-- END Book Header -->

					<!-- Excerpt Content -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						
						<!-- Spacer -->
						<tr><td height="40">&nbsp;</td></tr>
						
						<!-- Overview Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										Overview
									</p>
										
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left;">Below is a 1.5-minute sample of the message that\'s <strong>helping tens of thousands of people and organizations</strong> commit to going beyond mediocrity in everything they do (by the bestselling author of 212&deg; the extra degree&reg; and Lead Simply).</p>
			
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left;">Maybe it\'s the spark that can <strong>help you</strong> create the deeper awareness that inspires real (and wonderful) change.</p>
			
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:left;">Hand it out. Read it as a team. Discuss it. Encourage.</p>
								</td></tr>
							</table>
						</td></tr>
					
						<!-- Line Spacer -->
						<tr><td height="50" style="border-bottom:1px solid #D7D7D7;">&nbsp;</td></tr>
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Excerpt Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										Book Excerpt
									</p>
																	
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:bold; line-height:21px; margin-top:0; margin-bottom:0.8em; text-align:left;">With everything, there\'s a line.</p>
					                                                                                      
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">On one  side of the line is a greater chance to make good things happen (better results, better relationships, more opportunities). This is where you\'ll find all those people you admire.</p>
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">On the other side of the line, there\'s less of a chance.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">And with each line, you have a choice. You want to cross the line or you don\'t. You want the better chance at making those good things happen (meaningful things) or you <strong>settle</strong> with the lesser chance.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Your choice.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">It seems simple but...</p>
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Then there\'ll be those times &ndash; those times when in the short run it\'ll seem like you can\'t cross the line (no matter what you do). But then, those misses (those hurdles) in the short run will sometimes help you over the line in the long run (in a way you couldn\'t have seen). They\'ll serve as lessons, giving you more depth to your experience &ndash; making you stronger and better prepared for the bigger and more important challenges you\'ll face in the future.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">But you won\'t know that at the time. At the time, you\'ll just see that line. And it might seem like a wall. But it really is just a line (a line you want to cross). And it\'s yours to cross, but you have to decide (make that choice).</p>
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">It seems simple but...</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Then there\'ll be those people &ndash; those people who\'ve decided they\'d rather not cross the line and would prefer you didn\'t either (and unfortunately, sometimes those people might be your friends). Maybe they\'ll sprinkle in a little doubt or withhold a little encouragement at just the right time (or even encourage you to do the wrong thing). Maybe they\'ll be a little less subtle about it and just step directly in your way.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.8em; text-align:left;">And then, every once in a while, there\'ll be you &ndash; that inner voice that\'ll try to tell you you\'re not that special and that making something exceptional happen is for other people. (What are you thinking?)</p>
									
									<table width="400" border="0" cellspacing="0" cellpadding="10" style="border-left:4px solid #F0F0F0; margin-left:50px; margin-bottom:30px;">
										<tr><td>
											<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; line-height:22px; margin-top:0; margin-bottom:1em; text-align:left;">The difficult is what takes a little time; the impossible is what takes a little longer.</p>
											<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:14px; line-height:18px; margin-top:0; margin-bottom:0; text-align:left;">Fridtjof Nansen<br />Norwegian explorer</p>
										</td></tr>
									</table>
										
								</td></tr>
							</table>
						</td></tr> <!-- END Excerpt Copy-->

						<!-- Spacer -->
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Social Media Gray Box -->
						<tr><td align="center">
							<table align="center" bgcolor="#F2F2F2" border="0" cellpadding="20" cellspacing="0" style="margin:0 auto;" width="425">
								<tr><td align="center">
									<font face="helvetica, arial, sans-serif" style="color:#656565; font-size:16px; font-weight:300; line-height:40px; text-align:center;">
										Like Cross The Line? Share this with others.<br />
									</font>
									<a href="https://www.facebook.com/sharer/sharer.php?u=http://www.givemore.com/cross-the-line/" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/facebook-30x30.png" alt="Facebook" width="30" height="30" border="0" /></a>
									<a href="http://twitter.com/?status=Love+this+for+motivation+from+GiveMore.com...+http://www.givemore.com/cross-the-line/+@Give_More" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/twitter-30x30.png" alt="twitter" width="30" height="30" border="0" /></a>
									<a href="https://plus.google.com/share?url=http://www.givemore.com/cross-the-line/" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/google-plus-30x30.png" alt="Google Plus" width="30" height="30" border="0" /></a>
									<a href="http://www.linkedin.com/shareArticle?mini=true&url=http://www.givemore.com/cross-the-line/"><img src="http://www.givemore.com/images/email/social-media/linkedin-30x30.png" alt="LinkedIn" width="30" height="30" border="0" /></a>
								</td></tr>
							</table>
						</td></tr>
						
						<!-- Line Spacer -->
						<tr><td height="50" style="border-bottom:1px solid #D7D7D7;">&nbsp;</td></tr>
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Complete Book Includes Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										The complete book includes
									</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:2em; text-align:left;">Concluding thoughts, the 4 ways to Cross The Line, a few inspiring quotes, and a \'Time to Think\' section with questions to help you (and your team) reflect on how to Cross The Line in your work and life.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.5em; text-align:left;"><strong>The entire booklet is a quick 5-minute read</strong> (which means it\'ll be read) that\'s now encouraging tens of thousands of people, companies, and organizations to be more accountable to their work and the people around them.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.5em; text-align:left;"><strong>To watch the 4.5-minute video, buy the booklet, presentation material,</strong> or other fun Cross The Line gear (posters, pens, pocket cards, etc.), please visit <a href="http://www.givemore.com/cross-the-line/?utm_source=js-ctl-excerpt&utm_medium=email&utm_content=text+-+givemore+cross+2&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3;">http://www.GiveMore.com/Cross</a> or call us in Richmond, Virginia at 804-762-4500.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:left;"><strong>Surround yourself with good people, thoughts, and ideas</strong> and more good things will happen ... for everyone.</p>
								</td></tr>
							</table>
						</td></tr>

						<!-- Spacer -->
						<tr><td height="50">&nbsp;</td></tr>

					</table> <!-- END Excerpt Content -->
					
					<!-- Copyright -->
					<table align="center" bgcolor="#F2F2F2" border="0" cellpadding="20" cellspacing="0" style="margin:0 auto;" width="750">
						<tr><td align="center">
							<font face="helvetica, arial, sans-serif" style="color:#656565; font-size:12px; font-weight:300; line-height:15px; text-align:center;">
								&copy; by Give More Media Inc. Please don\'t publish or distribute this sample. It\'s for evaluation purposes only.
							</font>
						</td></tr>
					</table> <!-- END Copyright -->
					
					<!-- Buy Book & Watch Video Footer -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						<tr><td style="line-height:0px;">
							<a href="http://www.givemore.com/product/cross-the-line-booklet/?utm_source=js-ctl-excerpt&utm_medium=email&utm_content=footer+-+buy+the+booklet&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/footer-ctl-excerpt-1-338x375.jpg" alt="Cross The Line" width="338" height="375" border="0" /></a><a href="http://www.givemore.com/cross-the-line/?utm_source=js-ctl-excerpt&utm_medium=email&utm_content=footer+-+watch+the+video&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/footer-ctl-excerpt-2-412x375.jpg" alt="Cross The Line" width="412" height="375" border="0" /></a>
						</td></tr>
					</table> <!-- END Buy Book & Watch Video Footer -->
				';
				$excerpt_text_content = '
					"Better results, better relationships, more opportunities ... that\'s the goal."
					(Is anything more important?)

					Below is a 1.5-minute sample of the message that\'s helping tens of thousands of people and organizations commit to going beyond mediocrity in everything they do (by the bestselling author of 212 the extra degree(r) and Lead Simply).

					Maybe it\'s the spark that can help you create the deeper awareness that inspires real (and wonderful) change.

					Hand it out. Read it as a team. Discuss it. Encourage.

					-------------

					Cross The Line
					by Sam Parker

					(To buy the booklet or watch the 4.5-minute video, please visit http://www.GiveMore.com/Cross.)


					With everything, there\'s a line.

					On one side of the line is a greater chance to make good things happen (better results, better relationships, more opportunities). This is where you\'ll find all those people you admire.

					On the other side of the line, there\'s less of a chance.

					And with each line, you have a choice. You want to cross the line or you don\'t. You want the better chance at making those good things happen (meaningful things) or you settle with the lesser chance.

					Your choice.

					It seems simple but...

					Then there\'ll be those times - those times when in the short run it\'ll seem like you can\'t cross the line (no matter what you do). But then, those misses (those hurdles) in the short run will sometimes help you over the line in the long run (in a way you couldn\'t have seen). They\'ll serve as lessons, giving you more depth to your experience - making you stronger and better prepared for the bigger and more important challenges you\'ll face in the future.

					But you won\'t know that at the time. At the time, you\'ll just see that line. And it might seem like a wall. But it really is just a line (a line you want to cross). And it\'s yours to cross, but you have to decide (make that choice).

					It seems simple but...

					Then there\'ll be those people - those people who\'ve decided they\'d rather not cross the line and would prefer you didn\'t either (and unfortunately, sometimes those people might be your friends). Maybe they\'ll sprinkle in a little doubt or withhold a little encouragement at just the right time (or even encourage you to do the wrong thing). Maybe they\'ll be a little less subtle about it and just step directly in your way.

					And then, every once in a while, there\'ll be you - that inner voice that\'ll try to tell you you\'re not that special and that making something exceptional happen is for other people. (What are you thinking?)

					  The difficult is what takes a little time; the impossible is what takes a little longer.

					  Fridtjof Nansen
					  Norwegian explorer


					(c) by Give More Media Inc. Please don\'t publish or distribute this sample. It\'s for evaluation purposes only.

					-------------

					Buy the booklet
					http://www.givemore.com/product/cross-the-line-booklet/?utm_source=js-ctl-excerpt&utm_medium=email&utm_content=button+-+buy+the+booklet&utm_campaign=justsell+book+excerpt+request

					Watch the video
					http://www.givemore.com/cross-the-line/?utm_source=js-ctl-excerpt&utm_medium=email&utm_content=button+-+watch+the+video&utm_campaign=justsell+book+excerpt+request

					-------------

					The complete booklet includes: Concluding thoughts, the 4 ways to Cross The Line, a few inspiring quotes, and a \'Time to Think\' section with questions to help you (and your team) reflect on how to Cross The Line in your work and life.

					The entire booklet is a quick 5-minute read (which means it\'ll be read) that\'s now encouraging tens of thousands of people, companies, and organizations to be more accountable to their work and the people around them.

					To watch the 4.5-minute video, buy the booklet, presentation material, or other fun Cross The Line gear (posters, pens, pocket cards, etc.), please visit http://www.GiveMore.com/Cross or call us in Richmond, Virginia at 804-762-4500.

					Surround yourself with good people, thoughts, and ideas and more good things will happen ... for everyone.
				';
				break;

			case "ls":
				$book_title = 'Lead Simply';
				$excerpt_email_snippet = 'Lead Simply by Sam Parker. Better results, better relationships, more opportunities ... that\'s the goal. (Is anything more important?) Below is a 4-minute sample of the message that\'s helping thousands of leaders and organizations focus on what\'s most important (and often missed) in creating a special team of people that does great work (by the bestselling author of 212 the extra degree).';
				$excerpt_subject_line = 'Lead Simply (sample)';
				$excerpt_html_content = '
					<!-- Book Header -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						<tr>
							<td style="line-height:0px;">
								<a href="http://www.givemore.com/lead-simply/?utm_source=js-ls-excerpt&utm_medium=email&utm_content=header+-+book+image&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-ls-excerpt-1-302x370.jpg" alt="Lead Simply" width="302" height="370" border="0" /></a><a href="http://www.givemore.com/product/lead-simply-book/?utm_source=js-ls-excerpt&utm_medium=email&utm_content=header+-+buy+the+book&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-ls-excerpt-2-153x370.jpg" alt="Lead Simply" width="153" height="370" border="0" /></a><a href="http://www.givemore.com/lead-simply/?utm_source=js-ls-excerpt&utm_medium=email&utm_content=header+-+watch+the+video&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-ls-excerpt-3-167x370.jpg" alt="Lead Simply" width="167" height="370" border="0" /></a><a href="http://www.givemore.com/lead-simply/?utm_source=js-ls-excerpt&utm_medium=email&utm_content=header+-+final+piece&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-ls-excerpt-4-128x370.jpg" alt="Lead Simply" width="128" height="370" border="0" /></a>
							</td>
						</tr>
					</table> <!-- END Book Header -->

					<!-- Excerpt Content -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						
						<!-- Spacer -->
						<tr><td height="40">&nbsp;</td></tr>
						
						<!-- Overview Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										Overview
									</p>
																				
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left;">Below is a 4-minute sample of the message that\'s <strong>helping thousands of leaders and organizations</strong> focus on what\'s most important (and often missed) in creating a special team of people that does great work (by the bestselling author of 212&deg; the extra degree&reg;).</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left;">Maybe it\'s the spark that can <strong>help you</strong> create the deeper awareness that inspires real (and wonderful) change.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:left;">Hand it out. Read it as a team. Discuss it. Encourage.</p>
										
								</td></tr>
							</table>
						</td></tr>
					
						<!-- Line Spacer -->
						<tr><td height="50" style="border-bottom:1px solid #D7D7D7;">&nbsp;</td></tr>
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Excerpt Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										Book Excerpt
									</p>
															
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:bold; line-height:21px; margin-top:0; margin-bottom:0.8em; text-align:left;">Model. Connect. Involve.</p>
													
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">That\'s your framework for leadership &ndash; your simple, day-to-day, in-the-trenches formula to creating that special team that does important and meaningful work ... that cares to make things better ... continually ... every day.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										Model the behavior you want to see.<br />
										Connect with the people you lead.<br />
										Involve them as much as possible.
									</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">That\'s it. Ridiculously simple.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">For many years, The Gallup Organization has conducted surveys and polls to help us understand how into our work we are. The fancy term for this in the study of organizational behavior is <strong>employee engagement.</strong></p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">They\'ve determined the following (give or take a percentage point one way or the other depending on the year)...</p>
									
									<ul style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										<li>29 of 100 of us are engaged with our work (we care)</li>
										<li>54 of 100 are not engaged with our work (we don\'t really care)</li>
										<li>17 of 100 are actively disengaged with our work (we really don\'t care ... and we <br/>spread it ... we thrive on getting in the way of making good things happen)</li>
									</ul>			 
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Call the first group what you like (start with wonderful but then realize that being engaged in our work is really just our obligation to each other). The last two groups are <strong>Gomos</strong> and <strong>D-grunts</strong>. Gomos are the people who go through the motions. D-grunts are the disgruntled among us.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Add the two groups together and we have 71 out of every 100 people going through the motions each day ... or worse.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Frightening, isn\'t it?</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:bold; line-height:21px; margin-top:0; margin-bottom:0.8em; text-align:left;">Think about that lost potential and unrealized potential.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">(Do the math. What\'s your experience?)</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Why would more than half the people who go to work every day, leave their hearts and minds at home? Why would so many people be so apathetic about their work? Hundreds of variables could come into play here, right? Many of them are far beyond our control as leaders.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Perhaps, though, one of the most basic points behind all of it is that too many of us have forgotten that we\'re ultimately doing what it is we do so that we can make good things happen for other people.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Walk that basic mission statement through every single role, every single profession, and every single industry and you\'ll see that it fits.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">We\'re here to make good things happen for someone else. We do that ... and it all works.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">So as leaders (with a title or not), how can we help the people around us (and ourselves) to remember that and be more likely to stay engaged (wonderful)? How can we be sure we don\'t lose all that valuable time and energy and the fulfilling enjoyment of service (contribution, care) and the great results all of it can bring (organizationally and personally)?</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">First, we need to accept the fact, as challenging and frustrating as it might be, that the <strong>need for solid and ongoing reinforcement never (ever, ever, ever, ever) ends.</strong></p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Think about that. To become stronger or more skillful in anything, we have to commit to continual training and practice.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">How comfortable would you be with your surgeon if she only practiced medicine at an annual weekend retreat?</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Think about working out. What positive results can we expect by visiting the gym just twice a month? (Of course, some of us continue to try.)</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Learning a musical instrument or better golf game &ndash; everything we want to get better at and everything we want to be sure doesn\'t atrophy, erode, or degrade requires ongoing attention, effort, and practice.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Why do we forget this? It should be the reverse. As we get more and more experience, we should get wiser and remember it more often as we face our challenges in leading other people (and ourselves). We need to (and please hear this)...</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Be as committed and consistent in our coaching and development efforts as we ask our people to be in their roles and with the people they serve. (Did you hear that?)</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Leadership is a joint effort.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">We need to support each other more often and remember our interdependence and our obligation to the big picture...</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">We\'re here to make good things happen for other people.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.8em; text-align:left;">This is what will help us grow that engaged minority (that 29% of people who care) into the engaged majority. This is what will help us create incredibly productive teams and relationships of high-trust and truth (much more enjoyable) instead of being content with something less because it\'s uncomfortable and difficult to push ourselves. (uncomfortable = growth)</p>
									
									<table width="400" border="0" cellspacing="0" cellpadding="10" style="border-left:4px solid #F0F0F0; margin-left:50px; margin-bottom:30px;">
										<tr><td>
											<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:22px; margin-top:0; margin-bottom:1em; text-align:left;">There are risks and costs to a program of action. But they are far less than the long-range risks and costs of comfortable inaction.</p>
											<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:14px; font-weight:300; line-height:18px; margin-top:0; margin-bottom:0; text-align:left;">John F. Kennedy<br />35th U.S. president</p>
										</td></tr>
									</table>		

								</td></tr>
							</table>
						</td></tr> <!-- END Excerpt Copy-->

						<!-- Spacer -->
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Social Media Gray Box -->
						<tr><td align="center">
							<table align="center" bgcolor="#F2F2F2" border="0" cellpadding="20" cellspacing="0" style="margin:0 auto;" width="425">
								<tr><td align="center">
									<font face="helvetica, arial, sans-serif" style="color:#656565; font-size:16px; font-weight:300; line-height:40px; text-align:center;">
										Like Lead Simply? Share this with others.<br />
									</font>
									<a href="https://www.facebook.com/sharer/sharer.php?u=http://www.givemore.com/lead-simply/" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/facebook-30x30.png" alt="Facebook" width="30" height="30" border="0" /></a>
									<a href="http://twitter.com/?status=Love+this+for+motivation+from+GiveMore.com...+http://www.givemore.com/lead-simply/+@Give_More" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/twitter-30x30.png" alt="twitter" width="30" height="30" border="0" /></a>
									<a href="https://plus.google.com/share?url=http://www.givemore.com/lead-simply/" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/google-plus-30x30.png" alt="Google Plus" width="30" height="30" border="0" /></a>
									<a href="http://www.linkedin.com/shareArticle?mini=true&url=http://www.givemore.com/lead-simply/"><img src="http://www.givemore.com/images/email/social-media/linkedin-30x30.png" alt="LinkedIn" width="30" height="30" border="0" /></a>
								</td></tr>
							</table>
						</td></tr>
						

						<!-- Line Spacer -->
						<tr><td height="50" style="border-bottom:1px solid #D7D7D7;">&nbsp;</td></tr>
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Complete Book Includes Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										The complete book includes
									</p>
									
									<font face="helvetica, arial, sans-serif" style="color:#656565; font-size:16px; font-weight:300; line-height:24px; text-align:left;">
										<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:2em; text-align:left;">More support for the foundation behind the Lead Simply framework (an inspiring and encouraging call-to-action), several specific and creative ideas to help you &quot;model the behavior you want to see, connect with the people you lead, and involve them as much as possible,&quot; many additional thoughts, facts, and resources that can help you even more in your efforts to create your special team of people, and one more (extremely) important reminder.</p>
										
										<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.5em; text-align:left;"><strong>The entire book is a quick 20-minute read</strong> (which means it\'ll be read) that\'s now encouraging tens of thousands of leaders and organizations to be more accountable to their work and the people around them. It\'s 4 x 6 inches in size (made to fit any pocket) and just over 6,000 words.</p>
										
										<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.5em; text-align:left;"><strong>To watch the 3-minute video, buy the book, presentation material,</strong> or other fun Lead Simply gear (posters, pens, pocket cards, etc.), please visit <a href="http://www.givemore.com/lead-simply/?utm_source=js-ls-excerpt&utm_medium=email&utm_content=text+-+givemore+lead&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3;">http://www.GiveMore.com/Lead</a> or call us in Richmond, Virginia at 804-762-4500.</p>
										
										<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:left;"><strong>Surround yourself with good people, thoughts, and ideas</strong> and more good things will happen ... for everyone.</p>
									</font>
								</td></tr>
							</table>
						</td></tr>

						<!-- Spacer -->
						<tr><td height="50">&nbsp;</td></tr>

					</table> <!-- END Excerpt Content -->
					
					<!-- Copyright -->
					<table align="center" bgcolor="#F2F2F2" border="0" cellpadding="20" cellspacing="0" style="margin:0 auto;" width="750">
						<tr><td align="center">
							<font face="helvetica, arial, sans-serif" style="color:#656565; font-size:12px; font-weight:300; line-height:15px; text-align:center;">
								&copy; by Give More Media Inc. Please don\'t publish or distribute this sample. It\'s for evaluation purposes only.
							</font>
						</td></tr>
					</table> <!-- END Copyright -->
					
					<!-- Buy Book & Watch Video Footer -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						<tr><td style="line-height:0px;">
							<a href="http://www.givemore.com/product/lead-simply-book/?utm_source=js-ls-excerpt&utm_medium=email&utm_content=footer+-+buy+the+book&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/footer-ls-excerpt-1-338x375.jpg" alt="Lead Simply" width="338" height="375" border="0" /></a><a href="http://www.givemore.com/lead-simply/?utm_source=js-ls-excerpt&utm_medium=email&utm_content=footer+-+watch+the+video&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/footer-ls-excerpt-2-412x375.jpg" alt="Lead Simply" width="412" height="375" border="0" /></a>
						</td></tr>
					</table> <!-- END Buy Book & Watch Video Footer -->
				';
				$excerpt_text_content = '
					"Better results, better relationships, more opportunities ... that\'s the goal."
					(Is anything more important?)

					Below is a 4-minute sample of the message that\'s helping thousands of leaders and organizations focus on what\'s most important (and often missed) in creating a special team of people that does great work (by the bestselling author of 212° the extra degree®).

					Maybe it\'s the spark that can help you create the deeper awareness that inspires real (and wonderful) change.

					Hand it out. Read it as a team. Discuss it. Encourage.

					-------------

					Lead Simply
					by Sam Parker

					(To buy the book or watch the 3-minute video, please visit http://www.GiveMore.com/Lead.)


					Model. Connect. Involve.

					That\'s your framework for leadership - your simple, day-to-day, in-the-trenches formula to creating that special team that does important and meaningful work ... that cares to make things better ... continually ... every day.

					  - Model the behavior you want to see.

					  - Connect with the people you lead.

					  - Involve them as much as possible.

					That\'s it. Ridiculously simple.

					For many years, The Gallup Organization has conducted surveys and polls to help us understand how into our work we are. The fancy term for this in the study of organizational behavior is employee engagement.

					They\'ve determined the following (give or take a percentage point one way or the other depending on the year)...

					  - 29 of 100 of us are engaged with our work (we care)

					  - 54 of 100 are not engaged with our work (we don\'t really care)

					  - 17 of 100 are actively disengaged with our work (we really don\'t care ... and we spread it ... we thrive on getting in the way of making good things happen)
					  
					Call the first group what you like (start with wonderful but then realize that being engaged in our work is really just our obligation to each other). The last two groups are Gomos and D-grunts. Gomos are the people who go through the motions. D-grunts are the disgruntled among us.

					Add the two groups together and we have 71 out of every 100 people going through the motions each day ... or worse.

					Frightening, isn\'t it?

					Think about that lost potential and unrealized potential.

					(Do the math. What\'s your experience?)

					Why would more than half the people who go to work every day, leave their hearts and minds at home? Why would so many people be so apathetic about their work? Hundreds of variables could come into play here, right? Many of them are far beyond our control as leaders.

					Perhaps, though, one of the most basic points behind all of it is that too many of us have forgotten that we\'re ultimately doing what it is we do so that we can make good things happen for other people.

					Walk that basic mission statement through every single role, every single profession, and every single industry and you\'ll see that it fits.

					We\'re here to make good things happen for someone else. We do that ... and it all works.

					So as leaders (with a title or not), how can we help the people around us (and ourselves) to remember that and be more likely to stay engaged (wonderful)? How can we be sure we don\'t lose all that valuable time and energy and the fulfilling enjoyment of service (contribution, care) and the great results all of it can bring (organizationally and personally)?

					First, we need to accept the fact, as challenging and frustrating as it might be, that the need for solid and ongoing reinforcement never (ever, ever, ever, ever) ends.

					Think about that. To become stronger or more skillful in anything, we have to commit to continual training and practice.

					How comfortable would you be with your surgeon if she only practiced medicine at an annual weekend retreat?

					Think about working out. What positive results can we expect by visiting the gym just twice a month? (Of course, some of us continue to try.)

					Learning a musical instrument or better golf game - everything we want to get better at and everything we want to be sure doesn\'t atrophy, erode, or degrade requires ongoing attention, effort, and practice.

					Why do we forget this? It should be the reverse. As we get more and more experience, we should get wiser and remember it more often as we face our challenges in leading other people (and ourselves). We need to (and please hear this)...

					Be as committed and consistent in our coaching and development efforts as we ask our people to be in their roles and with the people they serve. (Did you hear that?)

					Leadership is a joint effort.

					We need to support each other more often and remember our interdependence and our obligation to the big picture...

					We\'re here to make good things happen for other people.

					This is what will help us grow that engaged minority (that 29% of people who care) into the engaged majority. This is what will help us create incredibly productive teams and relationships of high-trust and truth (much more enjoyable) instead of being content with something less because it\'s uncomfortable and difficult to push ourselves. (uncomfortable = growth)

					  There are risks and costs to a program of action. But they are far less than the long-range risks and costs of comfortable inaction.

					  John F. Kennedy
					  35th U.S. president

					(c) by Give More Media Inc. Please don\'t publish or distribute this sample. It\'s for evaluation purposes only.

					-------------

					Buy the Book
					http://www.givemore.com/product/lead-simply-book/?utm_source=js-ls-excerpt&utm_medium=email&utm_content=button+-+buy+book&utm_campaign=justsell+book+excerpt+request

					Watch the Video
					http://www.givemore.com/lead-simply/?utm_source=js-ls-excerpt&utm_medium=email&utm_content=button+-+watch+video&utm_campaign=justsell+book+excerpt+request

					-------------

					The complete book includes: More support for the foundation behind the Lead Simply framework (an inspiring and encouraging call-to-action), several specific and creative ideas to help you "model the behavior you want to see, connect with the people you lead, and involve them as much as possible," many additional thoughts, facts, and resources that can help you even more in your efforts to create your special team of people, and one more (extremely) important reminder.

					The entire book is a quick 20-minute read (which means it\'ll be read) that\'s now encouraging tens of thousands of leaders and organizations to be more accountable to their work and the people around them. It\'s 4 x 6 inches in size (made to fit any pocket) and just over 6,000 words.

					To watch the 3-minute video, buy the book, presentation material, or other fun Lead Simply gear (posters, pens, pocket cards, etc.), please visit http://www.GiveMore.com/Lead or call us in Richmond, Virginia at 804-762-4500.

					Surround yourself with good people, thoughts, and ideas and more good things will happen ... for everyone.
				';
				break;

			case "lyp":
				$book_title = 'Love Your People';
				$excerpt_email_snippet = 'Love Your People by Sam Parker. Better results, better relationships, more opportunities ... that\'s the goal. (Is anything more important?) Below is a 1.5-minute sample of the message that\'s helping thousands of people and organizations be kinder and more accountable to each other and the people they serve (by the bestselling author of 212 the extra degree and Lead Simply).';
				$excerpt_subject_line = 'Love Your People (sample)';
				$excerpt_html_content = '
					<!-- Book Header -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						<tr>
							<td style="line-height:0px;">
								<a href="http://www.givemore.com/love-your-people/?utm_source=js-lyp-excerpt&utm_medium=email&utm_content=header+-+booklet+image&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-lyp-excerpt-1-302x370.jpg" alt="Love Your People" width="302" height="370" border="0" /></a><a href="http://www.givemore.com/product/love-your-people-booklet/?utm_source=js-lyp-excerpt&utm_medium=email&utm_content=header+-+buy+the+booklet&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-lyp-excerpt-2-162x370.jpg" alt="Love Your People" width="162" height="370" border="0" /></a><a href="http://www.givemore.com/love-your-people/?utm_source=js-lyp-excerpt&utm_medium=email&utm_content=header+-+watch-the-video&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-lyp-excerpt-3-176x370.jpg" alt="Love Your People" width="176" height="370" border="0" /></a><a href="http://www.givemore.com/love-your-people/?utm_source=js-lyp-excerpt&utm_medium=email&utm_content=header+-+final+piece&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-lyp-excerpt-4-109x370.jpg" alt="Love Your People" width="109" height="370" border="0" /></a>
							</td>
						</tr>
					</table> <!-- END Book Header -->

					<!-- Excerpt Content -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						
						<!-- Spacer -->
						<tr><td height="40">&nbsp;</td></tr>
						
						<!-- Overview Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										Overview
									</p>
																					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left;">Below is a 1.5-minute sample of the message that\'s <strong>helping thousands of people and organizations</strong> be kinder and more accountable to each other and the people they serve (by the bestselling author of 212&deg; the extra degree&reg; and Lead Simply).</p>

									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left;">Maybe it\'s the spark that can <strong>help you</strong> create the deeper awareness that inspires real (and wonderful) change.</p>

									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:left;">Hand it out. Read it as a team. Discuss it. Encourage.</p>
										
								</td></tr>
							</table>
						</td></tr>
					
						<!-- Line Spacer -->
						<tr><td height="50" style="border-bottom:1px solid #D7D7D7;">&nbsp;</td></tr>
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Excerpt Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										Book Excerpt
									</p>
																	
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:bold; line-height:21px; margin-top:0; margin-bottom:0.8em; text-align:left;">PART OF THE PROBLEM is the eggshells.</p>
					                                                                                      
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">We\'ve laid them out around ourselves and become worried about stepping on those that others have laid around themselves.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Some of us (all of us at one time or another) have forgotten our interdependence and obligation to other people. We\'ve put ourselves (our comfort, our hearts, our minds) at the center of our universe (our universe?).</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">I want to encourage you to Love Your People.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Who are your people? Everyone who\'s important to you ... your family ... your friends ... your colleagues ... your customers, patients, team, students. These are your people (and you\'re theirs).</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">And love? It\'s care.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">And care? It\'s attention and contribution (what you give, what you do). It\'s kindness, patience, generosity, and truth. It\'s encouraging, apologetic, forgiving, and thankful.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">We\'ve let too much get between us (each other) and the reasons we\'re here. We\'ve allowed ourselves to slip into a state of busy distraction &ndash; seeking the complex instead of embracing the simple. It\'s time to stop going through the motions with our days (hours, minutes). We need to give more and we need to enjoy more.</p>
					
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:bold; line-height:21px; margin-top:0; margin-bottom:0.8em; text-align:left;">&quot;How do I love my people?&quot;</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left; text-indent:3em;">You know how.</p>
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:bold; line-height:21px; margin-top:0; margin-bottom:1em; text-align:left;">&quot;But specifically. How?&quot;</p>
					
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;"><strong>START BY DOING YOUR WORK.</strong> Contribute. Help someone else by doing what it is you do (on the job or in life). Do it the way you wish everyone else would do it for you (golden rule stuff).</p>
					 
									<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left; text-indent:3em;">But I don\'t like my work, situation, company, boss, colleagues...</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Be a part of the solution or find someplace/thing else.</p>
					 
									<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left; text-indent:3em;">Easy for you to say. I have a family, mortgage, credit cards, responsibilities...</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Then be a part of the solution and start making where you are better (by the way ... everyone has many of those things too).</p>
					 
									<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left; text-indent:3em;">But I told you ... my boss, coworkers, customers, situation...</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">I\'m sorry. You\'re a grown-up. Be a part of the solution or find someplace/thing else. Really.</p>
					 
									<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left; text-indent:3em;">It\'s not that simple.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.5em; text-align:left;">Yes ... it is.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;"><strong>BE KIND.</strong> This means you\'re considerate (have concern and respect for others). It means you\'re pleasantly helpful and generous where you can be.</p>
					 
									<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left; text-indent:3em;">But what if someone/thing makes it difficult to be kind?</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Do your best to act in a way you\'d like others to act toward you when you make things difficult.</p>
					 
									<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left; text-indent:3em;">But, I don\'t make things difficult.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">We all do.</p>
					 
									<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left; text-indent:3em;">But...</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.5em; text-align:left;">Me too.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;"><strong>BE PATIENT.</strong> Approach people and situations calmly. Have a soothing and relaxed presence and give others the time you\'d like to be given.</p>
					 
									<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left; text-indent:3em;">But what if it\'s really urgent, like an emergency?</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Be less patient with emergencies.</p>
					 
									<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left; text-indent:3em;">What if I think it\'s more urgent than someone else thinks it is?</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Then it\'ll be more challenging to be patient.</p>
					 
									<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left; text-indent:3em;">What kind of an answer is that?</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:2em; text-align:left;">Truth.</p>
										
								</td></tr>
							</table>
						</td></tr> <!-- END Excerpt Copy-->

						<!-- Spacer -->
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Social Media Gray Box -->
						<tr><td align="center">
							<table align="center" bgcolor="#F2F2F2" border="0" cellpadding="20" cellspacing="0" style="margin:0 auto;" width="425">
								<tr><td align="center">
									<font face="helvetica, arial, sans-serif" style="color:#656565; font-size:16px; font-weight:300; line-height:40px; text-align:center;">
										Like Love Your People? Share this with others.<br />
									</font>
									<a href="https://www.facebook.com/sharer/sharer.php?u=http://www.givemore.com/love-your-people/" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/facebook-30x30.png" alt="Facebook" width="30" height="30" border="0" /></a>
									<a href="http://twitter.com/?status=Love+this+for+motivation+from+GiveMore.com...+http://www.givemore.com/love-your-people/+@Give_More" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/twitter-30x30.png" alt="twitter" width="30" height="30" border="0" /></a>
									<a href="https://plus.google.com/share?url=http://www.givemore.com/love-your-people/" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/google-plus-30x30.png" alt="Google Plus" width="30" height="30" border="0" /></a>
									<a href="http://www.linkedin.com/shareArticle?mini=true&url=http://www.givemore.com/love-your-people/"><img src="http://www.givemore.com/images/email/social-media/linkedin-30x30.png" alt="LinkedIn" width="30" height="30" border="0" /></a>
								</td></tr>
							</table>
						</td></tr>
						

						<!-- Line Spacer -->
						<tr><td height="50" style="border-bottom:1px solid #D7D7D7;">&nbsp;</td></tr>
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Complete Book Includes Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										The complete book includes
									</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:2em; text-align:left;">The additional 5 ways to Love Your People (be honest, encourage people, apologize, forgive, thank people), the simple approach to making it happen, a few inspiring quotes, and a \'Time to Think\' section with questions to help you (and your team) reflect on how to Love Your People.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.5em; text-align:left;"><strong>The entire booklet is a quick 5-minute read</strong> (which means it\'ll be read) that\'s now encouraging thousands of people, companies, and organizations to be more accountable to their work and the people around them.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.5em; text-align:left;"><strong>To watch the 3-minute video, buy the booklet, presentation material,</strong> or other fun Love Your People gear (posters, pens, pocket cards, etc.), please visit <a href="http://www.givemore.com/love-your-people/?utm_source=js-lyp-excerpt&utm_medium=email&utm_content=text+-+givemore+love+2&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3;">http://www.GiveMore.com/Love</a> or call us in Richmond, Virginia at 804-762-4500.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:left;"><strong>Surround yourself with good people, thoughts, and ideas</strong> and more good things will happen ... for everyone.</p>
								</td></tr>
							</table>
						</td></tr>

						<!-- Spacer -->
						<tr><td height="50">&nbsp;</td></tr>

					</table> <!-- END Excerpt Content -->
					
					<!-- Copyright -->
					<table align="center" bgcolor="#F2F2F2" border="0" cellpadding="20" cellspacing="0" style="margin:0 auto;" width="750">
						<tr><td align="center">
							<font face="helvetica, arial, sans-serif" style="color:#656565; font-size:12px; font-weight:300; line-height:15px; text-align:center;">
								&copy; by Give More Media Inc. Please don\'t publish or distribute this sample. It\'s for evaluation purposes only.
							</font>
						</td></tr>
					</table> <!-- END Copyright -->
					
					<!-- Buy Book & Watch Video Footer -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						<tr><td style="line-height:0px;">
							<a href="http://www.givemore.com/product/love-your-people-booklet/?utm_source=js-lyp-excerpt&utm_medium=email&utm_content=footer+-+buy+the+booklet&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/footer-lyp-excerpt-1-338x375.jpg" alt="Love Your People" width="338" height="375" border="0" /></a><a href="http://www.givemore.com/love-your-people/?utm_source=js-lyp-excerpt&utm_medium=email&utm_content=footer+-+watch+the+video&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/footer-lyp-excerpt-2-412x375.jpg" alt="Love Your People" width="412" height="375" border="0" /></a>
						</td></tr>
					</table> <!-- END Buy Book & Watch Video Footer -->
				';
				$excerpt_text_content = '
					"Better results, better relationships, more opportunities ... that\'s the goal."
					(Is anything more important?)

					Below is a 1.5-minute sample of the message that\'s helping thousands of people and organizations be kinder and more accountable to each other and the people they serve (by the bestselling author of 212 the extra degree(r) and Lead Simply).

					Maybe it\'s the spark that can help you create the deeper awareness that inspires real (and wonderful) change.

					Hand it out. Read it as a team. Discuss it. Encourage.

					-------------

					Love Your People
					by Sam Parker

					(To buy the booklet or watch the 3-minute video, please visit http://www.GiveMore.com/Love.)


					PART OF THE PROBLEM is the eggshells.

					We\'ve laid them out around ourselves and become worried about stepping on those that others have laid around themselves.

					Some of us (all of us at one time or another) have forgotten our interdependence and obligation to other people. We\'ve put ourselves (our comfort, our hearts, our minds) at the center of our universe (our universe?).

					I want to encourage you to Love Your People.

					Who are your people? Everyone who\'s important to you ... your family ... your friends ... your colleagues ... your customers, patients, team, students. These are your people (and you\'re theirs).

					And love? It\'s care.

					And care? It\'s attention and contribution (what you give, what you do). It\'s kindness, patience, generosity, and truth. It\'s encouraging, apologetic, forgiving, and thankful.

					We\'ve let too much get between us (each other) and the reasons we\'re here. We\'ve allowed ourselves to slip into a state of busy distraction – seeking the complex instead of embracing the simple. It\'s time to stop going through the motions with our days (hours, minutes). We need to give more and we need to enjoy more.

					"How do I love my people?"

					  You know how.

					"But specifically. How?"

					START BY DOING YOUR WORK. Contribute. Help someone else by doing what it is you do (on the job or in life). Do it the way you wish everyone else would do it for you (golden rule stuff).

					  But I don\'t like my work, situation, company, boss, colleagues...

					Be a part of the solution or find someplace/thing else.

					  Easy for you to say. I have a family, mortgage, credit cards, responsibilities...

					Then be a part of the solution and start making where you are better (by the way ... everyone has many of those things too).

					  But I told you ... my boss, coworkers, customers, situation...

					I\'m sorry. You\'re a grown-up. Be a part of the solution or find someplace/thing else. Really.

					  It\'s not that simple.

					Yes ... it is.


					BE KIND. This means you\'re considerate (have concern and respect for others). It means you\'re pleasantly helpful and generous where you can be.

					  But what if someone/thing makes it difficult to be kind?

					Do your best to act in a way you\'d like others to act toward you when you make things difficult.

					  But, I don\'t make things difficult.

					We all do.

					  But...

					Me too.


					BE PATIENT. Approach people and situations calmly. Have a soothing and relaxed presence and give others the time you\'d like to be given.

					  But what if it\'s really urgent, like an emergency?

					Be less patient with emergencies.

					  What if I think it\'s more urgent than someone else thinks it is?

					Then it\'ll be more challenging to be patient.

					  What kind of an answer is that?

					Truth.


					(c) by Give More Media Inc. Please don\'t publish or distribute this sample. It\'s for evaluation purposes only.

					-------------

					Buy the booklet
					http://www.givemore.com/product/love-your-people-booklet/?utm_source=js-lyp-excerpt&utm_medium=email&utm_content=button+-+buy+the+booklet&utm_campaign=justsell+book+excerpt+request

					Watch the video
					http://www.givemore.com/love-your-people/?utm_source=js-lyp-excerpt&utm_medium=email&utm_content=button+-+watch+the+video&utm_campaign=justsell+book+excerpt+request

					-------------

					Also in the booklet: The additional 5 ways to Love Your People (be honest, encourage people, apologize, forgive, thank people), the simple approach to making it happen, a few inspiring quotes, and a \'Time to Think\' section with questions to help you (and your team) reflect on how to Love Your People.

					The entire booklet is a quick 5-minute read (which means it\'ll be read) that\'s now encouraging thousands of people, companies, and organizations to be more accountable to their work and the people around them.

					To watch the 3-minute video, buy the booklet, presentation material, or other fun Love Your People gear (posters, pens, pocket cards, etc.), please visit http://www.GiveMore.com/Love or call us in Richmond, Virginia at 804-762-4500.

					Surround yourself with good people, thoughts, and ideas and more good things will happen ... for everyone.

				';
				break;

			case "sm":
				$book_title = 'Smile &amp; Move';
				$excerpt_email_snippet = 'Smile & Move by Sam Parker. Better results, better relationships, more opportunities ... that\'s the goal. (Is anything more important?) Below is a 4-minute sample of the message that\'s helping thousands of leaders and organizations focus on what\'s most important (and often missed) in creating a special team of people that does great work (by the bestselling author of 212 the extra degree).';
				$excerpt_subject_line = 'Smile & Move (sample)';
				$excerpt_html_content = '
					<!-- Book Header -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						<tr>
							<td style="line-height:0px;">
								<a href="http://www.givemore.com/smile-and-move/?utm_source=js-sm-excerpt&utm_medium=email&utm_content=header+-+book+image&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-sm-excerpt-1-302x370.jpg" alt="Smile & Move" width="302" height="370" border="0" /></a><a href="http://www.givemore.com/product/smile-and-move-book-with-sticker-set/?utm_source=js-sm-excerpt&utm_medium=email&utm_content=header+-+buy+the+book&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-sm-excerpt-2-153x370.jpg" alt="Smile & Move" width="153" height="370" border="0" /></a><a href="http://www.givemore.com/smile-and-move/?utm_source=js-sm-excerpt&utm_medium=email&utm_content=header+-+watch+the+video&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-sm-excerpt-3-167x370.jpg" alt="Smile & Move" width="167" height="370" border="0" /></a><a href="http://www.givemore.com/smile-and-move/?utm_source=js-sm-excerpt&utm_medium=email&utm_content=header+-+final+piece&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-sm-excerpt-4-128x370.jpg" alt="Smile & Move" width="128" height="370" border="0" /></a>
							</td>
						</tr>
					</table> <!-- END Book Header -->

					<!-- Excerpt Content -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						
						<!-- Spacer -->
						<tr><td height="40">&nbsp;</td></tr>
						
						<!-- Overview Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										Overview
									</p>
										
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left;">Below is a 4-minute sample of the message that\'s <strong>helping thousands of leaders and organizations</strong> focus on what\'s most important (and often missed) in creating a special team of people that does great work (by the bestselling author of 212&deg; the extra degree&reg;).</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left;">Maybe it\'s the spark that can <strong>help you</strong> create the deeper awareness that inspires real (and wonderful) change.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:left;">Hand it out. Read it as a team. Discuss it. Encourage.</p>								
								</td></tr>
							</table>
						</td></tr>
					
						<!-- Line Spacer -->
						<tr><td height="50" style="border-bottom:1px solid #D7D7D7;">&nbsp;</td></tr>
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Excerpt Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										Book Excerpt
									</p>
																	
									<table width="600" border="0" cellspacing="0" cellpadding="0">
										<tr>					
											<td valign="top">
												<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:bold; line-height:21px; margin-top:0; margin-bottom:0.4em; text-align:left;">WE SMILE BY...</p>
												
												<ul style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
													<li>Being awake.</li>
													<li>Being thankful.</li>
													<li>Being approachable.</li>
													<li>Complaining less.</li>
													<li>Smiling really.</li>
												</ul>
											</td>
											
											<td valign="top">
												<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:bold; line-height:21px; margin-top:0; margin-bottom:0.4em; text-align:left;">WE MOVE BY...</p>
												
												<ul style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
													<li>Starting early and going long.</li>
													<li>Going beyond expectations.</li>
													<li>Having a sense of urgency.</li>
													<li>Being resourceful &amp; resilient (with no excuses).</li>
												</ul>
											</td>
										</tr>
									</table>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:14px; font-style:italic; font-weight:300; line-height:22px; margin-top:0; margin-bottom:2em; text-align:left;">In the book (a 20-minute read), each of these 9 ways to Smile &amp; Move are discussed with a short essay, a supportive fact or two, an inspiring quote, and some ideas to put it into action.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:bold; line-height:30px; margin-top:0; margin-bottom:0.8em; text-align:left;">Here are two of the author\'s favorite ways<br />to Smile &amp; Move...</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">1. WE SMILE BY ... Being approachable</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">We all have our &quot;customers.&quot; And most of us have more than one customer group.</p>
									
									<ul style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										<li>A teacher serves his students as well as his school administrators (and perhaps<br />even parents).</li>
										<li>An administrator serves her teachers in addition to a group of county administrators.</li>
										<li>A doctor serves his patients as well as his practice partners and perhaps a hospital staff.</li>
										<li>A CEO serves her stockholders as well as her management team and outside clients.</li>
										<li>It\'s an interdependent world.</li>
									</ul>
									                                                                                 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">We depend on each other and as a result, we need to make ourselves accessible and approachable to our people: customers, constituents, family, friends, colleagues, patients, leaders. We\'re not islands and can\'t expect to succeed on any significant level without connecting with people.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Being approachable is being receptive to occasional interruptions, making ourselves regularly accessible to others, and doing it with a smile... a smile not only with our mouths and eyes but an internal and authentic smile that can be felt by those with whom we interact.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">If we create a situation where people feel the need to walk on eggshells when they come to us (asking for help, guidance, or a solution), eventually they\'ll stop walking on eggshells and go to someone else. If that happens, whether we\'re a leader or a follower, our value to others is gone. And when we\'re shopping for something and the store no longer provides the value we\'re looking for, what happens? We go somewhere else.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:2em; text-align:left;">Be approachable.</p>

									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Smile &amp; Move thought...</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">When asked to do something for someone else (friend, family member, colleague, customer) does your YES say &quot;yes, if I have to&quot; or &quot;yes, it would be my pleasure&quot;?</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:3em; text-align:left;">How patient are you with the first from someone else?</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">2. WE MOVE BY ... Being resourceful &amp; resilient (with no excuses)</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">We want some thing or some result.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">That\'s all.</p>
									                                               
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">When we visit a store, we want the thing. When we call for help, we want the result.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">If we\'re lucky enough to be in the path of the want, we need to be resourceful in our attempts to make it (whatever it is) happen. When we fall short or fail (which we can\'t always prevent), we need to be resilient.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Our lack of this resource, the timing of that delivery, the weather, our lack of sleep, tough day, week, month, or year doesn\'t excuse us from delivering. It just makes it more challenging.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Moving (in the Smile & Move sense) is to move forward &ndash; refusing to complain or make excuses. It\'s remembering that service is about results.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:21px; margin-top:0; margin-bottom:2em; text-align:left;">Be happy. Do something.</p>
									
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Smile &amp; Move thought...</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.8em; text-align:left;">How many excuses do you allow yourself to give others when you don\'t deliver? How many do you enjoy hearing from others?</p>
									
									
									<table width="400" border="0" cellspacing="0" cellpadding="10" style="border-left:4px solid #F0F0F0; margin-left:50px; margin-bottom:30px;">
										<tr><td>
											<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:22px; margin-top:0; margin-bottom:1em; text-align:left;">Difficulty is the excuse history never accepts.</p>
											<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:14px; font-weight:300; line-height:18px; margin-top:0; margin-bottom:0; text-align:left;">Edward R. Murrow (1908-1965)<br />American broadcast journalist</p>
										</td></tr>
									</table>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Another Smile &amp; Move thought...</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">How can we help one another create and contribute more value each day?</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">What if we started by eliminating the TGIF sentiment from our workplaces and schools? What if Fridays became another day of opportunity for service and contribution? What if we pushed it further and stopped perpetuating the idea that Monday is a day of drudgery?</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:2em; text-align:left;">Together, Monday and Friday are 40% of a workweek. Imagine the impact if we could get these days back for all those who tend to lose them.</p>

								</td></tr>
							</table>
						</td></tr> <!-- END Excerpt Copy-->

						<!-- Spacer -->
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Social Media Gray Box -->
						<tr><td align="center">
							<table align="center" bgcolor="#F2F2F2" border="0" cellpadding="20" cellspacing="0" style="margin:0 auto;" width="425">
								<tr><td align="center">
									<font face="helvetica, arial, sans-serif" style="color:#656565; font-size:16px; font-weight:300; line-height:40px; text-align:center;">
										Like Smile &amp; Move? Share this with others.<br />
									</font>
									<a href="https://www.facebook.com/sharer/sharer.php?u=http://www.givemore.com/smile-and-move/" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/facebook-30x30.png" alt="Facebook" width="30" height="30" border="0" /></a>
									<a href="http://twitter.com/?status=Love+this+for+motivation+from+GiveMore.com...+http://www.givemore.com/smile-and-move/+@Give_More" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/twitter-30x30.png" alt="twitter" width="30" height="30" border="0" /></a>
									<a href="https://plus.google.com/share?url=http://www.givemore.com/smile-and-move/" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/google-plus-30x30.png" alt="Google Plus" width="30" height="30" border="0" /></a>
									<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=http://www.givemore.com/smile-and-move/"><img src="http://www.givemore.com/images/email/social-media/linkedin-30x30.png" alt="LinkedIn" width="30" height="30" border="0" /></a>
								</td></tr>
							</table>
						</td></tr>
						

						<!-- Line Spacer -->
						<tr><td height="50" style="border-bottom:1px solid #D7D7D7;">&nbsp;</td></tr>
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Complete Book Includes Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										The complete book includes
									</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:2em; text-align:left;">7 additional short essays covering the other ways to Smile &amp; Move, several reinforcement facts, ideas, and inspiring quotations in addition to many specific ways to &quot;Smove.&quot; Also, it opens with how the Smile &amp; Move message came about and closes with a few personal thoughts from the author.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.5em; text-align:left;"><strong>The entire book is a quick 20-minute read</strong> (which means it\'ll be read) that\'s now encouraging tens of thousands of people, companies, and organizations to be more accountable to their work and the people around them. It\'s 4 x 6 inches in size (made to fit any pocket) and just under 4,000 words.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.5em; text-align:left;"><strong>To watch the 3-minute video, buy the book, presentation material,</strong> or other fun Smile &amp; Move gear (posters, pens, pocket cards, etc.), please visit <a href="http://www.givemore.com/smile-and-move/?utm_source=js-sm-excerpt&utm_medium=email&utm_content=text+-+givemore+smile+2&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3;">http://www.GiveMore.com/Smile</a> or call us in Richmond, Virginia at 804-762-4500.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:left;"><strong>Surround yourself with good people, thoughts, and ideas</strong> and more good things will happen ... for everyone.</p>
								</td></tr>
							</table>
						</td></tr>

						<!-- Spacer -->
						<tr><td height="50">&nbsp;</td></tr>

					</table> <!-- END Excerpt Content -->
					
					<!-- Copyright -->
					<table align="center" bgcolor="#F2F2F2" border="0" cellpadding="20" cellspacing="0" style="margin:0 auto;" width="750">
						<tr><td align="center">
							<font face="helvetica, arial, sans-serif" style="color:#656565; font-size:12px; font-weight:300; line-height:15px; text-align:center;">
								&copy; by Give More Media Inc. Please don\'t publish or distribute this sample. It\'s for evaluation purposes only.
							</font>
						</td></tr>
					</table> <!-- END Copyright -->
					
					<!-- Buy Book & Watch Video Footer -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						<tr><td style="line-height:0px;">
							<a href="http://www.givemore.com/product/smile-and-move-book-with-sticker-set/?utm_source=js-sm-excerpt&utm_medium=email&utm_content=footer+-+buy+the+book&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/footer-sm-excerpt-1-338x375.jpg" alt="Smile & Move" width="338" height="375" border="0" /></a><a href="http://www.givemore.com/smile-and-move/?utm_source=js-sm-excerpt&utm_medium=email&utm_content=footer+-+watch+the+video&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/footer-sm-excerpt-2-412x375.jpg" alt="Smile & Move" width="412" height="375" border="0" /></a>
						</td></tr>
					</table> <!-- END Buy Book & Watch Video Footer -->
				';
				$excerpt_text_content = '
					"Better results, better relationships, more opportunities ... that\'s the goal."
					(Is anything more important?)

					Below is a 4-minute sample of the message that\'s helping thousands of leaders and organizations focus on what\'s most important (and often missed) in creating a special team of people that does great work (by the bestselling author of 212 the extra degree(r)).

					Maybe it\'s the spark that can help you create the deeper awareness that inspires real (and wonderful) change.

					Hand it out. Read it as a team. Discuss it. Encourage.

					-------------

					Smile & Move
					by Sam Parker

					(To buy the book or watch the 3-minute video, please visit http://www.GiveMore.com/Smile.)


					WE SMILE BY...

					  - Being awake.
					  - Being thankful.
					  - Being approachable.
					  - Complaining less.
					  - Smiling really.


					WE MOVE BY...

					  - Starting early and going long.
					  - Going beyond expectations.
					  - Having a sense of urgency.
					  - Being resourceful & resilient (with no excuses).
					  
					In the book (a 20-minute read), each of these 9 ways to Smile & Move are discussed with a short essay, a supportive fact or two, an inspiring quote, and some ideas to put it into action.


					Here are two of the author\'s favorite ways to Smile & Move...

					WE SMILE BY ... Being approachable

					WE ALL HAVE OUR "CUSTOMERS." And most of us have more than one customer group.

					  - A teacher serves his students as well as his school administrators (and perhaps even parents).

					  - An administrator serves her teachers in addition to a group of county administrators.

					  - A doctor serves his patients as well as his practice partners and perhaps a hospital staff.

					  - A CEO serves her stockholders as well as her management team and outside clients.

					  - It\'s an interdependent world.

					  - We depend on each other and as a result, we need to make ourselves accessible and approachable to our people: customers, constituents, family, friends, colleagues, patients, leaders. We\'re not islands and can\'t expect to succeed on any significant level without connecting with people.

					Being approachable is being receptive to occasional interruptions, making ourselves regularly accessible to others, and doing it with a smile... a smile not only with our mouths and eyes but an internal and authentic smile that can be felt by those with whom we interact.

					If we create a situation where people feel the need to walk on eggshells when they come to us (asking for help, guidance, or a solution), eventually they\'ll stop walking on eggshells and go to someone else. If that happens, whether we\'re a leader or a follower, our value to others is gone. And when we\'re shopping for something and the store no longer provides the value we\'re looking for, what happens? We go somewhere else.


					BE APPROACHABLE.

					Smile & Move thought...

					When asked to do something for someone else (friend, family member, colleague, customer) does your YES say "yes, if I have to" or "yes, it would be my pleasure"?

					How patient are you with the first from someone else?

					WE MOVE BY ... Being resourceful & resilient (with no excuses)

					WE WANT SOME THING or some result.

					That\'s all.

					When we visit a store, we want the thing. When we call for help, we want the result.

					If we\'re lucky enough to be in the path of the want, we need to be resourceful in our attempts to make it (whatever it is) happen. When we fall short or fail (which we can\'t always prevent), we need to be resilient.

					Our lack of this resource, the timing of that delivery, the weather, our lack of sleep, tough day, week, month, or year doesn\'t excuse us from delivering. It just makes it more challenging.

					Moving (in the Smile & Move sense) is to move forward – refusing to complain or make excuses. It\'s remembering that service is about results.



					BE HAPPY. DO SOMETHING.

					Smile & Move thought...

					How many excuses do you allow yourself to give others when you don\'t deliver? How many do you enjoy hearing from others?

					  Difficulty is the excuse history never accepts.

					  Edward R. Murrow (1908-1965)
					  American broadcast journalist


					Another Smile & Move thought...

					How can we help one another create and contribute more value each day?

					What if we started by eliminating the TGIF sentiment from our workplaces and schools? What if Fridays became another day of opportunity for service and contribution? What if we pushed it further and stopped perpetuating the idea that Monday is a day of drudgery?

					Together, Monday and Friday are 40% of a workweek. Imagine the impact if we could get these days back for all those who tend to lose them.

					(c) by Give More Media Inc. Please don\'t publish or distribute this sample. It\'s for evaluation purposes only.

					-------------

					Buy the Book
					http://www.givemore.com/product/smile-and-move-book-with-sticker-set/?utm_source=js-sm-excerpt&utm_medium=email&utm_content=button+-+buy+the+book&utm_campaign=justsell+book+excerpt+request

					Watch the Video
					http://www.givemore.com/smile-and-move/?utm_source=js-sm-excerpt&utm_medium=email&utm_content=button+-+watch+the+video&utm_campaign=justsell+book+excerpt+request

					-------------

					The complete book includes: 7 additional short essays covering the other ways to Smile & Move, several reinforcement facts, ideas, and inspiring quotations in addition to many specific ways to "Smove." Also, it opens with how the Smile & Move message came about and closes with a few personal thoughts from the author.

					The entire book is a quick 20-minute read (which means it\'ll be read) that\'s now encouraging tens of thousands of people, companies, and organizations to be more accountable to their work and the people around them. It\'s 4 x 6 inches in size (made to fit any pocket) and just under 4,000 words.

					To watch the 3-minute video, buy the book, presentation material, or other fun Smile & Move gear (posters, pens, pocket cards, etc.), please visit http://www.GiveMore.com/Smile or call us in Richmond, Virginia at 804-762-4500.

					Surround yourself with good people, thoughts, and ideas and more good things will happen ... for everyone.
				';
				break;

			case "st":
				$book_title = 'SalesTough';
				$excerpt_email_snippet = 'SalesTough by Sam Parker. Better results, better relationships, more opportunities ... that\'s the goal. (Is anything more important?) Below is a 2.5-minute sample of the message that\'s helping thousands of salespeople and organizations focus on what\'s most important to creating better sales results (co-authored by the founders of JustSell.com).';
				$excerpt_subject_line = 'SalesTough (sample)';
				$excerpt_html_content = '
					<!-- Book Header -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						<tr>
							<td style="line-height:0px;">
								<a href="http://www.givemore.com/sales-tough/?utm_source=js-st-excerpt&utm_medium=email&utm_content=header+-+book+image&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-st-excerpt-1-302x370.jpg" alt="SalesTough" width="302" height="370" border="0" /></a><a href="http://www.givemore.com/product/salestough-book/?utm_source=js-st-excerpt&utm_medium=email&utm_content=header+-+buy+the+book&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-st-excerpt-2-153x370.jpg" alt="SalesTough" width="153" height="370" border="0" /></a><a href="http://www.givemore.com/sales-tough/?utm_source=js-st-excerpt&utm_medium=email&utm_content=header+-+see+packages&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-st-excerpt-3-167x370.jpg" alt="SalesTough" width="167" height="370" border="0" /></a><a href="http://www.givemore.com/sales-tough/?utm_source=js-st-excerpt&utm_medium=email&utm_content=header+-+final+piece&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-st-excerpt-4-128x370.jpg" alt="SalesTough" width="128" height="370" border="0" /></a>
							</td>
						</tr>
					</table> <!-- END Book Header -->

					<!-- Excerpt Content -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						
						<!-- Spacer -->
						<tr><td height="40">&nbsp;</td></tr>
						
						<!-- Overview Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										Overview
									</p>
										
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left;">Below is a 2.5-minute sample of the message that\'s <strong>helping thousands of salespeople and organizations</strong> focus on what\'s most important to creating better sales results (co-authored by the founders of JustSell.com).</p>

									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left;">Maybe it\'s the spark that can <strong>help you</strong> create the deeper awareness that inspires real (and wonderful) change.</p>

									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:left;">Hand it out. Read it as a team. Discuss it. Encourage.</p>
										
								</td></tr>
							</table>
						</td></tr>
					
						<!-- Line Spacer -->
						<tr><td height="50" style="border-bottom:1px solid #D7D7D7;">&nbsp;</td></tr>
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Excerpt Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										Book Excerpt
									</p>
																	
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:bold; line-height:21px; margin-top:0; margin-bottom:0; text-align:left;">SalesTough</p>
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">salz-tuf, adj.</p>
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										<strong>01/</strong> Characterized by uncompromising determination<br />
										<strong>02/</strong> Resilient &amp; results-oriented<br />
										<strong>03/</strong> Relentlessly prepared, objective &amp; service minded
									</p>
					
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.5em; text-align:left;">Are you SalesTough? Here\'s where it begins...</p>
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:14px; font-style:italic; font-weight:300; line-height:22px; margin-top:0; margin-bottom:2em; text-align:left;">In the book (a 30-minute read at most), each of the 8 fundamentals to being SalesTough are discussed with a short essay, a supportive fact or two, an inspiring quote, and some ideas to put it into action. Below are 3 of the authors\' favorite points.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:bold; line-height:21px; margin-top:0; margin-bottom:0.8em; text-align:left;">NOTHING INTERRUPTS THE MONEY HOURS</p>				
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Let nothing interrupt your <strong>MONEY HOURS</strong> ... the hours in a salesday when one can talk with prospects and/or customers. (the most valuable hours in the day)</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;"><strong>TODAY</strong> is 20% of your salesweek.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Two salesdays are 10% of your month.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">To have only two slow days each month is equivalent to having more than one full month of slow days each year.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:14px; font-weight:bold; line-height:22px; margin-top:0; margin-bottom:0.8em; text-align:left;">SALES POINT...</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Every moment of every salesday matters. These are your <strong>MONEY HOURS</strong>. Hesitation for a better salesday of the week or a time when you\'re feeling more up to the task will have a long-term effect on your ultimate sales results (and discipline).</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;"><strong>IT\'S THIS SERIOUS.</strong> Every salesday is a salesday, regardless of circumstances.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Once it\'s gone, it\'s gone forever.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Over the next few weeks, begin your quest for <strong>COMPLETE SALESTIME DISCIPLINE</strong> regardless of environment ... regardless of circumstances. Put the &quot;Do Not Disturb&quot; button on your money hours and on your sales discipline.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.8em; text-align:left;">Time management is simple. Do what it is you know <strong>MUST BE DONE.</strong></p>
					
					 				<table width="400" border="0" cellspacing="0" cellpadding="10" style="border-left:4px solid #F0F0F0; margin-left:50px; margin-bottom:30px;">
										<tr><td>
											<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:22px; margin-top:0; margin-bottom:1em; text-align:left;">DEFER NO TIME, delays have dangerous ends.</p>
											<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:14px; font-weight:300; line-height:18px; margin-top:0; margin-bottom:0; text-align:left;">WILLIAM SHAKESPEARE (1564-1616)<br />English playwright &amp; poet</p>
										</td></tr>
									</table>
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:bold; line-height:22px; margin-top:0; margin-bottom:0.8em; text-align:left;">OPEN STRONG</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Excite your prospects and customers with <strong>STRONG OPENING STATEMENTS</strong> that mean something.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Nothing is more important to prospecting (other than actually doing it) than the quality of your opening statement.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">In person or over the phone, windows of attention are typically slammed shut in less than 10 seconds. This means you have to maximize the impact of every word, syllable, and pause in your lead-off statements. They need to be works of art &ndash; <strong>COMPELLING</strong> to the extreme degree. To minimize the importance of preparing a solid opening statement is to potentially short circuit your entire sales effort.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Fortunately, opening statements can be prepared and practiced <strong>BEFORE</strong> a sales opportunity is ever pursued.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.8em; text-align:left;"><strong>REMEMBER,</strong> it\'s very likely the people you\'re trying to reach are also being approached by many others each day (competitors or not). A powerful, compelling, and practiced opening statement can launch many sales opportunities for you, where a competitor\'s lack of preparation can leave them standing still.</p>
					 
					 				<table width="400" border="0" cellspacing="0" cellpadding="10" style="border-left:4px solid #F0F0F0; margin-left:50px; margin-bottom:30px;">
										<tr><td>
											<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:22px; margin-top:0; margin-bottom:1em; text-align:left;">Life all comes down to a few moments.<br />This is ONE OF THEM.</p>
											<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:14px; font-weight:300; line-height:18px; margin-top:0; margin-bottom:0; text-align:left;">BUD FOX<br />Stockbroker from the film &quot;Wall Street&quot; (1987)</p>
										</td></tr>
									</table>
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:bold; line-height:21px; margin-top:0; margin-bottom:0.8em; text-align:left;">SOLVE PROBLEMS, DON\'T SHARE THEM</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Keep any complaints or problems <strong>TO YOURSELF (ESPECIALLY</strong> when you\'re with prospects or customers).</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Be careful to keep your personal challenges to yourself during the salesday.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Eliminate anything that can potentially distract your customers and prospects from the benefits of your product or service (or from building a positive business relationship with you).</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">This should be fairly easy, unless your prospects or customers begin sharing their personal challenges with you. At that point, you might have a tendency to join in. Remember to do what you can to minimize it. Empathize. Care about their situation. Help if you feel it\'s appropriate, but avoid matching your personal challenges with theirs.</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">During <strong>SALESTIME</strong>, you have no problems &ndash; you solve them (and you love what you do).</p>
					 
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.8em; text-align:left;">Remember ... it\'s all about them.</p>
					 
									<table width="400" border="0" cellspacing="0" cellpadding="10" style="border-left:4px solid #F0F0F0; margin-left:50px; margin-bottom:30px;">
										<tr><td>
											<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:22px; margin-top:0; margin-bottom:1em; text-align:left;">When it\'s not always raining, there\'ll be days like this / When there\'s NO ONE COMPLAINING, there\'ll be days like this.</p>
											<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:14px; font-weight:300; line-height:18px; margin-top:0; margin-bottom:0; text-align:left;">VAN MORRISON (1945 - )<br />Irish-American music artist</p>
										</td></tr>
									</table>
										
								</td></tr>
							</table>
						</td></tr> <!-- END Excerpt Copy-->

						<!-- Spacer -->
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Social Media Gray Box -->
						<tr><td align="center">
							<table align="center" bgcolor="#F2F2F2" border="0" cellpadding="20" cellspacing="0" style="margin:0 auto;" width="425">
								<tr><td align="center">
									<font face="helvetica, arial, sans-serif" style="color:#656565; font-size:16px; font-weight:300; line-height:40px; text-align:center;">
										Like SalesTough? Share this with others.<br />
									</font>
									<a href="https://www.facebook.com/sharer/sharer.php?u=http://www.givemore.com/sales-tough/" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/facebook-30x30.png" alt="Facebook" width="30" height="30" border="0" /></a>
									<a href="http://twitter.com/?status=Love+this+for+motivation+from+GiveMore.com...+http://www.givemore.com/sales-tough/+@Give_More" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/twitter-30x30.png" alt="twitter" width="30" height="30" border="0" /></a>
									<a href="https://plus.google.com/share?url=http://www.givemore.com/sales-tough/" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/google-plus-30x30.png" alt="Google Plus" width="30" height="30" border="0" /></a>
									<a href="http://www.linkedin.com/shareArticle?mini=true&url=http://www.givemore.com/sales-tough/"><img src="http://www.givemore.com/images/email/social-media/linkedin-30x30.png" alt="LinkedIn" width="30" height="30" border="0" /></a>
								</td></tr>
							</table>
						</td></tr>
						

						<!-- Line Spacer -->
						<tr><td height="50" style="border-bottom:1px solid #D7D7D7;">&nbsp;</td></tr>
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Complete Book Includes Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										The complete book includes
									</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:2em; text-align:left;">The 5 additional ways to be SalesTough, several inspiring and motivational quotations, a section of sales tools (a guide to creating great opening statements, a guide for better networking, a closing checklist, and the top 30 open-ended questions for sales), and the classic 1500-word essay on initiative and responsibility (A Message to Garcia ... one of our favorites).</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.5em; text-align:left;"><strong>The entire book is a quick 30-minute read</strong> (which means it\'ll be read) that\'s now encouraging thousands of salespeople and organizations to be more valuable to their prospects, customers, and company. It\'s 4 x 6 inches in size (made to fit any pocket) and under 100 easy-to-read pages.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.5em; text-align:left;"><strong>To buy the book, presentation material,</strong> or other fun SalesTough gear (posters, pens, pocket cards, etc.), please visit <a href="http://www.givemore.com/sales-tough/?utm_source=js-st-excerpt&utm_medium=email&utm_content=text+-+givemore+salestough+2&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3;">http://www.GiveMore.com/SalesTough</a> or call us in Richmond, Virginia at 804-762-4500.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:left;"><strong>Surround yourself with good people, thoughts, and ideas</strong> and more good things will happen ... for everyone.</p>
								</td></tr>
							</table>
						</td></tr>

						<!-- Spacer -->
						<tr><td height="50">&nbsp;</td></tr>

					</table> <!-- END Excerpt Content -->
					
					<!-- Copyright -->
					<table align="center" bgcolor="#F2F2F2" border="0" cellpadding="20" cellspacing="0" style="margin:0 auto;" width="750">
						<tr><td align="center">
							<font face="helvetica, arial, sans-serif" style="color:#656565; font-size:12px; font-weight:300; line-height:15px; text-align:center;">
								&copy; by Give More Media Inc. Please don\'t publish or distribute this sample. It\'s for evaluation purposes only.
							</font>
						</td></tr>
					</table> <!-- END Copyright -->
					
					<!-- Buy Book & Watch Video Footer -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						<tr><td style="line-height:0px;">
							<a href="http://www.givemore.com/product/salestough-book/?utm_source=js-st-excerpt&utm_medium=email&utm_content=footer+-+buy+the+book&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/footer-st-excerpt-750x375.jpg" alt="SalesTough" width="750" height="375" border="0" /></a>
						</td></tr>
					</table> <!-- END Buy Book & Watch Video Footer -->
				';
				$excerpt_text_content = '
					"Better results, better relationships, more opportunities ... that\'s the goal."
					(Is anything more important?)

					Below is a 2.5-minute sample of the message that\'s helping thousands of salespeople and organizations focus on what\'s most important to creating better sales results (co-authored by the founders of JustSell.com).

					Maybe it\'s the spark that can help you create the deeper awareness that inspires real (and wonderful) change.

					Hand it out. Read it as a team. Discuss it. Encourage.

					-------------

					SalesTough
					by Sam Parker & Jim Gould

					(To buy the book, please visit http://www.GiveMore.com/SalesTough.)


					SalesTough
					salz-tuf, adj.

					01/ Characterized by uncompromising determination
					02/ Resilient & results-oriented
					03/ Relentlessly prepared, objective & service minded

					Are you SalesTough? Here\'s where it begins...

					In the book (a 30-minute read at most), each of the 8 fundamentals to being SalesTough are discussed with a short essay, a supportive fact or two, an inspiring quote, and some ideas to put it into action. Below are 3 of the authors\' favorite points.


					NOTHING INTERRUPTS THE MONEY HOURS

					Let nothing interrupt your MONEY HOURS ... the hours in a salesday when one can talk with prospects and/or customers. (the most valuable hours in the day)

					TODAY is 20% of your salesweek.

					Two salesdays are 10% of your month.

					To have only two slow days each month is equivalent to having more than one full month of slow days each year.

					SALES POINT...

					Every moment of every salesday matters. These are your MONEY HOURS. Hesitation for a better salesday of the week or a time when you\'re feeling more up to the task will have a long-term effect on your ultimate sales results (and discipline).

					IT\'S THIS SERIOUS. Every salesday is a salesday, regardless of circumstances.

					Once it\'s gone, it\'s gone forever.

					Over the next few weeks, begin your quest for COMPLETE SALESTIME DISCIPLINE regardless of environment ... regardless of circumstances. Put the "Do Not Disturb" button on your money hours and on your sales discipline.

					Time management is simple. Do what it is you know MUST BE DONE.

					  DEFER NO TIME, delays have dangerous ends.

					  WILLIAM SHAKESPEARE (1564-1616)
					  English playwright & poet


					OPEN STRONG

					Excite your prospects and customers with STRONG OPENING STATEMENTS that mean something.

					Nothing is more important to prospecting (other than actually doing it) than the quality of your opening statement.

					In person or over the phone, windows of attention are typically slammed shut in less than 10 seconds. This means you have to maximize the impact of every word, syllable, and pause in your lead-off statements. They need to be works of art – COMPELLING to the extreme degree. To minimize the importance of preparing a solid opening statement is to potentially short circuit your entire sales effort.

					Fortunately, opening statements can be prepared and practiced BEFORE a sales opportunity is ever pursued.

					REMEMBER, it\'s very likely the people you\'re trying to reach are also being approached by many others each day (competitors or not). A powerful, compelling, and practiced opening statement can launch many sales opportunities for you, where a competitor\'s lack of preparation can leave them standing still.

					  Life all comes down to a few moments. This is ONE OF THEM.

					  BUD FOX
					  Stockbroker from the film "Wall Street" (1987)


					SOLVE PROBLEMS, DON\'T SHARE THEM

					Keep any complaints or problems TO YOURSELF (ESPECIALLY when you\'re with prospects or customers).

					Be careful to keep your personal challenges to yourself during the salesday.

					Eliminate anything that can potentially distract your customers and prospects from the benefits of your product or service (or from building a positive business relationship with you).

					This should be fairly easy, unless your prospects or customers begin sharing their personal challenges with you. At that point, you might have a tendency to join in. Remember to do what you can to minimize it. Empathize. Care about their situation. Help if you feel it\'s appropriate, but avoid matching your personal challenges with theirs.

					During SALESTIME, you have no problems – you solve them (and you love what you do).

					Remember ... it\'s all about them.

					  When it\'s not always raining, there\'ll be days like this / When there\'s NO ONE COMPLAINING, there\'ll be days like this.

					  VAN MORRISON (1945 - )
					  Irish-American music artist


					(c) by Give More Media Inc. Please don\'t publish or distribute this sample. It\'s for evaluation purposes only.

					-------------

					Buy the Book
					http://www.givemore.com/product/salestough-book/?utm_source=js-st-excerpt&utm_medium=email&utm_content=button+-+buy+the+book&utm_campaign=justsell+book+excerpt+request

					-------------

					The complete book includes: The 5 additional ways to be SalesTough, several inspiring and motivational quotations, a section of sales tools (a guide to creating great opening statements, a guide for better networking, a closing checklist, and the top 30 open-ended questions for sales), and the classic 1500-word essay on initiative and responsibility (A Message to Garcia ... one of our favorites).

					The entire book is a quick 30-minute read (which means it\'ll be read) that\'s now encouraging thousands of salespeople and organizations to be more valuable to their prospects, customers, and company. It\'s 4 x 6 inches in size (made to fit any pocket) and under 100 easy-to-read pages.

					To buy the book, presentation material, or other fun SalesTough gear (posters, pens, pocket cards, etc.), please visit http://www.GiveMore.com/SalesTough or call us in Richmond, Virginia at 804-762-4500.

					Surround yourself with good people, thoughts, and ideas and more good things will happen ... for everyone.
				';
				break;

			default:
				$book_title = '212&deg; the extra degree';
				$excerpt_email_snippet = '212 the extra degree by Sam Parker. Better results, better relationships, more opportunities ... that\'s the goal. (is anything more important?) Below is a 2-minute sample of the message that\'s helping hundreds of thousands of people and organizations embrace the value of the extra effort, care, and attention that goes into creating great results (by the bestselling author of Lead Simply and Smile & Move).';
				$excerpt_subject_line = '212 the extra degree (sample)';
				$excerpt_html_content = '
					<!-- Book Header -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						<tr>
							<td style="line-height:0px;">
								<a href="http://www.givemore.com/212-the-extra-degree/?utm_source=js-tt-excerpt&utm_medium=email&utm_content=header+-+book+image&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-212-excerpt-1-302x370.jpg" alt="212 the extra degree book" width="302" height="370" border="0" /></a><a href="http://www.givemore.com/product/212-the-extra-degree-book-and-sticker-set/?utm_source=js-tt-excerpt&utm_medium=email&utm_content=header+-+buy+the+book&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-212-excerpt-2-153x370.jpg" alt="Buy the book" width="153" height="370" border="0" /></a><a href="http://www.givemore.com/212-the-extra-degree/?utm_source=js-tt-excerpt&utm_medium=email&utm_content=header+-+watch+the+video&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-212-excerpt-3-167x370.jpg" alt="Watch the video" width="167" height="370" border="0" /></a><a href="http://www.givemore.com/212-the-extra-degree/?utm_source=js-tt-excerpt&utm_medium=email&utm_content=header+-+final+piece&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/header-212-excerpt-4-128x370.jpg" alt="212 the extra degree" width="128" height="370" border="0" /></a>
							</td>
						</tr>
					</table> <!-- END Book Header -->

					<!-- Excerpt Content -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						
						<!-- Spacer -->
						<tr><td height="40">&nbsp;</td></tr>
						
						<!-- Overview Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										Overview
									</p>
										
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left;">Below is a 2-minute sample of the message that\'s <strong>helping hundreds of thousands of people and organizations</strong> embrace the value of the extra effort, care, and attention that goes into creating great results (by the bestselling author of Lead Simply and Smile &amp; Move).</p>

									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1em; text-align:left;">Maybe it\'s the spark that can <strong>help you</strong> create the deeper awareness that inspires real (and wonderful) change.</p>

									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:left;">Hand it out. Read it as a team. Discuss it. Encourage.</p>
								</td></tr>
							</table>
						</td></tr>
					
						<!-- Line Spacer -->
						<tr><td height="50" style="border-bottom:1px solid #D7D7D7;">&nbsp;</td></tr>
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Excerpt Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										Book Excerpt
									</p>
																	
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:20px; font-weight:bold; line-height:21px; margin-top:0; margin-bottom:0.8em; text-align:left;">At 211 degrees, water is hot. At 212 degrees, it boils.</p>
					                                                                                      
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">And with boiling water, comes steam. And with steam, you can power a train.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">One degree.</p>
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Applying <strong>one extra degree</strong> of temperature to water means the difference between something that is simply very hot and something that generates enough force to power a machine &ndash; a beautifully uncomplicated metaphor that ideally should feed our every endeavor &ndash; consistently pushing us to give the extra effort in every task, action and effort we undertake. Two-twelve serves as a forceful drill sergeant with its motivating and focused message while adhering to a scientific law &ndash; a natural law. It reminds us that seemingly small things can make tremendous differences.</p>
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Two-twelve.</p>
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Still looking for the &quot;silver bullet&quot; or &quot;quick fix&quot; to achieving great results?</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Stop.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">You know there are no secrets to success. Success with anything, success in anything has one fundamental aspect &ndash; effort. To achieve exponential results requires additional effort. You know this (and if you didn\'t, you do now).</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.8em; text-align:left;">Thomas Edison said...</p>
					
									<table width="400" border="0" cellspacing="0" cellpadding="10" style="border-left:4px solid #F0F0F0; margin-left:50px; margin-bottom:30px;">
										<tr><td>
											<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:left;">Many of life\'s failures are men who did not realize how close they were to success when they gave up.</p>
										</td></tr>
									</table>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.8em; text-align:left;">Vince Lombardi tightened it up with...</p>
					
									<table width="400" border="0" cellspacing="0" cellpadding="10" style="border-left:4px solid #F0F0F0; margin-left:50px; margin-bottom:30px;">
										<tr><td>
											<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:left;">Inches make the champion.</p>
										</td></tr>
									</table>
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:bold; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">212 distills it even further.</p>
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">From 2001 &ndash; 2010 (10 years), The PGA Championship winner took home an average of $1,183,500. The second-place finisher averaged $513,860 ... less. The margin of difference ... 1.9 strokes. Less than a stroke a day.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">At NASCAR\'s Daytona 500 from 1996 &ndash; 2010 (15 years), the winner took the checkered flag by an average margin of 0.082 seconds ... less than a tenth of a second. For the winner it meant $459,445 ... more.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">How many opportunities have you missed because you were not <strong>aware</strong> of the possibilities that would occur if you applied a small amount of effort beyond what you normally do?</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Consider the impact of making an extra contact each day at work ... a sales call ... a customer follow-up ... a brief discussion with a colleague ... an encouraging talk with a member of your team. With contact comes opportunity. At the end of a year you\'ll have opened more than 200 additional doors of possibility.</p>
					
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">On the personal side, imagine the exponentially positive results of investing an additional 15 minutes of quality time each day with your children or spouse &ndash; an equivalent of more than two weeks each year at work.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">Now you\'re aware of 212&deg; the extra degree. No longer will you be able to do only what is required of you and only what is <strong>expected</strong> of you. Because with awareness comes responsibility &ndash; to yourself and others.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">And you are now aware.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:2em; text-align:left;">The excitement has begun. Are you smiling yet?</p>
										
								</td></tr>
							</table>
						</td></tr> <!-- END Excerpt Copy-->

						<!-- Spacer -->
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Social Media Gray Box -->
						<tr><td align="center">
							<table align="center" bgcolor="#F2F2F2" border="0" cellpadding="20" cellspacing="0" style="margin:0 auto;" width="425">
								<tr><td align="center">
									<font face="helvetica, arial, sans-serif" style="color:#656565; font-size:16px; font-weight:300; line-height:40px; text-align:center;">
										Like 212 the extra degree? Share this with others.<br />
									</font>
									<a href="https://www.facebook.com/sharer/sharer.php?u=http://www.givemore.com/212-the-extra-degree/" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/facebook-30x30.png" alt="Facebook" width="30" height="30" border="0" /></a>
									<a href="http://twitter.com/?status=Love+this+for+motivation+from+GiveMore.com...+http://www.givemore.com/212-the-extra-degree/+@Give_More" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/twitter-30x30.png" alt="twitter" width="30" height="30" border="0" /></a>
									<a href="https://plus.google.com/share?url=http://www.givemore.com/212-the-extra-degree/" style="margin-right:5px;"><img src="http://www.givemore.com/images/email/social-media/google-plus-30x30.png" alt="Google Plus" width="30" height="30" border="0" /></a>
									<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=http://www.givemore.com/212-the-extra-degree/"><img src="http://www.givemore.com/images/email/social-media/linkedin-30x30.png" alt="LinkedIn" width="30" height="30" border="0" /></a>
								</td></tr>
							</table>
						</td></tr>
						
						<!-- Line Spacer -->
						<tr><td height="50" style="border-bottom:1px solid #D7D7D7;">&nbsp;</td></tr>
						<tr><td height="50">&nbsp;</td></tr>

						<!-- Complete Book Includes Copy -->
						<tr><td>
							<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
								<tr><td>
									<p style="color:#E55151; font-family:helvetica, arial, sans-serif; font-size:24px; font-weight:300; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0.8em; text-align:left;">
										The complete book includes
									</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:2em; text-align:left;">More support for the 212 message, several reinforcement facts, ideas, and inspiring quotations in addition to many specific ways to &quot;Be 212&quot;... 7 additional &quot;reflection&quot; essays, too.</p>
								
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.5em; text-align:left;"><strong>The entire book is a quick 20-minute read</strong> (which means it\'ll be read) that\'s now encouraging hundreds of thousands of people, companies, and organizations to be more accountable to their work and the people around them.</p>										
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:1.5em; text-align:left;"><strong>To watch the 3.5-minute video, buy the book, presentation material,</strong> or other fun 212 gear (posters, pens, pocket cards, etc.), please visit <a href="http://www.givemore.com/212-the-extra-degree/?utm_source=js-tt-excerpt&utm_medium=email&utm_content=text_givemore+212+2&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3;">http://www.GiveMore.com/212</a> or call us in Richmond, Virginia at 804-762-4500.</p>
									
									<p style="color:#656565; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:left;"><strong>Surround yourself with good people, thoughts, and ideas</strong> and more good things will happen ... for everyone.</p>
								</td></tr>
							</table>
						</td></tr>

						<!-- Spacer -->
						<tr><td height="50">&nbsp;</td></tr>

					</table> <!-- END Excerpt Content -->
					
					<!-- Copyright -->
					<table align="center" bgcolor="#F2F2F2" border="0" cellpadding="20" cellspacing="0" style="margin:0 auto;" width="750">
						<tr><td align="center">
							<font face="helvetica, arial, sans-serif" style="color:#656565; font-size:12px; font-weight:300; line-height:15px; text-align:center;">
								&copy; by Give More Media Inc. Please don\'t publish or distribute this sample. It\'s for evaluation purposes only.
							</font>
						</td></tr>
					</table> <!-- END Copyright -->
					
					<!-- Buy Book & Watch Video Footer -->
					<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="750">
						<tr><td style="line-height:0px;">
							<a href="http://www.givemore.com/product/212-the-extra-degree-book-and-sticker-set/?utm_source=js-tt-excerpt&utm_medium=email&utm_content=footer+-+buy+the+book&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/footer-212-excerpt-1-338x375.jpg" alt="212 the extra degree" width="338" height="375" border="0" /></a><a href="http://www.givemore.com/212-the-extra-degree/?utm_source=js-tt-excerpt&utm_medium=email&utm_content=footer+-+watch+the+video&utm_campaign=justsell+book+excerpt+request" style="line-height:0px;"><img src="http://www.givemore.com/images/email/engaging/footer-212-excerpt-2-412x375.jpg" alt="212 the extra degree" width="412" height="375" border="0" /></a>
						</td></tr>
					</table> <!-- END Buy Book & Watch Video Footer -->
				';
				$excerpt_text_content = '
					"Better results, better relationships, more opportunities ... that\'s the goal."
					(Is anything more important?)

					Below is a 2-minute sample of the message that\'s helping hundreds of thousands of people and organizations embrace the value of the extra effort, care, and attention that goes into creating great results (by the bestselling author of Lead Simply and Smile & Move).

					Maybe it\'s the spark that can help you create the deeper awareness that inspires real (and wonderful) change.

					Hand it out. Read it as a team. Discuss it. Encourage.

					-------------

					212 the extra degree
					by Sam Parker

					(To buy the book or watch the 3-minute video, please visit http://www.GiveMore.com/212.)


					At 211 degrees, water is hot. At 212 degrees, it boils.

					And with boiling water, comes steam. And with steam, you can power a train.

					One degree.

					Applying one extra degree of temperature to water means the difference between something that is simply very hot and something that generates enough force to power a machine - a beautifully uncomplicated metaphor that ideally should feed our every endeavor - consistently pushing us to give the extra effort in every task, action and effort we undertake. Two-twelve serves as a forceful drill sergeant with its motivating and focused message while adhering to a scientific law - a natural law. It reminds us that seemingly small things can make tremendous differences.

					Two-twelve.

					Still looking for the "silver bullet" or "quick fix" to achieving great results?

					Stop.

					You know there are no secrets to success. Success with anything, success in anything has one fundamental aspect - effort. To achieve exponential results requires additional effort. You know this (and if you didn\'t, you do now).

					Thomas Edison said...

					  "Many of life\'s failures are men who did not realize how close they were to success when they gave up."


					Vince Lombardi tightened it up with...

					  "Inches make the champion."


					212 distills it even further.

					From 2001 - 2010 (10 years), The PGA Championship winner took home an average of $1,183,500. The second-place finisher averaged $513,860 ... less. The margin of difference ... 1.9 strokes. Less than a stroke a day.

					At NASCAR\'s Daytona 500 from 1996 - 2010 (15 years), the winner took the checkered flag by an average margin of 0.082 seconds ... less than a tenth of a second. For the winner it meant $459,445 ... more.

					How many opportunities have you missed because you were not aware of the possibilities that would occur if you applied a small amount of effort beyond what you normally do?

					Consider the impact of making an extra contact each day at work ... a sales call ... a customer follow-up ... a brief discussion with a colleague ... an encouraging talk with a member of your team. With contact comes opportunity. At the end of a year you\'ll have opened more than 200 additional doors of possibility.

					On the personal side, imagine the exponentially positive results of investing an additional 15 minutes of quality time each day with your children or spouse - an equivalent of more than two weeks each year at work.

					Now you\'re aware of 212 the extra degree. No longer will you be able to do only what is required of you and only what is expected of you. Because with awareness comes responsibility - to yourself and others.

					And you are now aware.

					The excitement has begun. Are you smiling yet?


					(c) by Give More Media Inc. Please don\'t publish or distribute this sample. It\'s for evaluation purposes only.

					-------------

					Buy the Book
					http://www.givemore.com/product/212-the-extra-degree-book-and-sticker-set/?utm_source=gm-tt-excerpt&utm_medium=email&utm_content=button+-+buy+the+book&utm_campaign=justsell+book+excerpt+request

					Watch the Video
					http://www.givemore.com/212-the-extra-degree/?utm_source=gm-tt-excerpt&utm_medium=email&utm_content=button+-+watch+the+video&utm_campaign=justsell+book+excerpt+request

					-------------

					The complete book includes: More support for the 212 message, several reinforcement facts, ideas, and inspiring quotations in addition to many specific ways to "Be 212" ... 7 additional "reflection" essays, too.

					The entire book is a quick 20-minute read (which means it\'ll be read) that\'s now encouraging hundreds of thousands of people, companies, and organizations to be more accountable to their work and the people around them.

					To watch the 3.5-minute video, buy the book, presentation material, or other fun 212 gear (posters, pens, pocket cards, etc.), please visit http://www.GiveMore.com/212 or call us in Richmond, Virginia at 804-762-4500.

					Surround yourself with good people, thoughts, and ideas and more good things will happen ... for everyone.

				';
				break;

		endswitch;

		/* Pass all book specific variables to the send_post_book_excerpt_email function. */
		return send_post_book_excerpt_email($book_excerpt_capture_id, $book_excerpt_request_email, $book_title, $excerpt_email_snippet, $excerpt_subject_line, $excerpt_html_content, $excerpt_text_content);
	}
} /* END function process_post_book_excerpt_request_form */



/*
 * Sends the actual book excerpt email.
 * @argument $book_title initiated in process_post_book_excerpt_request_form() and sent via function call.
 */
function send_post_book_excerpt_email($book_excerpt_capture_id, $book_excerpt_request_email, $book_title, $excerpt_email_snippet, $excerpt_subject_line, $excerpt_html_content, $excerpt_text_content)
{

	/* [ Imports the necessary scripts to control MIME being sent. Use 'find . -name swift_required.php' to find location via ssh ] */
 require_once '/etc/apache2/sites-available/vendor/swiftmailer/swiftmailer/lib/swift_required.php';
// Live Server		require_once '/usr/share/pear/swift_required.php';
	
	/* [ Sets the transport method to PHP Mail ] */
	$transport = Swift_MailTransport::newInstance();
		
	/* [ Create the Mailer using the created Transport ] */
	$mailer = Swift_Mailer::newInstance($transport);
	
	/* [ Create the message ] */
	$message = Swift_Message::newInstance($excerpt_subject_line)
	  ->setFrom(array('JustSell@JustSell.com' => 'JustSell.com'))
	  ->setTo(array($book_excerpt_request_email))
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
				<span style="color:#F2F2F2; font-size:0px;">'. $excerpt_email_snippet . '</span>

				<!-- Gray BG -->
				<div style="background:#F2F2F2; width:100%;"><table width="100%" border="0" cellspacing="0" bgcolor="#F2F2F2" cellpadding="0" align="center" style="background:#F2F2F2; width:100%;"><tr><td>

					<!-- White BG Wrapper -->
					<table width="750" border="0" cellspacing="0" bgcolor="#FFFFFF" cellpadding="0" align="center" style="margin:0 auto;"><tr><td>

						'. $excerpt_html_content .'

						<!-- Closing Content -->
						<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="500">

							<!-- Spacer -->
							<tr><td height="20">&nbsp;</td></tr>
							
							<!-- URL & Number -->
							<tr><td align="center">
								<p style="color:#666666; font-family:helvetica, arial, sans-serif; font-size:16px; font-weight:300; line-height:24px; margin-top:0; margin-bottom:0; text-align:center;">
									<a href="http://www.justsell.com/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=text+-+justsell-dot-com&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3;">www.JustSell.com</a><br />
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
											<a href="http://www.givemore.com/212-the-extra-degree/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+212+books+image&utm_campaign=justsell+book+excerpt+request"><img src="http://www.givemore.com/images/dedicateds/books/212-book-140x140.jpg" width="118" height="118" alt="212&deg; the extra degree&reg; Books" title="212&deg; the extra degree&reg; Books" border="0" /></a><br />

											<p style="color:#262626; font-family:helvetica, arial, sans-serif; font-size:12px; line-height:18px; margin-top:0; text-align:center;">
												Inspire a little extra effort and attention.<br />
												<a href="http://www.givemore.com/212-the-extra-degree/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+212+the+extra+degree&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3;">212&deg; the extra degree</a>
											</p>
										</td>

										<td align="center" width="130">
											<a href="http://www.givemore.com/smile-and-move/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+smile+and+move+books+image&utm_campaign=justsell+book+excerpt+request"><img src="http://www.givemore.com/images/dedicateds/books/sm-book-140x140.jpg" width="118" height="118" alt="Smile &amp; Move&reg; Books" title="Smile &amp; Move&reg; Books" border="0" /></a><br />

											<p style="color:#262626; font-family:helvetica, arial, sans-serif; font-size:12px; line-height:18px; margin-top:0; text-align:center;">
												Encourage better attitudes and service.<br />
												<a href="http://www.givemore.com/smile-and-move/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+smile+and+move&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3;">Smile &amp; Move</a>
											</p>
										</td>

										<td align="center" width="130">
											<a href="http://www.givemore.com/cross-the-line/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+cross+the+line+booklets+image&utm_campaign=justsell+book+excerpt+request"><img src="http://www.givemore.com/images/dedicateds/books/ctl-booklet-140x140.jpg" width="118" height="118" alt="Cross The Line&reg; Booklets" title="Cross The Line&reg; Booklets" border="0" /></a><br />

											<p style="color:#262626; font-family:helvetica, arial, sans-serif; font-size:12px; line-height:18px; margin-top:0; text-align:center;">
												Inspire commitment, effort, and resilience.<br />
												<a href="http://www.givemore.com/cross-the-line/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+cross+the+line&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3;">Cross The Line</a>
											</p>
										</td>

										<td align="center" width="130">
											<a href="http://www.givemore.com/love-your-people/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+love+your+people+booklets+image&utm_campaign=justsell+book+excerpt+request"><img src="http://www.givemore.com/images/dedicateds/books/lyp-booklet-140x140.jpg" width="118" height="118" alt="Love Your People&reg; Booklets" title="Love Your People&reg; Booklets" border="0" /></a><br />

											<p style="color:#262626; font-family:helvetica, arial, sans-serif; font-size:12px; line-height:18px; margin-top:0; text-align:center;">
												Encourage more trust and accountability.<br />
												<a href="http://www.givemore.com/love-your-people/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+love+your+people&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3;">Love Your People</a>
											</p>
										</td>

										<td align="center" width="136">
											<a href="http://www.givemore.com/lead-simply/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+lead+simply+books+image&utm_campaign=justsell+book+excerpt+request"><img src="http://www.givemore.com/images/dedicateds/books/ls-book-140x140.jpg" width="118" height="118" alt="Lead Simply&trade; Books" title="Lead Simply&trade; Books" border="0" /></a><br />

											<p style="color:#262626; font-family:helvetica, arial, sans-serif; font-size:12px; line-height:18px; margin-top:0; text-align:center;">
												No fluff. No parables.<br />No matrixes. Just truth.<br />
												<a href="http://www.givemore.com/lead-simply/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+lead+simply&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3;">Lead Simply</a>
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
											<a href="http://www.givemore.com/speaking/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+sam+headshot+image&utm_campaign=justsell+book+excerpt+request"><img src="http://www.givemore.com/images/email/icons/speaking-sam-headshot-150x150.jpg" alt="Sam Parker Headshot" width="150" height="150" border="0" /></a>
										</td>

										<!-- Spacer -->
										<td width="30">&nbsp;</td>

										<td valign="middle" width="310">
											<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:30px; font-weight:300; line-height:35px; margin-bottom:0; margin-top:0; text-align:left;">
												<a href="http://www.givemore.com/speaking/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+learn+about+sam&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3; text-decoration:none;">+ Learn about Sam</a><br />
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
												<a href="http://www.givemore.com/books-and-booklets/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+books&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3; text-decoration:none;">Books</a><br />
												<a href="http://www.givemore.com/videos/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+videos&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3; text-decoration:none;">Videos</a>
											</p>
										</td>

										<td width="180" valign="top" align="center">
											<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; line-height:30px; text-align:center;">
												<a href="http://www.givemore.com/meetings-discussions/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+meeting+packages&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3; text-decoration:none;">Meeting Packages</a><br />
												<a href="http://www.givemore.com/presentations/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+powerpoint+slides&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3; text-decoration:none;">PowerPoint&reg; Slides</a>
											</p>
										</td>

										<td width="130" valign="top" align="center">
											<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; line-height:30px; text-align:center;">
												<a href="http://www.givemore.com/category/pocket-cards/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+pocket+cards&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3; text-decoration:none;">Pocket Cards</a><br />
												<a href="http://www.givemore.com/category/wristbands/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+wristbands&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3; text-decoration:none;">Wristbands</a>
											</p>
										</td>

										<td width="165" valign="top" align="center">
											<p style="color:#1A80D3; font-family:helvetica, arial, sans-serif; font-size:16px; line-height:30px; text-align:center;">
												<a href="http://www.givemore.com/category/posters-and-prints/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+posters+and+banners&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3; text-decoration:none;">Posters &amp; Banners</a><br />
												<a href="http://www.givemore.com/gear/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+gifts+and+gear&utm_campaign=justsell+book+excerpt+request" style="color:#1A80D3; text-decoration:none;">Gifts &amp; Gear</a>
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
									&copy; by Give More Media Inc. &nbsp;|&nbsp; <a href="http://www.justsell.com/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+justsell&utm_campaign=justsell+book+excerpt+request" style="color:#656565;">www.JustSell.com</a><br />
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

'. $excerpt_text_content .'

-------------

Ideas to motivate people...

Inspire a little extra effort and attention. 212 the extra degree
http://www.givemore.com/212-the-extra-degree/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+212+the+extra+degree&utm_campaign=justsell+book+excerpt+request

Encourage better attitudes and service. Smile & Move
http://www.givemore.com/smile-and-move/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+smile+and+move&utm_campaign=justsell+book+excerpt+request

Inspire commitment, effort, and resilience. Cross The Line
http://www.givemore.com/cross-the-line/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+cross+the+line&utm_campaign=justsell+book+excerpt+request

Encourage more trust and accountability. Love Your People
http://www.givemore.com/love-your-people/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+love+your+people&utm_campaign=justsell+book+excerpt+request

No fluff. No parables. No matrixes. Just truth. Lead [simply]
http://www.givemore.com/lead-simply/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+lead+simply&utm_campaign=justsell+book+excerpt+request

-------------


Need a speaker for your next event?
Sam\'s thoughts and ideas have inspired thousands of people. He\'s the guy behind this stuff. Maybe he can help your organization.


Click below to learn about Sam or call (866) 952-4483
http://www.givemore.com/speaking/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+learn+about+sam&utm_campaign=justsell+book+excerpt+request


-------------


Upcoming meeting, project, or event?

Our fresh no-fluff messages, handouts, and themes can help you kick it off or support it by making it more interesting and meaningful.

------
Books
http://www.givemore.com/books-and-booklets/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+books&utm_campaign=justsell+book+excerpt+request

------
Videos
http://www.givemore.com/videos/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+videos&utm_campaign=justsell+book+excerpt+request

------
Meeting Packages
http://www.givemore.com/meetings-discussions/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+meeting+packages&utm_campaign=justsell+book+excerpt+request

------
PowerPoint(R) Slides
http://www.givemore.com/presentations/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+powerpoint+slides&utm_campaign=justsell+book+excerpt+request

------
Pocket Cards
http://www.givemore.com/category/pocket-cards/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+pocket+cards&utm_campaign=justsell+book+excerpt+request

------
Wristbands
http://www.givemore.com/category/wristbands/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+wristbands&utm_campaign=justsell+book+excerpt+request

------
Posters & Banners
http://www.givemore.com/category/posters-and-prints/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+posters+and+banners&utm_campaign=justsell+book+excerpt+request

------
Gifts & Gear
http://www.givemore.com/gear/?utm_source=js-book-excerpt-request&utm_medium=email&utm_content=footer+-+gifts+and+gear&utm_campaign=justsell+book+excerpt+request


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
		process_capture($book_excerpt_request_email, null, 'post-book-excerpt-request', $book_excerpt_capture_id);

		return '
			<section class="post-pdf-request">
				<h3 class="title">Please check your inbox.</h3>
				<p class="subtitle">The excerpt is on its way.</p>
			</section>
		';
		
	} else {
	 //	die("Sorry but the email could not be sent. Please go back and try again!");
	  echo "Failures:";
	  print_r($failures);
	}

} /* END function send_post_pdf */


/* Wordpress function call to bind the shortcode '[postbookexcerptrequest]' to the functions above. */
add_shortcode( 'postbookexcerptrequest', 'post_book_excerpt_request_control' );

?>