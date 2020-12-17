<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 19.06.2020
 */

namespace Fw\Components\Modules\TemplateEngine;


class CssKit
{

	public function __construct()
	{
	}

	
	protected $root = [
		"font-size" => "var(--fz)",

		"--ff-0"	=> "\"PT Sans\", sans-serif",
		"--ff-1"	=> "\"PT Sans\", sans-serif",
		"--ff-fab"	=> "'Font Awesome 5 Brands'",
		"--ff-fal"	=> "\"Font Awesome 5 Pro\"",
		"--ff-far"	=> "\"Font Awesome 5 Pro\"",
		"--ff-fa"	=> "\"Font Awesome 5 Pro\"",
		"--ff-fas"	=> "\"Font Awesome 5 Pro\"",

		"--ff-h1"	=> "var(--ff-1)",
		"--ff-h2"	=> "var(--ff-1)",
		"--ff-h3"	=> "var(--ff-1)",
		"--ff-h4"	=> "var(--ff-1)",
		"--ff-p"	=> "var(--ff-0)",
		"--ff-h5"	=> "var(--ff-0)",
		"--ff-h6"	=> "var(--ff-0)",

		"--fz"  	=> "16px",

		"--fz-h1"	=> "2.5rem",
		"--fz-h2"	=> "2rem",
		"--fz-h3"	=> "1.5rem",
		"--fz-h4"	=> "1.25rem",
		"--fz-p" 	=> "1rem",
		"--fz-h5"	=> ".875rem",
		"--fz-h6"	=> ".75rem",

		"--fw-h1"	=> "bold",
		"--fw-h2"	=> "bold",
		"--fw-h3"	=> "bold",
		"--fw-p" 	=> "normal",
		"--fw-h4"	=> "normal",
		"--fw-h5"	=> "normal",
		"--fw-h6"	=> "normal",

		"--m-h1"	=> "2rem",
		"--m-h2"	=> "2rem",
		"--m-h3"	=> "1rem",
		"--m-p" 	=> "1rem",
		"--m-h4"	=> ".5rem",
		"--m-h5"	=> ".5rem",
		"--m-h6"	=> ".5rem",
		
		/* aqua */
		"--c-turquoise"	    => "26,188,156",
		"--c-greensea"	    => "22,160,133",
		/* green */
		"--c-emerland"	    => "46,204,113",
		"--c-nephritis"	    => "39,174,96",
		/* blue */
		"--c-peterriver"	=> "52,152,219",
		"--c-belizehole"	=> "41,128,185",
		/* purple */
		"--c-amethyst"	    => "155,89,182",
		"--c-wisteria"	    => "142,68,173",
		/* dark */
		"--c-wetasphalt"	=> "52,73,94",
		"--c-midnightblue"	=> "44,62,80",
		/* yellow */
		"--c-sunflower"	    => "241,196,15",
		"--c-orange"	    => "243,156,18",
		/* orange */
		"--c-carrot"	    => "230,126,34",
		"--c-pumpkin"	    => "211,84,0",
		/* red */
		"--c-alizarin"	    => "231,76,60",
		"--c-pomegranate"	=> "192,57,43",
		/* lightgrey */
		"--c-clouds"	    => "236,240,241",
		"--c-silver"	    => "189,195,199",
		/* darkgrey */
		"--c-concrete"	    => "149,165,166",
		"--c-asbestos"	    => "127,140,141",
		/* white */
		"--c-w"	    => "255,255,255",
		"--c-w-i"	=> "0,0,0",
		/* black */
		"--c-b"	    => "0,0,0",
		"--c-b-i"	=> "255,255,255",
		/* gray */
		"--c-g"	    => "var(--c-silver)",
		"--c-g-i"	=> "0,0,0",
		/* 1 */
		"--c-1"	    => "var(--c-alizarin)",
		"--c-1-i"	=> "255,255,255",
		/* 2 */
		"--c-2"	    => "var(--c-peterriver)",
		"--c-2-i"	=> "255,255,255",
		/* 3 */
		"--c-3"	    => "var(--c-emerland)",
		"--c-3-i"	=> "255,255,255",
		/* 4 */
		"--c-4"	    => "var(--c-sunflower)",
		"--c-4-i"	=> "0,0,0",
		/* 5 */
		"--c-5"	    => "var(--c-amethyst)",
		"--c-5-i"	=> "255,255,255",
	];
}