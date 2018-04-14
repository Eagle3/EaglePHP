<?php

namespace lib\system;

class Mp3ToWav {    
    public static function run($mp3file) {
        $url = substr($mp3file, 0, -4);
        $wavFile = $url . '.wav';
        if (file_exists($wavFile) == true) {
            return $wavFile;
        } else {
            //$command = "/usr/local/bin/ffmpeg -i {$this->mp3file} {$wavFile}"; //Linux
            $command = "D:/ffmpeg/bin/ffmpeg.exe -i {$mp3file} {$wavFile}"; //Windows
            system($command, $error);
        }
        return $wavFile;
    }
}