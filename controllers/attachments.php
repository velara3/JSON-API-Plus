<?php
/*
Controller name: Attachments
Controller description: Basic introspection methods for fetching attachments
*/

class JSON_API_Attachments_Controller {
	
	public function delete_attachment() {
		global $json_api;
	
	
		if ( !current_user_can( 'upload_files' ) && !current_user_can('delete_posts') ) {
			$json_api->error("You do not have permission to delete files.");
		}
		//$json_api->error("Test 1 You do not have permission to delete files.");
		//return null;
		$nonce_id = $json_api->get_nonce_id('attachments', 'update_attachment');
	
		if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
			//$json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
		}
		$id = $json_api->query->id !== null;
	
		if ($json_api->query->id !== null) {
			$id = (integer) $json_api->query->id;
		}
		else {
			$json_api->error("Include 'id' or 'slug' var in your request.");
		}
	
		$force_delete = true;
		if ($json_api->query->force_delete !== null) {
			$force_delete = (bool) $json_api->query->force_delete;
		}
	
		$result = wp_delete_attachment( $id, $force_delete );
	
		if ( $result ) {
			$successful = true;
		}
		else {
			$successful = false;
		}
		 
		$result = array(
				'post' => $result,
				'deleted' => (bool) $successful
		);
		 
		return $result;
	}
	
    public function delete_attachments() {
        global $json_api;
        
        
        if ( !current_user_can( 'upload_files' ) && !current_user_can('delete_posts') ) {
        	$json_api->error("You do not have permission to delete files.");
        }
        
        $nonce_id = $json_api->get_nonce_id('attachments', 'update_attachment');
        
        if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
        	//$json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
        }
        
        
        if ($json_api->query->ids !== null) {
        	$ids = $json_api->query->ids;
        }
        else {
        	$json_api->error("Include 'ids' var in your request.");
        }
        
        $ids = explode(",", $json_api->query->ids);
        
        $force_delete = true;
        
        if ($json_api->query->force_delete !== null) {
			$force_delete = (bool) $json_api->query->force_delete;
        }
        
        $deletedItems = array();
        $successful = true;
        
		foreach ($ids as $id) {
        	$deleted = wp_delete_attachment( $id, $force_delete );
        	$deletedItems[$id] = $deleted;
        	
        	if ($deleted==false) {
        		$successful = false;
        	}
		}
		
    	
    	$result = array(
    			'idsRequest' => $_REQUEST['ids'],
    			'ids' => $ids,
    			'deletedItems' => $deletedItems,
    			'status' => 'ok',
    			'successful' => (bool) $successful
    	);
    	
    	return $result;
    }

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

        // we should check if the file the attachments are part of are published or not
	    $attachments = $json_api->introspector->get_attachments($parent);
	    
	    $output = array(
	    	'count' => count($attachments),
	    	'attachments' => $attachments
	    	);
	    
        return $output;
    }
}

?>