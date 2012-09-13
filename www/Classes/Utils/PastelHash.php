<?


# PASTEL HASH
# Unique image for everything
# Goga 2012


$COLORS = array('77C4D3', 'F6F792', 'EA2E49', 'FF530D', 'FF0000', '023B47', '295E52', 'A4DEAB', '499E8D', 'FFF6C9', 'FF862B');
if(($LINES = md5_hex_to_dec($_GET['hash'])+1) < 4)
	$LINES = 4;

$hashArray = str_split($_GET['hash']);
		array_unique($hashArray);
			$hash = implode('', $hashArray);

if(($W = $_GET['W']) && ($H = $_GET['H']) && ($hash = $_GET['hash'])){	
	if(isset($_GET['opt']))
	 $opt = trim($_GET['opt']);
	else
	 $opt = '';

	# check if image already exist
	if(file_exists($filename = $_SERVER['DOCUMENT_ROOT']."/thumbs/pastelhash_".$hash."_".$W."x".$H."_opt_".$opt)){
		header( "Content-type: image/png" );
		echo file_get_contents($filename);
		 return;
	}

	$last_c = null;

	# no image found

		$im = imagecreatetruecolor($W, $H);

			/* if(md5_hex_to_dec($_GET['hash']) % 2 == 0){
			 	# horizontal lines
			 		$line_width = $H/$LINES;	
						for($i = 0; $i < $LINES; $i++){	
							if(md5_hex_to_dec(md5($hash[$i])) == $last_c)
								$color =  10;
							else
								$color = md5_hex_to_dec(md5($hash[$i]));
							$last_c =  $color;

							imagefilledrectangle($im, 0, $line_width*$i, $W, $line_width*($i+1), 
								hexColorAlloc(
										$im,
										$COLORS[$color]
									)			
							);		
						}
			 }else{*/
			 	# vertical lines
			 		$line_width = $W/$LINES;	
						for($i = 0; $i < $LINES; $i++){	
							if(md5_hex_to_dec(md5($hash[$i])) == $last_c)
								$color =  10;
							else
								$color = md5_hex_to_dec(md5($hash[$i]));
							$last_c =  $color;

							imagefilledrectangle($im, $line_width*$i, 0, $line_width*($i+1), $H, 
								hexColorAlloc(
										$im,
										$COLORS[$color]
									)			
							);		
						}
			/* }*/
			if(stristr($opt, "grayscale") !== false)
				imagefilter($im, IMG_FILTER_GRAYSCALE);

	header( "Content-type: image/png" );
		imageinterlace($im, true);

			imagepng( $im, $filename );
		 	imagepng( $im);
		 		imagedestroy($im);		 
}


function hexColorAlloc($im,$hex){ 
  $a = hexdec(substr($hex,0,2)); 
  $b = hexdec(substr($hex,2,2)); 
  $c = hexdec(substr($hex,4,2)); 
  return ImageColorAllocate($im, $a, $b, $c); 
} 

function md5_hex_to_dec($hex_str)
{
    $arr = str_split($hex_str, 4);
    foreach ($arr as $grp) {
        $dec[] = str_pad(hexdec($grp), 5, '0', STR_PAD_LEFT);
    }
    return $dec[0][1];
}



?>