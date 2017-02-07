<?php

$options = getopt('n::');

$ns = isset($options['n'])? $options['n'] : 'wub';

if ($ns) {
    $out = "<?php\n\nnamespace $ns;\n";
} else {
    $out = "<?php\n\n";
}

$files = [
	__DIR__.'/src/arr.php',
	__DIR__.'/src/io.php',
	__DIR__.'/src/str.php',
	__DIR__.'/src/wub.php',
];
foreach ($files as $file) {
	$content = file_get_contents($file);
	$content = str_replace("<?php\n\nnamespace wub;", '', $content);
	$out .= $content;
}

echo $out;