<?php

function bucket_icon($bucketType) {
	switch($bucketType) {
		case App\PH\C::BUCKET_TYPE_AUDIO: return 'fa-microphone-alt';
	}
}
