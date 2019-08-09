<?php

	// Theme setup
	class UAMSPhysicians
	{
	  	const VERSION = 0.1;
	  	function __construct()
	  	{
	   		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
	   		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
	  	}

	  	function styles()
	  	{
		  	//Custom Style Sheets go here
		  	// wp_register_style( 'uamspeople', get_stylesheet_directory_uri() . '/uamspeople.css' );
		  	// wp_enqueue_style('uamspeople');

	  	}

		function scripts()
		{
		    // Custom Scripts go here
		      // Google Maps API
		      //wp_register_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?signed_in=true' );
		      // YouTube iFrame API
		      //wp_register_script( 'youtube-iframe-api', 'https://www.youtube.com/player_api' );
		      // Example
		      //wp_register_script( 'name', get_bloginfo("stylesheet_directory") . '/js/name.js' );
		      // UAMSPeople App and dependecies
		      //wp_register_script( 'UAMSPeople', get_stylesheet_directory_uri() . '/js/uamspeople.js', array( 'youtube-iframe-api', 'name' ), self::VERSION );
		      //wp_enqueue_script( 'UAMSPeople' );
		}
	}
	new UAMSPhysicians;