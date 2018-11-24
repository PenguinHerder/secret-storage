<?php

function bucket_icon($bucketType) {
	switch($bucketType) {
		case App\PH\C::BUCKET_TYPE_AUDIO: return 'fa-microphone-alt';
	}
}

function getMaximumFileUploadSize() {  
    return min(convertPHPSizeToBytes(ini_get('post_max_size')), convertPHPSizeToBytes(ini_get('upload_max_filesize')));  
}  

function convertPHPSizeToBytes($sSize) {
    $sSuffix = strtoupper(substr($sSize, -1));
    if (!in_array($sSuffix,array('P','T','G','M','K'))){
        return (int)$sSize;  
    } 
	
    $iValue = substr($sSize, 0, -1);
    switch ($sSuffix) {
        case 'P':
            $iValue *= 1024;
        case 'T':
            $iValue *= 1024;
        case 'G':
            $iValue *= 1024;
        case 'M':
            $iValue *= 1024;
        case 'K':
            $iValue *= 1024;
            break;
    }
	
    return (int)$iValue;
}

function bytesToHuman(int $bytes) {
	$steps = [
		'GB' => 2 ** 30,
		'MB' => 2 ** 20,
		'KB' => 2 ** 10,
		'B' => 1,
	];
	
	foreach($steps as $suffix => $step) {
		if($bytes >= $step) {
			return round($bytes / $step, 2) . $suffix;
		}
	}
	
	return '0';
}

function secondsToHuman(int $seconds) {
	$minutes = floor($seconds / 60);
	$secs = $seconds % 60;
	
	return str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($secs, 2, '0', STR_PAD_LEFT);
}
