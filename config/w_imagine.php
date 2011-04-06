<?php
$available_extensions = Array();
	array_push($available_extensions, "jpeg");
	array_push($available_extensions, "jpg");
	array_push($available_extensions, "png");
	array_push($available_extensions, "bmp");
	array_push($available_extensions, "gif");
	array_push($available_extensions, "ai");
	array_push($available_extensions, "pdf");
	array_push($available_extensions, "ps");
	array_push($available_extensions, "psd");
	array_push($available_extensions, "tif");
	array_push($available_extensions, "eps");

function makePreview($full_path) {
	global $previewDir;


	$filename = basename($full_path);

	$ext = get_ext($filename); //get extension
	$worked = convert($full_path, $ext);

	return $worked;
}

function get_ext($file) {
	$pos = strpos($file, ".");
	$extension  = substr($file, $pos + 1);
	return strtolower($extension);
}

function convert($path, $extension) {
	$success = true;//Initialize
	$newPath = "preview.jpg";

	switch($extension) {
		case "jpeg":
			$cmd = "convert ".$path." -resize 350x350 ".$newPath;
			exec($cmd);			
			break;
	
		case "jpg":
			$cmd = "convert ".$path." -resize 350x350 ".$newPath;
			exec($cmd);
			break;
	
		case "png":
			$cmd = "convert ".$path." -resize 350x350 -background white -flatten ".$newPath;
			exec($cmd);
			break;
	
		case "bmp":
			$cmd = "convert ".$path." -resize 350x350 ".$newPath;
			exec($cmd);
			break;
	
		case "gif":
			$cmd = "convert ".$path." -resize 350x350 -background white -flatten ".$newPath;
			exec($cmd);
			break;
	
		case "ai":
			$cmd = "convert ".$path." -resize 350x350 -background white -flatten ".$newPath;
			exec($cmd);
			break;
	
		case "pdf":
			$cmd = "convert ".$path."[0] -resize 350x350 ".$newPath;
			exec($cmd);
			break;
	
		case "ps":
			$cmd = "convert ".$path." -resize 350x350 -background white -flatten ".$newPath;
			exec($cmd);
			break;
	
		case "psd":
			$cmd = "convert ".$path."[0] ".$newPath;
			exec($cmd);
			$cmd = "convert ".$newPath." -resize 350x350 ".$newPath;
			exec($cmd);
			break;

		case "tif":
			$cmd = "convert ".$path." -resize 350x350 -background white -flatten ".$newPath;
			exec($cmd);
			break;

		case "eps":
			$cmd = "convert ".$path." -resize 350x350 -background white -flatten ".$newPath;
			exec($cmd);
			break;
	
	
		default:
			$success = false;//No compatible extensions

	}
		
	return $success;
}
?>
