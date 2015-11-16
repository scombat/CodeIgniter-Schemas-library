<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package CodeIgniter-Schemas-library
 * @version 15.11.14
 *  
 * @author Steve Combat < steve@castoretpollux.com / scombat@student.42.fr >
 * @copyright Copyright (c) 2015, Steve Combat
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License Version 2.0, January 2004
 * 
 * @link https://github.com/scombat/CodeIgniter-Schemas-library
 * 
 * @todo Event for Performers
 * @todo Event for Venues
 * @todo Event for Ticketers
 * @todo Music play actions
 * @todo Movies watch actions
 * @todo Promote critic reviews
 * @todo Rich Snippets for Products
 * @todo Rich Snippets for Recipes
 * @todo Rich Snippets for Reviews
 * @todo Rich Snippets for Events
 * @todo Rich Snippets for Software Apps
 * @todo Rich Snippets for Videos
 * @todo Rich Snippets for Articles
 * @todo Testing tools
 * @todo Preview results
 * @todo Live Preview results
 * @todo Wysiwyg Editor
 * @todo Microdata support
 * @todo RDFA support
 * @todo Automatic generation
 * 
 * CodeIgniter-Schemas-library v15.11.14
 * 
 * 
 * Schemas Support for now :
 * 		[Data model] :
 *   		• JSON-LD
 * 
 * 		Knowledges Graphs :
 * 			• Logos
 *   		• Corporate Contacts
 *    		• Social Profile links
 * 
 *      Search Result Styles :
 *      	• BreadCrumbs
 *       	• Site Name
 */
class Schemas {

	private $ci;
	public $schema;
	public $breadcrumb;
	public $sitename;
	private $config;
	private $socials_position = array(
		"Facebook"		=>	null,
		"Twitter"		=>	null,
		"Google+"		=>	null,
		"Instagram"		=>	null,
		"YouTube"		=>	null,
		"LinkedIn"		=>	null,
		"Myspace"		=>	null,
		"Pinterest"		=>	null,
		"SoundCloud"	=>	null,
		"Tumblr"		=>	null
	);

	public function __construct()
	{
		// get main ci instanse
		$this->ci =& get_instance();

		// load helpers
		$this->ci->load->helper('url');

		// load schemas dependencies
		$this->config = $this->ci->config->load('schemas');
		$this->ci->lang->load('schemas');

		// Initialize schema object
		$this->schema = new stdClass();
		$this->schema->url = base_url();
	}

	/**
	 * Update the Schema context
	 * @param string $context 'http://schema.org'
	 */
	public function set_context($context)
	{
		$this->schema->{'@Context'} = $context;
	}

	/**
	 * Update the Schema type
	 * @param string $type 'Product / Organisation...' (see schemas config file)
	 */
	public function set_type($type)
	{
		$this->schema->{'@type'} = $type;
	}

	/**
	 * Define the logo
	 * @param string $logo filename to the image
	 */
	public function set_logo($logo)
	{
		$this->schema->logo = $logo;
	}

