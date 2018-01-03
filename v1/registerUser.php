<?php

require_once '../includes/DbOperations.php';		// iterpiamas failas, kurio skriptas reikalingas

$response = array();				// uzklausos atsakyma ideti i array

if ($_SERVER['REQUEST_METHOD']=='POST') {		// patikrinama ar yra POST uzklausa
	if(											// jei yra POST uzklausa, tikrinama ar pateikti visi reikalingi parametrai
		isset($_POST['username']) and 
			isset($_POST['email']) and 
				isset($_POST['password']))
		{
		// jei pateikti visi reikalingi parametrai, toliau atliekamos operacijos su duomenimis
		$db = new DbOperations();		// sukuriamas objektas

		$result = $db->createUser(	$_POST['username'],			// iskvieciamas createUser metodas is DbOperations klases ir perduodamos reikalingos reiksmes
									$_POST['password'],
									$_POST['email']
								);

		if ($result == 1) {
			$response['error'] = false;							// sekmingai sukurtas naujas naudotojas
			$response['message'] = "User registered successfully";
		}elseif ($result == 2) {								// klaida kuriant nauja naudotoja
			$response['error'] = true;
			$response['message'] = "Some error occurred please try again";
		}elseif ($result == 0) {
			$response['error'] = true;
			$response['message'] = "It seems you are already registered, please choose a different email and username";
		}
	

	} else {				// jei pateikti ne visi reikalingi parametrai, rodoma zinute, kad reikia uzpildyti trukstamus laukelius
		$response['error'] = true;
		$response['message'] = "Required fields are missing";
	}
} else {										// rodoma klaida jei nera POST uzklausos
	$response['error'] = true;
	$response['message'] = "Invalid Request";
}

echo json_encode($response);					// pateikti uzklausos atsakyma json formatu