<?php 
function GDLibrary($files, $que_ancho_nuevo, $que_ruta_base){
	$ruta_temporal = $files["imagen"]["tmp_name"];
	switch($files['imagen']['type']) {
		case 'image/gif':
			$extension = "gif";
			break;
		case 'image/png':
			$extension = "png";
			break;
		case 'image/jpeg':
		case 'image/pjpeg':
			$extension = "jpg";
			break;
	}

	$foto_original = @imagecreatefromstring(file_get_contents($ruta_temporal));
	if ($foto_original === false) {
	    return [
			'status' => "error",
			'errors' => "El archivo ingresado esta corrupto, por favor ingrese otra foto."
		];
	}
	
	try {
		list($ancho_original, $alto_original) = getimagesize($ruta_temporal);
		$nuevo_ancho = $que_ancho_nuevo;
		$nuevo_alto = round( $nuevo_ancho * $alto_original / $ancho_original );

		$nueva_foto = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
		imagecopyresampled($nueva_foto, $foto_original, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho_original, $alto_original);

		$nombreFinal = md5($ruta_temporal);
		$rutaCasiFinal = $nombreFinal.'.'.$extension; // esta es la ruta para subir en la base de datos
		$rutaFinal = $que_ruta_base.$nombreFinal.'.'.$extension; // esta es la ruta para subir en el sitio fisicamente

		if ( $extension == "jpg" ){
			imagejpeg($nueva_foto, $rutaFinal , 100);
		} else if ( $extension == "png" ){
			imagepng($nueva_foto, $rutaFinal , 9);
		} else if ( $extension == "gif" ){
			imagegif($nueva_foto, $rutaFinal , 100);
		}			

		imagedestroy($foto_original);
		imagedestroy($nueva_foto);

		return [
			'status' => "success",
			'nombreImagen' => $rutaCasiFinal
		];
	} catch(Exception $e) {
		return $e->getMessage();
	}
}
?>