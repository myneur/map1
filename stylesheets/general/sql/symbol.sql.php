<?php
require_once "inc/utils.php";
require_once "conf/pgis.php";
require_once "conf/symbol.conf.php";
require_once "sql/_common.sql.php";


function getSymbolGradeSQl() {
    $historic = "T.historic";
    $castle_type = "T.castle_type";
    $amenity = "T.amenity";
    $tourism = "T.tourism";
    $ruins = "COALESCE(T.ruins,'no'::text)";
    $place_of_worship = "COALESCE(T.place_of_worship)";
    $place_of_worship_type = "T.\"place_of_worship:type\"";
    /*
    (CASE
		WHEN W.nkp = 1 THEN 5.0
		WHEN W.kp = 1 THEN 2.5		
		ELSE 0.0
	    END)
	    +
    */
    return "	
	((
	    (CASE
		WHEN $historic='castle' AND ($castle_type IN ('defensive','burg','fortress')) AND $ruins = 'no' THEN 2.5
		WHEN $historic='castle' AND ($castle_type IN ('stately','schloss','burg;schloss') OR $castle_type IS NULL) AND $ruins = 'no' THEN 2.0
		WHEN $historic='castle' AND ($castle_type IN ('defensive','burg','fortress')) AND $ruins='yes' THEN 1.8
		WHEN $historic='ruins' OR $ruins='yes' THEN 1.3
		WHEN T.building='church' OR ($amenity='place_of_worship' AND COALESCE($place_of_worship,$place_of_worship_type,T.building,$historic,'church') NOT IN ('chapel','monastery','wayside_shrine','wayside_cross')) THEN 1.0
		WHEN COALESCE($place_of_worship_type,$place_of_worship,$historic)='monastery' THEN 2.0
		WHEN $amenity = 'theatre' THEN 0.5
		WHEN $tourism = 'museum' THEN 0.8
		WHEN $tourism = 'zoo' THEN 1.0		
		ELSE 0
	    END)
	)
	*
	(CASE
	    WHEN T.tourism = 'attraction' THEN 1.3
	    WHEN T.tourism = 'yes' THEN 1.1
	    ELSE 1.0
	END))
    ";
}

function sql_symbol_short($priority) {
		global $RENDER_ZOOMS,$SYMBOL_DENSITY,$SYMBOL;
	  $zoom = $RENDER_ZOOMS[0];
		$count = linear($SYMBOL_DENSITY,$RENDER_ZOOMS[0]);
		$type = 0;
		$types = array();
		foreach ( $SYMBOL AS $a ) {
			 $type++;
			 if ( !empty($a['zooms'][$zoom]) && $a['zooms'][$zoom] == $priority + 1 ) {
				 $types[] = $type;
			 } 			 
		}
		
		$types = empty($types) ? 'false' : 'type IN ('.implode(',',$types).')';
    return "
			SELECT * FROM symbols S
			JOIN symbol_density D ON S.osm_id = D.osm_id
			WHERE name IS NOT NULL AND count < $count AND $types
			ORDER BY grade DESC
    ";
}

function sql_symbol_short_notrect() {
    return "
	SELECT * FROM symbol S	
	ORDER BY grade DESC
    ";
}

function sql_symbol($where = '1 = 1',$order = 'z_order') {
	global $SYMBOL;
	$propertyWhereQuery = getPropertyWhereQuery($SYMBOL);
	$propertyWhereQuery = str_replace('"historic"',"T.historic",$propertyWhereQuery);
	$propertyWhereQuery = str_replace('"castle_type"',"T.castle_type",$propertyWhereQuery);
	$propertyWhereQuery = str_replace('"amenity"',"T.amenity",$propertyWhereQuery);
	$propertyWhereQuery = str_replace('"tourism"',"T.tourism",$propertyWhereQuery);
	$propertyWhereQuery = str_replace('"natural"',"T.natural",$propertyWhereQuery);
	$propertyWhereQuery = str_replace('"ruins"',"T.ruins",$propertyWhereQuery);
	$propertyWhereQuery = str_replace('"place_of_worship"',"T.place_of_worship",$propertyWhereQuery);
	$propertyWhereQuery = str_replace('"highway"',"T.highway",$propertyWhereQuery);
	$propertyWhereQuery = str_replace('"railway"',"T.railway",$propertyWhereQuery);
	$propertyWhereQuery = str_replace('"aeroway"',"T.aeroway",$propertyWhereQuery);
	
	$symbolGradeSql = getSymbolGradeSql();
	$typeQuery = getPropertyTypeQuery($SYMBOL);
return <<<EOD
    SELECT
		T.way AS		
	way,
		$typeQuery AS
	type,
		T.name AS
	name,
		$symbolGradeSql AS
	grade,
		COALESCE(T.historic,'no'::text) AS
	historic,
		COALESCE(T.man_made,'no'::text) AS
	man_made,
		COALESCE(T.tourism,'no'::text) AS
	tourism,	
	    COALESCE(T.amenity,'no'::text) AS
	amenity,
	    COALESCE(T.natural,'no'::text) AS
	"natural",
		COALESCE(leisure,'no'::text) AS
	leisure,		    
		COALESCE(building,'no'::text) AS
	building,
		COALESCE(T.ruins,'no'::text) AS
	ruins,	
	"tower:type",
	information,	
		COALESCE(T.place_of_worship,'no'::text) AS
	place_of_worship,
		COALESCE(T."place_of_worship:type",'no'::text) AS
	"place_of_worship:type",
		COALESCE(T.castle_type,'no'::text) AS
	castle_type,
		COALESCE(T.railway,'no'::text) AS
	railway,
		COALESCE(T.highway,'no'::text) AS
	highway,
	    COALESCE(T.aeroway,'no'::text) AS
	aeroway,
		COALESCE(T.way_area,0) AS
	way_area,	
	wikipedia,
	website,
	power,
	NULL AS wiki,
	T.osm_id
    FROM (
	SELECT osm_id,name,way,historic,man_made,tourism,amenity,"natural",leisure,building,ruins,"tower:type",information,place_of_worship,"place_of_worship:type",castle_type,railway,highway,aeroway,power,0 AS way_area, wikipedia, website, z_order FROM symbol
    ) AS T    
    WHERE
		    ($propertyWhereQuery)			
	    AND ($where)
    ORDER BY $symbolGradeSql DESC, COALESCE(T.way_area,0) DESC, $order
EOD;
}
