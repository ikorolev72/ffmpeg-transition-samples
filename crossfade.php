<?php
// code sample for http://ffmpeg.unixpin.com
// #animation
// /korolev-ia@yandex


$output_height = 720;
$output_width = 1280;
$duration = 5;
$inputOne="video1.mp4";
$inputTwo="video2.mp4";
$output = "crossfade.mp4";
$fadeDuration=2;
$video1Duration=5;
$video2Duration=5;


$bgDuration=$video1Duration+$video2Duration-$fadeDuration;
$startFade=$video1Duration-$fadeDuration;


$filters = '';
$filters .= " [0:v] scale=w=min(iw*${output_height}/ih\,${output_width}):h=min(${output_height}\,ih*${output_width}/iw), ";
$filters .= " pad=w=${output_width}:h=${output_height}:x=(${output_width}-iw)/2:y=(${output_height}-ih)/2, ";
$filters .= " fade=out:st=$startFade:d=$fadeDuration:alpha=1, setpts=PTS-STARTPTS  [video1];  ";

$filters .= " [1:v] scale=w=min(iw*${output_height}/ih\,${output_width}):h=min(${output_height}\,ih*${output_width}/iw), ";
$filters .= " pad=w=${output_width}:h=${output_height}:x=(${output_width}-iw)/2:y=(${output_height}-ih)/2, ";
$filters .= " fade=in:st=0:d=$fadeDuration:alpha=1, setpts='PTS+$startFade/TB' [video2];  ";

$filters .= " [video1][video2]overlay[v]";
$cmd = "ffmpeg -y -i $inputOne -i $inputTwo  ";
$cmd .= "-filter_complex \"$filters\" -map \"[v]\" -c:v h264 -crf 18 -preset veryfast $output";
print $cmd;

exec($cmd);
