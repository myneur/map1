<?php $svg = <<<EOD
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<svg  
  xmlns="http://www.w3.org/2000/svg"
  version="1.1"
  width="$size"
  height="$size"  
  viewBox="0 0 $viewbox $viewbox"> 
    
  <g transform="scale(0.75,0.75)" style="opacity: $opacity">
	<g transform="translate(-300,-135)">
      <path style="fill:#000000;stroke:none" d="m 374.12653,287.28223 2.52538,5.8084 4.79822,4.54573 5.55584,0.7576 c 0,0 3.283,-5.80843 3.53554,-6.81853 0.25254,-1.0102 1.51523,-6.31354 1.76776,-8.33384 0.25254,-2.0203 6.06092,-13.637 7.07107,-15.1523 1.01015,-1.5152 9.84899,-14.6472 9.84899,-14.6472 0,0 5.80838,-9.09139 6.06091,-10.35399 0.25254,-1.2627 5.55584,-5.8084 5.55584,-5.8084 l 11.36422,-13.3845 c 0,0 1.26269,8.3337 1.51523,10.354 0.25254,2.0203 1.01015,9.3439 1.51523,12.3744 0.50507,3.03039 0.25254,8.58629 0,10.10149 -0.25254,1.5152 -2.27285,13.3845 -2.27285,13.3845 0,0 -3.78807,2.778 -2.77792,5.8084 1.01016,3.0305 -0.25253,26.26407 -0.25253,27.27417 0,1.0102 -3.78808,12.8795 -3.78808,12.8795 l -3.78807,16.1624 c 0,0 -2.27284,11.1117 -1.76776,12.6269 0.50507,1.5153 1.01015,16.6675 1.01015,16.6675 l 2.77792,14.6473 c 0,0 1.76776,4.2931 3.78807,3.5355 2.0203,-0.75759 10.35406,0.2525 10.85914,-1.5152 0.50508,-1.76779 2.77792,-6.566 3.283,-7.57619 0.50507,-1.01011 6.56599,-11.36421 6.56599,-11.36421 l 2.27284,-10.35409 c 0,0 -0.75761,-6.06091 0,-7.07101 0.75761,-1.0102 4.54569,-10.6066 4.54569,-10.6066 l 1.26269,-3.0305 0,9.5965 0.25254,9.0913 -1.76777,12.3744 c 0,0 0,7.3236 0.75761,8.8388 0.75762,1.5153 2.27285,5.8084 2.02031,6.8186 -0.25254,1.0101 -1.76777,4.5457 -2.02031,5.8083 -0.25254,1.2627 -1.76776,7.8287 -1.51523,9.0914 0.25254,1.2627 1.76777,6.56604 2.02031,7.57625 0.25254,1.01009 2.52538,21.9708 2.52538,21.9708 l 0.75761,15.65726 -2.52538,7.0711 1.76777,9.5965 c 0,0 -1.26269,1.5152 0.25254,1.5152 1.51523,0 11.11168,2.0203 11.11168,2.0203 l 5.3033,-2.2729 -0.50508,-4.04059 c 0,0 -3.53553,-5.8083 -4.29315,-7.07101 -0.75761,-1.2627 -2.27284,-2.5254 -2.27284,-4.2932 0,-1.7677 0.75761,-12.8794 0.75761,-12.8794 l 4.54569,-20.96066 2.27284,-14.14215 -0.50507,-10.1015 -1.76777,-7.57619 -2.77792,-7.0711 2.52538,-11.3642 9.09138,-19.95051 7.07106,-21.2132 c 0,0 4.54569,-27.02167 4.04061,-28.03177 -0.50507,-1.0102 -3.78807,-11.3642 -3.78807,-11.3642 0,0 -2.0203,-6.06099 -2.0203,-7.0711 0,-1.0102 0.25253,-9.5965 0.25253,-11.1117 0,-1.51519 2.27285,-13.13199 2.27285,-13.13199 l 3.53553,-13.637 3.03046,-7.5762 15.9099,11.6168 c 0,0 8.83884,3.5355 9.84899,4.5457 1.01015,1.0101 19.1929,7.071 19.1929,7.071 l 4.29315,0.7576 -4.54569,0.7577 2.77792,5.05069 c 0,0 2.27284,0 1.51523,-1.0101 -0.75762,-1.0102 -1.51523,-1.51529 -0.50508,-2.52539 1.01015,-1.0102 4.29315,-3.5355 4.29315,-3.5355 l 2.77792,-2.778 1.01015,-5.0507 c 0,0 3.53554,-4.0406 2.02031,-4.0406 -1.51523,0 -9.84899,2.0203 -9.84899,2.0203 0,0 -7.57614,1.2627 -9.09137,0.2525 -1.51523,-1.0101 -8.5863,-4.2931 -8.5863,-5.8084 0,-1.5152 -14.64721,-14.1421 -14.64721,-14.1421 l -6.56599,-4.0406 c 0,0 -0.25254,-10.1015 -2.02031,-11.1117 -1.76777,-1.0101 -7.82868,-4.2931 -9.59645,-4.0406 -1.76777,0.2525 -5.3033,-1.0102 -5.3033,-1.0102 l -9.34391,-7.071 0.75761,-5.3033 c 0,0 1.01016,-1.0102 2.77792,-1.7678 1.76777,-0.7576 3.78808,-4.7982 4.04061,-5.8084 0.25254,-1.0101 -0.75761,-6.8185 -1.01015,-7.8287 -0.25254,-1.0101 -3.03046,-8.3337 -4.29315,-10.1015 -1.26269,-1.7677 -3.78807,-4.2931 -6.06091,-5.0507 -2.27285,-0.7577 -3.78807,-1.2627 -5.80838,-1.7678 -2.0203,-0.5051 -2.77792,-1.2627 -6.31345,0 -3.53554,1.2627 -6.56599,1.5152 -6.81853,2.7779 -0.25254,1.2627 -0.50508,2.5254 -0.75762,3.7881 -0.25253,1.2627 -0.25253,2.7779 -0.50507,4.0406 -0.25254,1.2627 -0.50508,3.5355 -1.01016,5.3033 -0.50507,1.7678 -0.25253,1.7678 -0.50507,3.7881 -0.25254,2.0203 -0.25254,3.0304 0.25254,5.8083 0.50507,2.778 0,4.2932 1.26269,5.8084 1.26269,1.5153 2.0203,0.7576 1.51523,3.283 -0.50508,2.5254 -0.75762,3.7881 -1.76777,4.0406 -1.01015,0.2526 -6.56599,-0.505 -6.56599,-0.505 l -4.54569,3.0304 c 0,0 -3.53553,4.5457 -5.3033,4.5457 -1.76777,0 -3.53553,-0.5051 -7.32361,0.7576 -3.78807,1.2627 -11.36421,12.1218 -11.86929,13.132 -0.50507,1.0101 -5.80838,7.8287 -5.80838,7.8287 0,0 -3.03045,2.5254 -3.03045,5.3033 0,2.7779 -0.25254,5.8084 -1.51523,6.0609 -1.26269,0.2525 -8.5863,12.1218 -8.5863,12.1218 0,0 -5.05076,6.0609 -5.55584,7.0711 -0.50507,1.0101 -4.79822,10.60659 -4.79822,10.60659 l -7.32361,17.67771 -2.0203,5.55579 -8.33376,6.81854 z" id="path7112"/>
    </g>
 </g>
</svg>
EOD;