	/**
	 * Add a contact point to the current schema
	 * @param mixed  $telephone          phone number (international ready)
	 * @param string  $contactType        like google defined type
	 * @param mixed $areaServed         string or array of The geographical region served by the number, specified as a Schema.org/AdministrativeArea. Countries may be specified concisely using just their standard ISO-3166 two-letter code, as in the examples at right. If omitted, the number is assumed to be global.
	 * @param string $contactOption      Optional details about the phone number. Currently only the two values shown at config file are supported.
	 * @param mixed $availableLanguages Optional details about the language spoken. Languages may be specified by their common English name. If omitted, the language defaults to English.
	 * @return mixed Void/Int Void if script is stopped by input validation. Int corresponding of the contact index if contact is valid.
	 */
	public function add_contact($telephone, $contactType, $areaServed = false, $contactOption = false, $availableLanguages = false)
	{
		// Contact validation
		// Phone number validation (international ready)
		if ( !preg_match('/(?:[+])?[0-9]+(?:[ \/.,|-]?[(]?[ 0-9][)]?)/', $telephone) )
		{
			show_error($this->ci->lang->line('schema_contact_bad_phone'));
			die();
		}

		// Contact Type validation
		if ( !in_array(strtolower($contactType), $this->config["allowedContactTypes"]) )
		{
			show_error($this->ci->lang->line('schema_contact_bad_contact_type'));
			die();
		}

		// Area served validation
		if ( $areaServed !== false )
		{
			// if input is array
			if ( is_array($areaServed) )
			{
				// for each areas
				foreach ($areaServed as $index => $area) {
					if ( !in_array($area, $this->config["allowedAreasServed"]) )
					{
						show_error($this->ci->lang->line('schema_contact_bad_served_area'));
						die();
					}
				}
			// else if a string
			} else {
				if ( !in_array($areaServed, $this->config["allowedAreasServed"]) )
				{
					show_error($this->ci->lang->line('schema_contact_bad_served_area'));
					die();
				}
			}
		}

		// Contact Option validation
		if ( $contactOption !== false && !in_array($contactOption, $this->config["allowedContactOption"]) )
		{
			show_error($this->ci->lang->line('schema_contact_bad_option'));
			die();
		}

		// Available languages validation
		if ( $availableLanguages !== false )
		{
			if ( is_array($availableLanguages) ) {
				foreach ($availableLanguages as $index => $language) {
					if ( !in_array(strtolower($language), $this->config["allowedContactLanguages"]) )
					{
						show_error($this->ci->lang->line('schema_contact_bad_language'));
						die();
					}
				}
			} else {
				if ( !in_array(strtolower($availableLanguages), $this->config["allowedContactLanguages"]) )
				{
					show_error($this->ci->lang->line('schema_contact_bad_language'));
					die();
				}
			}
		}

		// If contact point isn't already defined initialize it
		if ( !property_exists($this->schema, "contactPoint") )
			$this->schema->contactPoint = array();

		// Create the contact object and add properties
		$contact = new stdClass();
		$contact->{"@type"} = "ContactPoint";
		$contact->telephone = $telephone;
		$contact->contactType = $contactType;

		if ( $areaServed !== false )
			$contact->areaServed = $areaServed;

		if ( $contactOption !== false )
			$contact->contactOption = $contactOption;

		if ( $availableLanguages !== false )
			$contact->availableLanguage = $availableLanguages;

		$this->schema->contactPoint[] = $contact;

		// return the index of last contact
		return ( count($this->schema->contactPoint) - 1);
	}

	/**
	 * Add a social link.
	 * @param mixed $profile_url If a valid url is provided return the index of added profile else return false
	 */
	public function add_social($profile_url)
	{
		if ( !property_exists($this->schema, "sameAs") )
			$this->schema->sameAs = array();

		if ( is_array($profile_url) )
		{
			foreach ($profile_url as $index => $url) {
				if ( filter_var($url, FILTER_VALIDATE_URL) )
					$this->schema->sameAs[] = $url;
				else {
					show_error($this->ci->lang->line('schema_social_bad_url'));
					die();
				}
			}
		} else if ( filter_var($profile_url, FILTER_VALIDATE_URL) ) {
			$this->schema->sameAs[] = $profile_url;
		} else {
			show_error($this->ci->lang->line('schema_social_bad_url'));
			die();
		}

		return ( count($this->schema->sameAs) - 1 );
	}

	/**
	 * Add a breadcrumb item to the list
	 * @param int $position the position of the breadcrumb item
	 * @param string $id       the url of the item
	 * @param string $name     the name displayed on bot result pages
	 */
	public function add_breadcrumb_item($position, $id, $name)
	{
		// If no breadcrumb exist create a new one by default
		if ( empty($this->breadcrumb) )
			$this->create_breadcrumb();

		// If the new position is already taken return an error
		if ( !$this->breadcrumb_verify_position($position) ) {
			show_error($this->ci->lang->line('schema_breadcrumb_bad_position'));
			die();
		}

		if ( !$this->breadcrumb_verify_item($id) ) {
			show_error($this->ci->lang->line('schema_breadcrumb_bad_id') );
			die();
		}

		$item = new stdClass();
		$item->{'@type'} = 'ListItem';
		$item->position = $position;
		$item->item = new stdClass();
		$item->item->{'@id'} = $id;
		$item->item->name = $name;
		$this->breadcrumb->itemListElement[] = $item;
	}

