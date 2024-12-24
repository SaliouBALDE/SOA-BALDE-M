<?php
    $ch = curl_init();
	try {
		curl_setopt($ch, CURLOPT_URL, "http://localhost:3500");
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);   
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);         
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		
		$response = curl_exec($ch);
		
	    if (curl_errno($ch)) {
			echo curl_error($ch);
			die();
		}
		
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($http_code == intval(200)){
			echo "Ressource valide";
		}
		else{
			echo "Ressource introuvable : " . $http_code;
		}
	} catch (\Throwable $th) {
		throw $th;
	} finally {
		curl_close($ch);
	}
?>
