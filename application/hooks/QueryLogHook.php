<?php
/* application/hooks/QueryLogHook.php */
class QueryLogHook {

    function log_queries() {    
        $CI =& get_instance();
		echo $CI->db->last_query();
        $times = $CI->db->query_times;
        $dbs    = array();
        $output = NULL;     
        $queries = $CI->db->queries;

        if (count($queries) == 0)
        {
            $output .= "no queries\n";
        }
        else
        {
            foreach ($queries as $key=>$query)
            {
                $output .= $query . "\n";
            }
            $took = round(doubleval($times[$key]), 3);
            $output .= "===[took:{$took}]\n\n";
        }

        $CI->load->helper('file');
        if ( ! write_file(APPPATH  . "/logs/queries.log.php", $output, 'a+'))
        {
             log_message('debug','Unable to write query the file');
        }   
    }

}