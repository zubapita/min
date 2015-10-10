#!/usr/local/bin/php
<?php
	$hbars = [];
	$hbars[] =  json_decode('["\u2010"]', true)[0];
	$hbars[] =  json_decode('["\u2011"]', true)[0];
	$hbars[] =  json_decode('["\u2012"]', true)[0];
	$hbars[] =  json_decode('["\u2013"]', true)[0];
	$hbars[] =  json_decode('["\u2014"]', true)[0];
	$hbars[] =  json_decode('["\u2015"]', true)[0];
	$hbars[] =  json_decode('["\u2212"]', true)[0];
	$hbars[] =  json_decode('["\u30FC"]', true)[0];
	$hbars[] =  json_decode('["\uFF70"]', true)[0];
	$hbars[] =  json_decode('["\u4e00"]', true)[0];
	var_dump($hbars);
?>