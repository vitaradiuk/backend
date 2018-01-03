<?php

	/**
	* 	Klase reikalinga atlikti operacijas su DB
	*/
	class DbOperations
	{
		private $con;					// deklaruojamas kintamasis
		
		function __construct()			// konstruktoriuje inicializuojamas kintamasis con is DbConnect klases
		{
			require_once dirname(__FILE__).'/DbConnect.php';		// importuojamas DbConnect.php failas

			$db = new DbConnect();						// sukuriamas objektas

			$this->con = $db->connect();				// gaunama prisijungimo prie DB nuoroda, iskvieciant connect metoda is DbConnect klases
		}

		/* CRUD -> C -> CREATE */

		public function createUser($username, $pass, $email){	// metodas sukuria nauja naudotoja su 3 parametrais
			if ($this->isUserExist($username,$email)) {			// patikrinama ar naudotojas neegzistuoja
				return 0;										// grazinama 0 reiksme, jei egziztuoja
			} else {											// jei ne, toliau sukuriamas naujas naudotojas

				$password = md5($pass);							// uzkoduojamas password'as, md5 grazina 32 simboliu sesioliktaini skaiciu
				$stmt = $this->con->prepare("INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES (NULL, ?, ?, ?);");		//paruosiama SQL uzklausa

				$stmt->bind_param("sss", $username, $password, $email);		// pridedami realus parametrai reikalingi uzklausai

				if($stmt->execute()){// kai iskvieciamas metodas execute, vykdoma SQL uzklausa, jei duomenys iterpiami sekmingai, grazinama true reiksme(1)
					return 1;
				}else{
					return 2;		// kitu atveju grazinama reiksme false(2)
				}
			}
		}

		public function userLogin($username, $pass){		// metodas naudotojo prisijungimui
			$password = md5($pass);
			$stmt = $this->con->prepare("SELECT id FROM users WHERE username = ? AND password = ?");//formuojama uzklausa, username ir password turi atitikti
			$stmt->bind_param("ss", $username, $password);						// perduodami username ir password parametrai
			$stmt->execute();													//vykdoma
			$stmt->store_result();												// rezultatas
			return $stmt->num_rows > 0;											// jei randa 0, tai tokio naudotojo nera ir jis prisijungti negali
		}

		public function getUserByUsername($username){			// metodas reikalingas patikrinti ar egziztuoja naudotojas
			$stmt = $this->con->prepare("SELECT * FROM users WHERE username = ?");
			$stmt->bind_param("s", $username);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();			// grazina eilute is DB pagal username
		}

		private function isUserExist($username, $email){			// metodas patikrina ar naudotojas, kuris registruojasi, egzistuoja
			$stmt = $this->con->prepare("SELECT id FROM users WHERE username = ? OR email = ?");	// suformuojama uzklausa
			$stmt->bind_param("ss", $username, $email);		// perduodami username ir email parametrai
			$stmt->execute();								//vykdoma
			$stmt->store_result();				// rezultatas
			return $stmt->num_rows > 0;			// jei randa 0, tai naudotojas neegzistuoja
		}
	}

	