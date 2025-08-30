<?php
// This file is generated. Do not modify it manually.
return array(
	'pr-bloc-recherche' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 2,
		'name' => 'mon-theme/pr-bloc-recherche',
		'version' => '1.0.0',
		'title' => 'PR Bloc Recherche',
		'category' => 'widgets',
		'icon' => 'search',
		'description' => 'Un bloc pour filtrer dynamiquement différents types de contenus',
		'textdomain' => 'mon-theme',
		'editorScript' => 'file:./build/index.js',
		'editorStyle' => 'file:./build/editor.css',
		'style' => 'file:./build/style.css',
		'attributes' => array(
			'target' => array(
				'type' => 'string',
				'default' => '.item'
			),
			'searchIn' => array(
				'type' => 'string',
				'default' => ''
			),
			'placeholder' => array(
				'type' => 'string',
				'default' => 'Rechercher'
			),
			'isLive' => array(
				'type' => 'boolean',
				'default' => true
			),
			'customClass' => array(
				'type' => 'string',
				'default' => ''
			),
			'context' => array(
				'type' => 'string',
				'default' => 'custom'
			)
		)
	)
);
