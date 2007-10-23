<?php
class Card {
    function __construct ($id = '') {
        if ($id) {
            $this->id = $id;
	    $this->Load();
        } else {
            //New card
            $this->created_date = time();
	    $this->points = 0;
	    $this->completed = 0;
	    $this->accent = DEFAULT_ACCENT;
        }
    }

    //Keywords management

    //Add a keyword to the card
    public function AddKeyword ($keyword) {
            Keywords::Add('C', $this->id, $keyword);
    }

    //Remove a keyword from the card
    public function DelKeyword ($keyword) {
            Keywords::Del('C', $this->id, $keyword);		
    }

    //Indicates wheter a specified keyword is linked to the card
    public function HasKeyword ($keyword) {
            Keywords::Has('C', $this->id, $keyword);
    }

    //Loads card data from db
    public function Load () {
            global $db;
            $clause = ($this->id == 'rnd') ? " ORDER BY RAND() LIMIT 1" : " WHERE card_id = $this->id";
            $sql = 'SELECT card_id, card_title, card_accent, card_text, card_logo, card_created_date, card_created_by, card_updated_date, card_updated_by, card_points, card_completed FROM ' . TABLE_CARDS . $clause;
            if (!$result = $db->sql_query($sql)) message_die(SQL_ERROR, "Can't load card", '', __LINE__, __FILE__, $sql);
            if (!$row = $db->sql_fetchrow($result)) message_die(GENERAL_ERROR, 'Card #' . $this->id . ' not created or hard erased.', 'Unknown card');
            $this->id = $row['card_id'];
            $this->title = $row['card_title'];
            $this->accent = $row['card_accent'];
            $this->text = $row['card_text'];
            $this->logo = $row['card_logo'];
            $this->created_date = $row['card_created_date'];
            $this->created_by = $row['card_created_by'];
            $this->updated_date = $row['card_updated_date'];
            $this->updated_by = $row['card_updated_by'];
	    $this->points = $row['card_points'];
	    $this->completed = $row['card_completed'];
            $this->LoadKeywords();            
    }

    public function LoadKeywords () {
            global $db;
            $sql = 'SELECT k.keyword_word FROM ' . TABLE_KEYWORDS . ' k, ' . TABLE_RELATIONS . " r
		    WHERE r.parent_type = 'C' AND r.parent_id = $this->id AND r.children_type = 'K' AND k.keyword_id = r.children_id";
            if (!$result = $db->sql_query($sql)) message_die(SQL_ERROR, "Can't get keywords", '', __LINE__, __FILE__, $sql);
	    while ($row = $db->sql_fetchrow($result)) {
		$this->keywords[] = $row['keyword_word'];
		$url_keyword = urlencode($row['keyword_word']);
		$keywords_to_urlize[]= "<a href='/search/$url_keyword' />$row[keyword_word]</a>";
	    }
            //key1, key2, key3, key4 string representation
	    if (count($this->keywords)) {
		$this->keywords_string = implode(', ', $this->keywords);
		$this->keywords_URL = implode(', ', $keywords_to_urlize);
	    }
    }

    //Inserts or updates card data
    public function Save () {
            global $db;
            //Escapes text fiels
            $title = $db->sql_escape($this->title);
            $text = $db->sql_escape($this->text);
            $logo = $db->sql_escape($this->logo);
            if ($this->id) {
                    //Updates data
                    $sql = 'UPDATE ' . TABLE_CARDS . " SET card_title = '$title', card_accent = '$this->accent', card_text = '$text', card_logo = '$logo', card_created_date = '$this->created_date', card_created_by = '$this->created_by', card_updated_date = '$this->updated_date', card_updated_by = '$this->updated_by', card_completed = $this->completed, card_points = $this->points WHERE card_id = $this->id";
            } else {
                    //Inserts new card
                    $sql = 'INSERT INTO ' . TABLE_CARDS . " (card_title, card_accent, card_text, card_logo, card_created_date, card_created_by, card_updated_date, card_updated_by, card_points, card_completed)
                    VALUES ('$title', '$this->accent', '$text', '$logo', '$this->created_date', '$this->created_by', '$this->updated_date', '$this->updated_by', $this->points, $this->completed)";
            }
            if (!$db->sql_query($sql)) message_die(SQL_ERROR, "Can't save card", '', __LINE__, __FILE__, $sql);
            if (!$this->id) $this->id = $db->sql_nextid();
    }

}

?>
