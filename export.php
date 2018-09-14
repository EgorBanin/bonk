<?php

$options = getopt('n::s');

$ns = isset($options['n'])? $options['n'] : 'wub';
$s = isset($options['s'])? true : false;

if ($ns) {
    $out = "<?php\n\nnamespace $ns;\n";
} else {
    $out = "<?php\n\n";
}

$out .= '
/**
 * Набор полезных функций
 * @link https://github.com/EgorBanin/wub
 */
';

$files = [
	__DIR__.'/src/arr.php',
	__DIR__.'/src/io.php',
	__DIR__.'/src/str.php',
	__DIR__.'/src/wub.php',
	__DIR__.'/src/http.php',
	__DIR__.'/src/file.php',
	__DIR__.'/src/obj.php',
];
foreach ($files as $file) {
	$content = file_get_contents($file);
	$content = str_replace("<?php\n\nnamespace wub;", '', $content);
	$out .= $content;
}

if ($s) {
	$out = str_replace('	', '    ', $out);
}

echo $out;
