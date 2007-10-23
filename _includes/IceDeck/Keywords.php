<?php
class Keywords {
	
	//Gets the specified resources with specified keywords
	static public function GetResources ($resource_type, $keyword_list) {
		global $db;
		//$keyword_list can be a string with a keyword alone or an array
		if (!is_array($keyword_list)) {
			$keyword_clause = "keyword_word = '$keyword_list'";
		} else {
			//(keyword_word = 'alpha' OR keyword_word = 'beta') ...
			foreach ($keyword_list as $keyword_word) {
				$tab[] = "keyword_word = '$keyword_word'";
			}
			$keyword_clause = '(' . join(" OR ", $tab) . ')';
		}
		$keywords = split("k.keyword_word = '' OR ", $keyword_clause);
		$sql = "SELECT DISTINCT parent_id FROM " . TABLE_RELATIONS . " r, " . TABLE_KEYWORDS . " k WHERE $keyword_clause "
		     . "AND r.parent_type = '$resource_type' AND r.children_type = 'K' AND r.children_id = k.keyword_id ";
		if (!$result = $db->sql_query($sql)) message_die(SQL_ERROR, "Can't perform search", '', __LINE__, __FILE__, $sql);
		while ($row = $db->sql_fetchrow($result)) {
			$resources[] = $row['parent_id'];
		}
		return $resources;
	}
	
	//Adds a keyword, links it to specified resource and returns keyword id
	static public function Add ($resource_type, $resource_id, $keyword_word) {
		global $db;
		//Adds the keyword, if needed
		if (!$id = Keywords::GetID($keyword_word)) {
			//Add keyword
			$word = $db->sql_escape($keyword_word);
			$sql = "INSERT INTO " . TABLE_KEYWORDS . " (keyword_word, keyword_count) VALUES ('$word', 0)";
	                if (!$result = $db->sql_query($sql)) message_die(SQL_ERROR, "Can't query keywords", '', __LINE__, __FILE__, $sql);
			$id = $db->sql_nextid();
		}

		//Links to specified resource
		if (!Keywords::IsLinked($id, $resource_type, $resource_id)) {
			$sql = "INSERT INTO " . TABLE_RELATIONS . " (parent_type, parent_id, children_type, children_id)
				VALUES ('$resource_type', '$resource_id', 'K', $id)";
			if (!$result = $db->sql_query($sql)) message_die(SQL_ERROR, "Can't link keyword to specified resource $resource_type $resource_id", '', __LINE__, __FILE__, $sql);
			
			//Increments keyword count (useful to compute the cloud)
			$sql = "UPDATE " . TABLE_KEYWORDS . " SET keyword_count = keyword_count + 1 WHERE keyword_id = $id";
			if (!$result = $db->sql_query($sql)) message_die(SQL_ERROR, "Can't increment keyword count", '', __LINE__, __FILE__, $sql);
		}				

		//Returns id
		return $id;
	}

	//Unlinks a keyword from specified resource
	static public function Del ($resource_type, $resource_id, $keyword_word) {	
		if (!$id = Keywords::GetID($keyword_word)) {
			message_die(GENERAL_ERROR, "Keyword not found : $keyword_word", 'Keywords::Del fatal error');
		}
		if (Keywords::IsLinked($id, $resource_type, $resource_id)) {
	                global $db;
			$sql = "DELETE FROM " . TABLE_RELATIONS .
			      " WHERE parent_type = '$resource_type' AND parent_id = '$resource_id' AND children_type = 'K' AND children_id = $id";
                        if (!$result = $db->sql_query($sql)) message_die(SQL_ERROR, "Can't link keyword to specified resource $resource_type $resource_id", '', __LINE__, __FILE__, $sql);

			//If keyword count = 1, the keyword is not used anymore so we can erase it
			//Just decrements keyword count without any other consideration, so:
			$sql = "UPDATE " . TABLE_KEYWORDS . " SET keyword_count = keyword_count - 1 WHERE keyword_id = $id";
                        if (!$result = $db->sql_query($sql)) message_die(SQL_ERROR, "Can't decrement keyword count", '', __LINE__, __FILE__, $sql);	
		}
	}

	//Unlinked all keywords from  specified resource
	static public function Truncate ($resource_type, $resource_id) {
		//TODO : think about InnoDB tables and transaction safe queries (that should be facultative)
		global $db;

		//Gets the list of keywords, to update total count
		$sql = "SELECT children_id FROM " . TABLE_RELATIONS . " WHERE parent_type = '$resource_type' AND parent_id = '$resource_id' AND children_type = 'K'";
		if (!$result = $db->sql_query($sql)) message_die(SQL_ERROR, "Can't truncate keywords", '', __LINE__, __FILE__, $sql);
		while ($row = $db->sql_fetchrow($result)) {
                        $keywords[] = $row['children_id'];
		}

		if (!count($keywords)) {
			//No keywords linked to this resource = nothing to do
			return;
		}

		//Delete
		$sql = "DELETE FROM " . TABLE_RELATIONS . " WHERE parent_type = '$resource_type' AND parent_id = '$resource_id' AND children_type = 'K'";
		if (!$result = $db->sql_query($sql)) message_die(SQL_ERROR, "Can't delete keywords", '', __LINE__, __FILE__, $sql);
	
		//Update count
		$clause = implode(' OR keyword_id = ', $keywords);
		$sql = "UPDATE " . TABLE_KEYWORDS . " SET keyword_count = keyword_count - 1 WHERE $clause";
                if (!$result = $db->sql_query($sql)) message_die(SQL_ERROR, "Can't decrement keyword count", '', __LINE__, __FILE__, $sql);
		
	}

