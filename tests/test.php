<?php 
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload
/**
 * @author amine safsafi
 * @category File
 */
$senpai_option = \WP_SENPAI\Utils\JSON('senpai_option');
$senpai_option->set('site_title','SENPAI WEBSITE');
$senpai_option->save();
$var = $senpai_option->get('site_title');
$senpai_all = $senpai_option->get_all();
$senpai_option->reset();

