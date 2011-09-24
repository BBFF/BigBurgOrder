<?php

/**
 * Nexmo PHP. Set the two constants below, then skip to the bottom!
 */

define('NX_USERNAME','88b01997');	// Enter your username here
define('NX_PASSWORD','d6684617');		// Enter your password here


/**
 * Constant SERVER is set for a JSON response from Nexmo.
 * If you want an XML response, set to: http://rest.nexmo.com/sms/xml
 * NexmoMessage class requires SimpleXML for parsing.
 * 
 * For security reason, you might want to switch to https.
 */
 
define('NX_SERVER', 'https://rest.nexmo.com/sms/json');


/**
 * Include NexmoMessage class. This class handles SMS messaging.
 *
 */

include ( "NexmoMessage.php" );


/**
 * To send a text message.
 *
 */

//Step 1: Declare new NexmoMessage.
$nexmo_sms = new NexmoMessage;

//Step 2: Use text_message( $to, $from, $message ) method to send a message. 
$nexmo_sms->text_message( '6590350605', 'BBFFOrder', 'Hello!' );

//Step 3: Display parsed response.
echo $nexmo_sms->nexmo_response;

//Done!

?>