	static public function IsLinked ($keyword_id, $resource_type, $resource_id) {
		global $db;
                $sql = "SELECT count(*) FROM ". TABLE_RELATIONS . "
			WHERE parent_type = '$resource_type' AND parent_id = '$resource_id' AND children_type = 'K' AND children_id = '$keyword_id'";
                if (!$result = $db->sql_query($sql)) message_die(SQL_ERROR, "Can't check relations", '', __LINE__, __FILE__, $sql);
                $row = $db->sql_fetchrow($result);
                return ($row[0] == 1);

	}

	//Gets keyword id (returns null if keyword doesn't exist)
	static public function GetID ($keyword_word) {
                global $db;
                $sql = "SELECT keyword_id FROM ". TABLE_KEYWORDS . " WHERE keyword_word = '$keyword_word'";
                if (!$result = $db->sql_query($sql)) message_die(SQL_ERROR, "Can't query keywords", '', __LINE__, __FILE__, $sql);
                if ($row = $db->sql_fetchrow($result)) {
			return $row['keyword_id'];
		} else {
			return null;
		}
	}

	//Returns true if keyword $keyword_word exists.
	static public function Is ($keyword_word) {
		global $db;
		$sql = "SELECT count(*) FROM ". TABLE_KEYWORDS . " WHERE keyword_word = '$keyword_word'";
                if (!$result = $db->sql_query($sql)) message_die(SQL_ERROR, "Can't load card", '', __LINE__, __FILE__, $sql);
		$row = $db->sql_fetchrow($result);
		return ($row[0] == 1);
	}

	//Cleans keywords table, deleting orphan entries
	static public function Clean () {
		global $db;
		//We keep things linked to keywords (rather strange, don't you think?)
		$sql = 'SELECT DISTINCT parent_id FROM ' . TABLE_RELATIONS . " WHERE parent_type = 'K'";
	
		if (!$result = $db->sql_query($sql)) {
			message_die(SQL_ERROR, "Impossible d'interroger la base de données", '', __LINE__, __FILE__, $sql);
		}
		while ($row = $db->sql_fetchrow($result)) {
			$keywords[] = $row[0];
		}		

		//And of course, we keep keywords linked to other objects!
		$sql = 'SELECT DISTINCT children_id FROM ' . TABLE_RELATIONS . " WHERE children_type = 'K'";
	
		if (!$result = $db->sql_query($sql)) {
			message_die(SQL_ERROR, "Impossible d'interroger la base de données", '', __LINE__, __FILE__, $sql);
		}
		while ($row = $db->sql_fetchrow($result)) {
			$keywords[] = $row[0];
		}

		//Remove duplicates
		$keywords = array_unique($keywords);

		//Okay, we're ready to clean now :)
		if (!count($keywords)) {
			//Nothing to keep
			$sql = 'TRUNCATE ' . TABLE_KEYWORDS;
		} else {
			$sql = 'DELETE FROM ' . TABLE_KEYWORDS . ' WHERE keyword_id NOT IN (' . implode(', ', $keywords) . ')';
		}
		if (!$db->sql_query($sql)) {
			message_die(SQL_ERROR, "Can't delete orphan keywords.", '', __LINE__, __FILE__, $sql);
		}
	}

	static public function SyncCount () {
		global $db;
		$sql = "SELECT k.keyword_id, count(children_id) as qt FROM " . TABLE_RELATIONS . ' r, '
		     . TABLE_KEYWORDS . " k WHERE r.parent_type = 'C' AND r.children_type = 'K' AND k.keyword_id = r.children_id GROUP BY children_id";
		if (!$result = $db->sql_query($sql)) {
			message_die(SQL_ERROR, "Can't compute keywords count", '', __LINE__, __FILE__, $sql);
		}
    
		while ($row = $db->sql_fetchrow($result)) {
        		$sql = "UPDATE IceDeck_Keywords SET keyword_count = $row[qt] WHERE keyword_id = $row[keyword_id]";
        		if (!$db->sql_query($sql)) {
            			message_die(SQL_ERROR, "Cant update keywords count.", '', __LINE__, __FILE__, $sql);
        		}
    		}
	}
}
?>
