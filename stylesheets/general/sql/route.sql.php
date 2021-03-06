<?php
require_once "sql/_common.sql.php";
require_once "sql/highway.sql.php";
require_once "conf/route.conf.php";

function _getHikingRouteColorSql($col) {
    return "
	(CASE
	    WHEN $col LIKE 'red:%' THEN 'red'
	    WHEN $col LIKE 'blue:%' THEN 'blue'
	    WHEN $col LIKE 'green:%' THEN 'green'
	    WHEN $col LIKE 'yellow:%' THEN 'yellow'
	    ELSE 'unknown'
	END)
    ";
}

function _getHikingGradeSql($col) {
    return "
	(CASE
	    WHEN $col LIKE '%_bar' THEN 0
	    WHEN $col LIKE '%_corner' THEN 1
	    WHEN $col LIKE '%_backslash' THEN 1
	    WHEN $col LIKE '%_L' THEN 2
	    WHEN $col LIKE '%_triangle' THEN 2
	    WHEN $col LIKE '%_bowl' THEN 2
	    WHEN $col LIKE '%_turned_T' THEN 2
	    ELSE 3
	END)
    ";
}

function _getBicycleGradeSql($col) {
    return "
	(CASE
	    WHEN $col IN ('icn','iwn') THEN 0
	    WHEN $col IN ('ncn','nwn') THEN 1
	    WHEN $col IN ('rcn','rwn') THEN 2
	    WHEN $col IN ('lcn','lwn') THEN 3	    
	    ELSE 4
	END)
    ";
}

function _getHikingRouteColumn($col,$offset) {
    global $ROUTE_BICYCLE_GRADES;
    global $RENDER_ZOOMS;
    global $ROUTE_MAX_COUNT;
    
    
    
    $bicycleGrade = _getBicycleGradeSql('network0');
    
    $zoom = end($RENDER_ZOOMS);
    $maxBicycleGrade = end($ROUTE_BICYCLE_GRADES[$zoom]);
    
    if ( empty($maxBicycleGrade) )
	$maxBicycleGrade = 1000;
    
    
    $tmp = array();
    foreach ( range(2,$ROUTE_MAX_COUNT) as $i ) {
	if ( $offset+$i-1 > $ROUTE_MAX_COUNT ) break;
	
	if ( $offset+$i <= $ROUTE_MAX_COUNT ) {
	    $tmp2 = "
		(CASE
		    WHEN route0 NOT IN ('bicycle','mtb') OR $bicycleGrade > $maxBicycleGrade THEN $col".($offset+$i)."
		    ELSE $col".($offset+$i-1)."
		END)
	    ";
	}
	else {
	    $tmp2 = "
		(CASE
		    WHEN route0 NOT IN ('bicycle','mtb') OR $bicycleGrade > $maxBicycleGrade THEN NULL
		    ELSE $col".($offset+$i-1)."
		END)
	    ";
	}
	$tmp[] = "\t\tWHEN route$i IN ('foot','hiking') THEN $tmp2";	
    }
    $tmp = implode("\n",$tmp);
    if ( $offset < $ROUTE_MAX_COUNT ) {
	$tmp2 = "
	    (CASE
		WHEN route0 NOT IN ('bicycle','mtb') OR $bicycleGrade > $maxBicycleGrade THEN $col".($offset+1)."
		ELSE {$col}{$offset}
	    END)
	";
    }
    else {
	$tmp2 = "
	    (CASE
		WHEN route0 NOT IN ('bicycle','mtb') OR $bicycleGrade > $maxBicycleGrade THEN NULL
		ELSE {$col}{$offset}
	    END)
	";
    }
    
    
    
    return " 
	(CASE
	    WHEN route0 IN ('foot','hiking') THEN {$col}{$offset}
	    WHEN route1 IN ('foot','hiking') THEN $tmp2
	    $tmp
	END)
    ";
}


function sql_route_hiking($offset, $cols = '0',$where = '1 = 1') {
    $highwayGradeSql = _getHighwayGradeSql(true);    
    
return <<<EOD
	SELECT 
		way,
		    $highwayGradeSql AS
		highway_grade,
		offsetside,		   
		color,
		sac_scale,
		route,
		$cols
	FROM routes2
	WHERE
		route IN ('foot','hiking')
	    AND "offset" = $offset
	    AND ($where)	
EOD;
}


function sql_route_bicycle($offset,$cols = '0',$where = '1 = 1') {	
    $highwayGradeSql = _getHighwayGradeSql(true);    
    
return <<<EOD
	SELECT 
		way,
		    $highwayGradeSql AS
		highway_grade,
		offsetside,
		"mtb:scale",
		route,
		density,
		network,
		(CASE 
		    WHEN oneway IN ('false','0','no') THEN 'no'
		    WHEN oneway IN ('true','1','yes') THEN 'yes'
		    ELSE COALESCE(oneway,CAST('no' AS text))
		END) AS oneway,
		
		$cols
	FROM routes2 R2	
	WHERE
		route IN ('bicycle','mtb')
	    AND "offset" = $offset
	    AND ($where)	
EOD;
}

function sql_route_ski($offset,$cols = '0',$where = '1 = 1') {	
    $highwayGradeSql = _getHighwayGradeSql(true);    
    
return <<<EOD
	SELECT 
		way,
		    $highwayGradeSql AS
		highway_grade,
		offsetside,
		route,	  
		$cols
	FROM routes2
	WHERE
		route IN ('ski')
	    AND "offset" = $offset
	    AND ($where)	
EOD;
}
