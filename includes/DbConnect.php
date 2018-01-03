<?php

	// script will connect database

	class DbConnect
	{
		private $con;					// deklaruojamas kintamasis
		
		function __construct()
		{
			
		}

		function connect(){
			include_once dirname(__FILE__).'/Constants.php';					// iterpiamas failas su konstantomis
			$this->con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	// inicializuojamas prisijungimas prie DB per kintamaji con

			if(mysqli_connect_errno()){											// patikrinama ar nera klaidos prisijungiant prie DB
				echo "Failed to connect with database".mysqli_connect_err();	// jie yra klaida, rodomas prisijungimo prie DB klaidos pranesimas
			}

			return $this->con;													// jei nera klaidos, grazinama kintamojo con reiksme
		}
	}