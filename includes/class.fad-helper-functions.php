<?php

// AP Style for Dates
if ( !function_exists('apStyleDate') ) {
	function apStyleDate($date){

		$date = strftime("%l:%M %P", strtotime($date));
	
		$date = str_replace(":00", "", $date);
		$date = str_replace("m", ".m.", $date);
	
		return $date;
	
	}
}

// Partition / Split Col function
if ( !function_exists('partition') ) {
    function partition( $list, $p ) {
        $listlen = count( $list );
        $partlen = floor( $listlen / $p );
        $partrem = $listlen % $p;
        $partition = array();
        $mark = 0;
        for ($px = 0; $px < $p; $px++) {
            $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
            $partition[$px] = array_slice( $list, $mark, $incr );
            $mark += $incr;
        }
        return $partition;
    }
}