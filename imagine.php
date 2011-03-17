<?
function makePreview($id, $full_path) {
	global $previewDir;

	$newPath = $previewDir.$id.".jpg";

	$filename = basename($full_path);

	$ext = get_ext($filename); //get extension
	$worked = convert($full_path, $newPath, $ext);

	return $worked;
}

function get_ext($file) {
	$pos = strpos($file, ".");
	$extension  = substr($file, $pos + 1);
	return $extension;
}

function convert($path, $newPath, $extension) {
	$success = true;//Initialize

	switch($extension) {
		
		case "jpeg":
			$cmd = "convert ".$path." -resize 350x350\> ".$newPath;
			exec($cmd);			
			break;
	
		case "jpg":
			$cmd = "convert ".$path." -resize 350x350\> ".$newPath;
			exec($cmd);
			break;
	
		case "png":
			$cmd = "convert ".$path." -resize 350x350\> -background white -flatten ".$newPath;
			exec($cmd);
			break;
	
		case "bmp":
			$cmd = "convert ".$path." -resize 350x350\> ".$newPath;
			exec($cmd);
			break;
	
		case "gif":
			$cmd = "convert ".$path." -resize 350x350\> -background white -flatten ".$newPath;
			exec($cmd);
			break;
	
		case "ai":
			$cmd = "convert ".$path." -resize 350x350x\> -background white -flatten ".$newPath;
			exec($cmd);
			break;
	
		case "pdf":
			$cmd = "convert ".$path." -resize 350x350x\> -background white -flatten ".$newPath;
			exec($cmd);
			break;
	
		case "ps":
			$cmd = "convert ".$path." -resize 350x350x\> -background white -flatten ".$newPath;
			exec($cmd);
			break;
	
		case "psd":
			$cmd = "convert ".$path." -resize 350x350x\> -background white -flatten ".$newPath;
			exec($cmd);
			break;

		case "tif":
			$cmd = "convert ".$path." -resize 350x350x\> -background white -flatten ".$newPath;
			exec($cmd);
			break;

		case "eps":
			$cmd = "convert ".$path." -resize 350x350x\> -background white -flatten ".$newPath;
			exec($cmd);
			break;
	
		case "xcf":

		break;
	
		default:
			$success = false;//No compatible extensions

	}
		
	return $success;
}
?>
