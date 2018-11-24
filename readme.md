Note on audio processing

* reduce sample rate and channel no

	sox input.wav -r 22500 -c 1 output.wav


* acquire noise profile

	sox output.wav -n trim 0 1 noiseprof profile


* apply noise profile

	sox output.wav clean.wav noisered profile 0.33


* transform into mp3

	lame -b 96 -h output.wav output.mp3