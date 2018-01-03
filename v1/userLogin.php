<?php 
 
require_once '../includes/DbOperations.php';			// iterpiamas failas, kurio skriptas reikalingas
 
$response = array(); 									// uzklausos atsakyma ideti i array
 
if($_SERVER['REQUEST_METHOD']=='POST'){					// patikrinama ar yra POST uzklausa
    if(isset($_POST['username']) and isset($_POST['password'])){		// jei yra POST uzklausa, tikrinama ar pateikti visi reikalingi parametrai
        $db = new DbOperations(); 
 
        if($db->userLogin($_POST['username'], $_POST['password'])){		// patikrinama ar teisingai ivesti prisijungimo duomenys
            $user = $db->getUserByUsername($_POST['username']);			// vykdoma, jei teisingai ivesti prisijungimo duomenys
            $response['error'] = false; 
            $response['id'] = $user['id'];								// gaunami naudotojo duomenys is DB pagal username
            $response['email'] = $user['email'];
            $response['username'] = $user['username'];
        }else{
            $response['error'] = true; 					// neteisingai ivesti prisijungimo duomenys
            $response['message'] = "Invalid username or password";          
        }
 
    }else{												// rodoma klaida, nes neivesti visi reikiami parametrai
        $response['error'] = true; 
        $response['message'] = "Required fields are missing";
    }
}
 
echo json_encode($response);			// pateikti uzklausos atsakyma json formatu