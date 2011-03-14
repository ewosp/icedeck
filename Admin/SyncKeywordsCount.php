<?php
    $sql = "SELECT k.keyword_id, count(children_id) as qt FROM " . RELATIONS_TABLE . . ' r, '
	 . KEYWORDS_TABLE . " k WHERE r.parent_type = 'C' AND r.children_type = 'K' AND k.keyword_id = r.children_id GROUP BY children_id";

    if (!$result = $db->sql_query($sql)) {
        message_die(SQL_ERROR, "Can't compute keywords count", '', __LINE__, __FILE__, $sql);
    }
    
    while ($row = $db->sql_fetchrow($result)) {
        $sql = "UPDATE IceDeck_Keywords SET keyword_count = $row[qt] WHERE keyword_id = $row[keyword_id]";
        if (!$db->sql_query($sql)) {
            message_die(SQL_ERROR, "Cant update keywords count.", '', __LINE__, __FILE__, $sql);
        }
    }
?>
