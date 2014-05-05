<?php

require_once("../includes/db_lib.php");
require_once("db_mysql_lib.php");

/**
 *	Class that manages the abbreviations
 *	
 *	Examples
 *    	Abbreviations::addabbreviation("SA","Staphilococuss Aureaus");
 *		Abbreviations::updateAbbreviation(1, "CC","QQQQQQQQQQQQQQ xxxxxxxxxxxxx");
 *		Abbreviations::getById(1));
 *		Abbreviations::getAllAbbreviations());
 *		Abbreviations::searchByAbbreviation("ss");
 * 		Abbreviations::removeAbbreviation(3);
 *
 */
class Abbreviations {

	public $abbreviation;
	public $word;
	public $id;

	/**
	 * [getObject converts mysql record to an abbreviation object]
	 * @param  [mysql record] $record 
	 * @return [object] abbreviation object
	 */
	public static function getObject($record)
	{
		$abbreviation = new Abbreviations();

		if (isset($record['abbreviation'])){
			$abbreviation->abbreviation = $record['abbreviation'];
		}
		else {
			$abbreviation->abbreviation =  null;
		}
		if (isset($record['word'])){
			$abbreviation->word = $record['word'];
		}
		else {
			$abbreviation->word = null;
		}
		if (isset($record['id'])){
			$abbreviation->id = $record['id'];
		}
		else {
			$abbreviation->id = null;
		}

		return $abbreviation;
	}

	/**
	 * [Retrieves record from table abbreviation]
	 * @param  [integer] id of the abbreviation	
	 * @return [object] Abbreviation object from getObject
	 */
	
	public static function getById($id)
	{
		global $con;
		$query_string = "SELECT * FROM abbreviations WHERE id = $id";
		$record = query_associative_one($query_string);
		return Abbreviations::getObject($record);
	}

	public static function searchByAbbreviation($abb)
	{
		global $con;
		$query_string = "SELECT * FROM abbreviations WHERE abbreviation = '$abb' LIMIT 1";
		$record = query_associative_one($query_string);
		return Abbreviations::getObject($record);
	}

	public static function addAbbreviation($abb, $word)
	{
		global $con;
		$abb = mysql_real_escape_string($abb);
		$word = mysql_real_escape_string($word);
		$query_string = "INSERT INTO abbreviations (abbreviation, word) values('$abb', '$word') ";
		$result = query_insert_one($query_string);
		return get_last_insert_id();	
	}

	public static function updateAbbreviation($id, $abb, $word)
	{
		global $con;
		$query_string = "UPDATE abbreviations SET abbreviation = '$abb', word = '$word' where id = $id";
		$record = query_update($query_string);
		
	}

	public static function getAllAbbreviations()
	{
		global $con;
		$query_string = "SELECT * FROM abbreviations";
		$record = query_associative_all($query_string, $count);
		$retval = array();

		foreach($record as $obj)
		{
			$retval[] = Abbreviations::getObject($obj);
		}
	
		return $retval;
	}

	public static function removeAbbreviation($id) 
	{
		global $con;
		$query_string = "DELETE from abbreviations where id = $id";
		$record = query_delete($query_string);
	}


}


?>