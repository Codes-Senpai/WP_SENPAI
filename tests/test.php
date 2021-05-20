<?php 
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

$options_themes = new \WP_SENPAI\Utils\JSON('option_name');

$options_themes->set('n1','v1');
$options_themes->save();
$options_themes->get('n1');
$options_themes->get_all();
$options_themes->reset();