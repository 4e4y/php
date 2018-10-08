<?php

if (count($argv) < 2)
{
	die ('Input file is not provided ...');
}

$filename = $argv[1];
if (!file_exists($filename))
{
	die ("File $filename does not exists ...");
}

$handle = fopen($filename, "r");
if (!$handle)
{
	die ("Error processing file $filename ...");
}

while (($line = fgets($handle)) !== false) {
	$idData = explode(",", $line);
	
	if (count($idData) < 2)
	{
		echo "Invalid data found: $line" . PHP_EOL;
		continue;
	}
	
	$id = trim($idData[0]);
	$reportDate = trim($idData[1]);
	
	if (!is_numeric(trim($id)))
	{
		echo "Invalid id: $id found ..." . PHP_EOL;
		continue;
	}
	
	$date = DateTime::createFromFormat('Y-n-j', $reportDate);
	if(DateTime::getLastErrors()['warning_count'] > 0)
	{
		echo "Invalid report Date: $reportDate found ..." . PHP_EOL;
		continue;
	}
	
	if (!$date || $date->format('Y-n-j') === $reportDate)
	{
		echo "Invalid report Date: $reportDate found ..." . PHP_EOL;
		continue;
	}
}

fclose($handle);

echo 'Done ...';