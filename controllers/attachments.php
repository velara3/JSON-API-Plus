<?php
/*
Controller name: Attachments
Controller description: Basic introspection methods for fetching attachments
*/

class JSON_API_Attachments_Controller {

    public function get_attachments() {
        global $json_api;
        global $user_ID;
    	
        // todo
        // support attachments by user
        // support attachments by post (this is supported)
        // support returning no attachments if user is not logged in
        // support returning all attachments for parent and it's descendents
		if (is_user_logged_in()) {
			$loggedIn = (bool) true;
		}
		else {
			$loggedIn = (bool) false;
		}
		
		if ($loggedIn) {
        	$user = get_userdata($user_ID);
		}

        if ($json_api->query->parent !== "null") {
            $parent = (integer) $json_api->query->parent;
        }
        else {
            $parent = null;
        }

	    $attachments = $json_api->introspector->get_attachments($parent);
	    
	    $output = array(
	    	'count' => count($attachments),
	    	'attachments' => $attachments
	    	);
	    
        return $output;
    }
}

?>