	/**
	 * Reset current breadcrumb and setup a new fresh
	 * @return void
	 */
	public function clear_breadcrumb()
	{
		$this->create_breadcrumb();
	}

	/**
	 * For each items in breadcrumb compare the new id to the olders.
	 * @param  string $id the new item's id
	 * @return boolean     true if the new id isn't already used else false
	 */
	private function breadcrumb_verify_item($id)
	{
		foreach ($this->breadcrumb->itemListElement as $index => $listElement) {
			if ( isset($listElement->item->{'@id'}) && $id === $listElement->item->{'@id'} )
				return (false);
		}

		return (true);
	}

	/**
	 * For each items in breadcrumb compare the new position to the older.
	 * @param  int $position the new item's position
	 * @return boolean           true if the new position isn't already used else false
	 */
	private function breadcrumb_verify_position($position)
	{
		foreach ($this->breadcrumb->itemListElement as $index => $listElement) {
			if ( $position === $listElement->position )
				return (false);
		}

		return (true);
	}

	/**
	 * Create a new BreadCrumb object or reset the older.
	 * @return void
	 */
	public function create_breadcrumb()
	{
		$this->breadcrumb = new stdClass();

		if ( isset($this->schema->{'@Context'}) && !empty($this->schema->{'@Context'}) )
			$this->breadcrumb->{'@Context'} = $this->schema->{'@Context'};
		else
			$this->breadcrumb->{'@Context'} = "http://schema.org";

		$this->breadcrumb->{'@type'} = "BreadcrumbList";

		$this->breadcrumb->itemListElement = array();
	}

	public function site_name($name, $alt, $url)
	{
		if ( !filter_var($url, FILTER_VALIDATE_URL) )
		{
			show_error($this->ci->lang->line('schema_url_validation'));
			die();
		}

		$site_name = new stdClass();

		if ( isset($this->schema->{'@Context'}) && !empty($this->schema->{'@Context'}) )
			$site_name->{'@Context'} = $this->schema->{'@Context'};
		else
			$site_name->{'@Context'} = "http://schema.org";

		$site_name->{'@type'} = "WebSite";
		$site_name->name = $name;
		$site_name->alternateName = $alt;
		$site_name->url = $url;

		$this->sitename = $site_name;
	}

	public function read_sitename($encoded = null)
	{
		if ( $encoded === null )
		{
			if ( $this->config["encoded"] === true )
				return ( json_encode($this->sitename, JSON_UNESCAPED_SLASHES) );
			return ( $this->sitename );
		} else {
			if ( $encoded === true )
				return ( json_encode($this->sitename, JSON_UNESCAPED_SLASHES) );
			return ( $this->sitename );
		}
	}


	/**
	 * Return current Breadcrumb encoded or not
	 * @param  boolean $encoded optionnal, overwrite the encoded config variable
	 * @return mixed string if encoded config is set to true else object
	 */
	public function read_breadcrumb($encoded = null)
	{
		if ( $encoded === null )
		{
			if ( $this->config["encoded"] === true )
				return ( json_encode($this->breadcrumb, JSON_UNESCAPED_SLASHES) );
			return ( $this->breadcrumb );
		} else {
			if ( $encoded === true )
				return ( json_encode($this->breadcrumb, JSON_UNESCAPED_SLASHES) );
			return ( $this->breadcrumb );
		}
	}


	/**
	 * Return current Schema encoded or not
	 * @return mixed string if encoded config is set to true else object
	 */
	public function read_schema($encoded = null)
	{
		if ( $encoded === null )
		{
			if ( $this->config["encoded"] === true )
				return ( json_encode($this->schema, JSON_UNESCAPED_SLASHES) );
			return ( $this->schema );
		} else {
			if ( $encoded === true )
				return ( json_encode($this->schema, JSON_UNESCAPED_SLASHES) );
			return ( $this->schema );
		}
	}
}