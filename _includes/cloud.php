<?php
/*
 * This class is based on Dave Lizotte's Faire son propre Tag Cloud sous PHP :
 * www.pckult.net/index.php?option=com_content&task=view&id=551&Itemid=7
 *
 * Dave provides sql raw query, algorhytm and code logics.
 * The class, comments and options have been added by Dereckson.
 *
 * Code Sample :
 *  $cloud = new Cloud('Table', 'FieldToPrint', 'PrimaryKey');
 *  $cloud->Compute();
 *  foreach ($cloud->cloud as $cloudItem) {
 *      echo '<a href="/search/' . $cloudItem[0] . '" style="font-size: ' . $cloudItem[1] . '%">' . $cloudItem[0] . '</a>';
 *  }
 */

class Cloud {
    /*
     *  What data to parse ?
     *    - In the able $table
     *    - Cloud items are the $field fields.
     *    - The quantity, use the table's primary key : count($primary_key)
     */
    function __construct ($table, $field, $qt) {
        $this->sql = "SELECT $field as tag, $qt AS quantity "
                   . "FROM $table GROUP BY $field ORDER BY $field ASC";
    }
    
    
    public function Compute() {
        global $db;
        if (!$result = $db->sql_query($this->sql)) {
            message_die(SQL_ERROR, "Can't compute cloud", '', __LINE__, __FILE__, $sql);
        }
        /*
         If you wish throw an error if there isn't data, uncomment this :
        if (!$db->sql_numrows($result)) {
            message_die(SQL_ERROR, "No keywords in table.", '', __LINE__, __FILE__, $sql);
            message_die(GENERAL_ERROR, "To enable cloud, you've to add at least one keyword to one card.", 'No cloud data');
        }
        */
        while ($row = $db->sql_fetchrow($result)) {
            $this->tags[$row['tag']] = $row['quantity'];
        }
        
        //Gets minimal, maximal and spread values
        //array_values returns the values from array, indexing it numerically.
        $max_qty = max(array_values($this->tags));
        $min_qty = min(array_values($this->tags));
        $spread = $max_qty - $min_qty;
        if (!$spread) $spread = 1; //let's avoid divide by 0
        //How much have we to increment font size ?
        $step = ($this->size_max - $this->size_min) / $spread;
        
        unset($this->cloud);
        foreach ($this->tags as $key => $value) {
            //Gets font-size value
            $font_size = $this->size_min + $step * ($value - $min_qty);
            //Sets keyword as the specified string if null
            if (!$key) $key = $this->nullString;
            //$cloud is an 2D array e
            $this->cloud[] = array($key, $font_size);
        }
    }
    
    private $sql;
    
    public $size_min = 100; //in %
    public $size_max = 250; //in %
    public $nullString = '(no keyword)';
    
    public $cloud;
    
}

?>