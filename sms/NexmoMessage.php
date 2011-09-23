<?php

/* 
 * Class NexmoMessage handles the methods and properties of sending an SMS message.
 *
 * Listing of class properties and methods here -later
 *
 **/

class NexmoMessage {

	//Property stores most recent unparsed Nexmo response.
	var $unparsed_response = '';
	
	
	//Property stores most recent parsed Nexmo response.
	var $nexmo_response = '';
	
	
	//Whether this class should parse the response from the server. Default: true.
	var $parse_response = true;

	
	/**
	 * Prepare new text message.
	 */
	function text_message ( $to, $from, $message ) {
	
		if ( !is_numeric($from) ) 
			$from = utf8_encode( $from ); //Must be UTF-8 Encoded if not a continuous number
			
		$message = utf8_encode( $message ); //Must be UTF-8 Encoded
		
		$from = urlencode( $from ); // URL Encode
		$message = urlencode( $message ); // URL Encode
		
		$post = 'username=' . NX_USERNAME . '&password=' . NX_PASSWORD . '&from=' . $from . '&to=' . $to . '&text=' . $message;
		$this->send_request ( $post );
		
	}
	
	
	/**
	 * Prepare new WAP message.
	 */
	function binary_message ( $to, $from, $body, $udh ) {
	
		//Binary messages must be hex encoded
		$body = bin2hex ( $body ); //Must be hex encoded binary
		$udh = bin2hex ( $udh ); //Must be hex encoded binary
		$post = 'username=' . NX_USERNAME . '&password=' . NX_PASSWORD . '&from=' . $from . '&to=' . $to . '&type=binary&body=' . $body . '&udh=' . $udh;
		$this->send_request ( $post );
		
	}
	
	
	/**
	 * Prepare new binary message.
	 */
	function wappush_message ( $to, $from, $title, $url, $validity = 172800000 ) {
		
		//WAP Push title and URL must be UTF-8 Encoded
		$title = utf8_encode ( $body ); //Must be UTF-8 Encoded
		$url = utf8_encode ( $udh ); //Must be UTF-8 Encoded
		$post = 'username=' . NX_USERNAME . '&password=' . NX_PASSWORD . '&from=' . $from . '&to=' . $to . '&type=wappush&url=' . $url . '&title=' . $title . '&validity=' . $validity;
		$this->send_request ( $post );
		
	}
	
	
	/**
	 * Prepare and send new text message.
	 */
	 private function send_request ( $post ) {
	 
		$to_nexmo = curl_init( NX_SERVER );
		curl_setopt( $to_nexmo, CURLOPT_POST, true );
		curl_setopt( $to_nexmo, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $to_nexmo, CURLOPT_POSTFIELDS, $post );
		$from_nexmo = curl_exec( $to_nexmo );
		curl_close ( $to_nexmo );
		
		$from_nexmo = str_replace('-', '', $from_nexmo);
		$this->unparsed_response = $from_nexmo;
		
		if ( $this->parse_response ) {
			$this->nexmo_parse( $from_nexmo );
		}
	 
	 }
	
	
	/**
	 * Parse and display server response.
	 */
	private function nexmo_parse ( $from_nexmo ) {
		
		if ( (NX_SERVER == 'http://rest.nexmo.com/sms/json') ||  (NX_SERVER == 'https://rest.nexmo.com/sms/json') ) {

			$response_obj = json_decode( $from_nexmo );
			
			//How many messages were sent?
			if ( $response_obj->messagecount > 1 ) {
			
				$start = '<p>Your message was sent in ' . $response_obj->message-count . ' parts ';
			
			} else {
			
				$start = '<p>Your message was sent ';
			
			}
			
			//Check each message for errors
			$error = '';
			$messages_array = $response_obj->messages;
			
			foreach ( $messages_array as $message ) {
					
					if ( $message->status != 0) {
						$error .= $message->errortext . ' ';
						}
				
			}
			
			//Complete parsed response
			if ( $error == '' ) {
				$complete = 'and delivered successfully.</p>';
			} else {
				$complete = 'but there was an error: ' . $error . '</p>';
			}
			
			//Set parsed response
			$this->nexmo_response = $start . $complete;
			
		} elseif ( (NX_SERVER == 'http://rest.nexmo.com/sms/xml') || (NX_SERVER == 'https://rest.nexmo.com/sms/xml')) {
			
			// Server is set to XML, so parse with SimpleXML
			$error = '';
			
			//check if SimpleXML is loaded
			if ( !extension_loaded( 'SimpleXML' ) ) return $this->nexmo_response = 'Sorry, you need SimpleXML installed. Please install or try JSON instead.';
			
			$xml = simplexml_load_string( $from_nexmo );
			
			$number_of_parts = 0;
			foreach( $xml->messages[0]->attributes() as $val ) {
			
				$number_of_parts += $val;
				
			}
			
			if( $number_of_parts > 1 ) { 
			
				$start = '<p>Your message was sent in ' . $number_of_parts . ' parts ';
				
			} else { 
			
				$start = '<p>Your message was sent ';
			
			}
			
			for ($i = 0; $i < $number_of_parts; $i++) {
			
				if ( $xml->messages[0]->message[$i]->status != 0 )
					$error .= $xml->messages[0]->message[$i]->errorText;
				
			}
			
			if ( $error == '' ) {
				$complete = 'and delivered successfully.</p>';
			} else {
				$complete = 'but there was an error: ' . $error . '</p>';
			}
			
			$this->nexmo_response = $start . $complete;
  
		} else {
			//SERVER not properly set
			$this->nexmo_response = 'Sorry, server response was unreadable. Make sure you have defined constant SERVER to a valid Nexmo server (e.g., http://rest.nexmo.com/sms/json)';
		
		}
		
	}

}
?>