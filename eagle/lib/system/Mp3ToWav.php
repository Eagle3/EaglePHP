<?php

namespace lib\system;

class Mp3ToWav {
    private $mp3file = '';
    public function __construct($mp3file){
        $this->mp3file = $mp3file;
    }
    
    public function run() {
        $url = substr($this->mp3file, 0, -4);
        $wavFile = $url . '.wav';
        if (file_exists($wavFile) == true) {
            return $wavFile;
        } else {
            //$command = "/usr/local/bin/ffmpeg -i {$this->mp3file} {$wavFile}"; //Linux
            $command = "D:/ffmpeg/bin/ffmpeg.exe -i {$this->mp3file} {$wavFile}"; //Windows
            system($command, $error);
        }
        return $wavFile;
    }
}