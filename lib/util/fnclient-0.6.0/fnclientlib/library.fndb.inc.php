<?php
/* FILE INFO */
/*
 * File: library.fndb.inc.php
 * Author: Greg Elin
 * Version: 0.1
 * Updated: 04.14.2005
 * Description: Classes for working with mysql or sqllite databases.
 *
 */ 
 
 /* CLASSES */
 
 class FNDatabaseAbstract {
	
	function FNDatabaseAbstract() {
	
		GLOBAL $DB_TYPE;
		$this->db_type = $DB_TYPE;
		displayDebug("FNDatabaseAbstract...", 3);
		
		switch($this->db_type) {
			case 'SQLITE':
				@touch('fnslib/database.sqlite');
				if (! is_writable('fnslib/database.sqlite')) {
					echo "The database file 'fnslib/database.sqlite' is not writable! Please check permissions!";
					//die("The database file 'fnslib/database.sqlite' is not writable! Please check permissions!");
					break;
				}
				$this->db = new SQLiteDatabase('fnslib/database.sqlite');
				break;
				
			case 'MYSQL':
				GLOBAL $MYSQL_SERVER, $MYSQL_USERNAME, $MYSQL_PASSWORD, $MYSQL_DBNAME;
				if (! $this->db = mysql_connect($MYSQL_SERVER, $MYSQL_USERNAME, $MYSQL_PASSWORD)) {
					echo mysql_error(); 
					//die(mysql_error());
				}
				if (! mysql_select_db($MYSQL_DBNAME, $this->db)) {
					echo mysql_error();
					//die(mysql_error());
				}
				break;
			
			case 'NONE':
				// Do nothing, database not in use.
				break;
			default:
				die("Database type '$DB_TYPE' not supported!");
		}
	}
	
	function query($query) {
		displayDebug("function query",4);
		displayDebug("query: ".$query,4);
		displayDebugParam($r,4);
		
		$r = new resultset_abstract($query, $this->db);
		return $r;
	}
	
	function getTables() {
		GLOBAL $MYSQL_SERVER, $MYSQL_USERNAME, $MYSQL_PASSWORD, $MYSQL_DBNAME;
		$result = mysql_list_tables( $MYSQL_DBNAME);
		$num_rows = mysql_num_rows($result);
		for ($i=0;$i<$num_rows;$i++) {
			//here
			$tables[$i] = mysql_tablename($result, $i);
		}
		return $tables;
	}		
	
	function lastInsertRowId() {
		switch($this->db_type) {
			case 'SQLITE':
				return $this->db->lastInsertRowId();				
			case 'MYSQL':
				return mysql_insert_id($this->db);		
		}
	}	
}

class resultset_abstract {

	function resultset_abstract($query, $db) {
		GLOBAL $DB_TYPE;
		
		$this->r = array();
		$this->pointer = 0;
		switch($DB_TYPE) {
			case 'SQLITE':
				$r = $db->query($query);
				while ($row = @$r->fetch()) {
					$this->r[] = $row;
				}
				break;
			case 'MYSQL':
				
				$r = mysql_query($query, $db);
				while ($row = @mysql_fetch_assoc($r)) {
					$this->r[] = $row;
				}
				
				break;
		}
	}

	function fetch() {
		if (isset($this->r[$this->pointer])) {
			$row = $this->r[$this->pointer];

			$this->pointer++;
			return $row;
		}
	}
	
	function numrows() {
		return count($this->r);
	}

}

?>