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
 * @todo Event for Performers [In the pipes !]
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
	public $product;
	private $config;

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

	public function product_snippet($name, $image = false, $description = false, $brand = false, $review = false, $aggregateRating = false, $offers = false, $code = false)
	{
		if ( empty($this->product) )
			$this->create_product();
		
		$this->product_snippet_name($name);

		if ( false !== $image )
			$this->product_snippet_image($image);

		if ( false !== $description )
			$this->product_snippet_description($description);

		if ( false !== $brand )
			$this->product_snippet_brand($brand);

		if ( false !== $review )
			$this->product_snippet_review($review);

		if ( false !== $aggregateRating )
			$this->product_snippet_aggregateRating($aggregateRating);

		if ( false !== $offers )
			$this->product_snippet_offers($offers);

		if ( false !== $code )
			$this->product_snippet_code( (strlen($code) - 1), $code );
	}

	public function product_snippet_name($name)
	{

		if ( empty($this->product) )
			$this->create_product();

		if ( !empty($name) )
			$this->product->name = $name;
		else {
			show_error($this->ci->lang->line('schema_string_could_not_be_empty'));
			die();
		}
	}

	public function product_snippet_image($image)
	{
		if ( empty($this->product) )
			$this->create_product();
		
		if ( filter_var($image, FILTER_VALIDATE_URL) )
			$this->product->description = $image;
		else {
			show_error($this->ci->lang->line('schema_url_validation'));
			die();
		}
	}

	public function product_snippet_description($description)
	{
		if ( empty($this->product) )
			$this->create_product();
		
		if ( !empty($description) )
			$this->product->description = $description;
		else {
			show_error($this->ci->lang->line('schema_string_could_not_be_empty'));
			die();
		}
	}

	public function product_snippet_brand($brand)
	{
		if ( empty($this->product) )
			$this->create_product();

		if ( !empty($brand) ) {
			$this->product->brand = new stdClass();
			$this->product->brand->{'@type'} = "Thing";
			$this->product->brand->name = $brand;
		} else {
			show_error($this->ci->lang->line('schema_string_could_not_be_empty'));
			die();
		}
	}

	/**
	 * Create and append a new review
	 * @param  array $review Multidimensionnal array to create a review (see http://schema.org/Review)
	 * @return void
	 */
	public function product_snippet_review($review)
	{
		if ( empty($this->product) )
		{
			$this->create_product();
			$this->product->review = array();
		}

		if ( !is_array($review) )
		{
			show_error($this->ci->lang->line('schema_array_only'));
			die();
		}

		$newReview = stdClass();
		$newReview->{'@type'} = 'Review';

		foreach ($review as $name => $value) {
			if ( !is_array($value) )
				$newReview->{$name} = $value;
			else {
				$newReview->{$name} = new stdClass();
				$newReview->{$name} = json_decode(json_encode($value), FALSE);
			}
		}

		$this->product->review[] = $newReview;
	}

	public function product_snippet_aggregateRating($ratingValue = false, $reviewCount = false)
	{
		// Create product
		if ( empty($this->product) )
			$this->create_product();

		$this->product->aggregateRating = new stdClass();
		$this->product->aggregateRating->{'@type'} = 'AggregateRating';

		if ( false === $ratingValue && false === $reviewCount )
			return false;

		if ( $ratingValue && !filter_var($ratingValue, FILTER_VALIDATE_FLOAT) && !filter_var($ratingValue, FILTER_VALIDATE_INT) && ($ratingValue < 0 || $ratingValue > 5) )
		{
			show_error($this->ci->lang->line('schema_not_a_number'));
			die();
		}

		if ( false !== $ratingValue )
			$this->product->aggregateRating->ratingValue = $ratingValue;

		if ( false !== $reviewCount )
			$this->product->aggregateRating->reviewCount = $reviewCount;
	}

	/**
	 * Add a offer to the product
	 * @param  float|double|int  $price           price of the current product
	 * @param  string  $priceCurrency   current currency
	 * @param  string $priceValidUntil date until the price is valid
	 * @param  string $availability    Value is taken from a constrained list of options
	 * @param  string $url             A URL to the product web page (that includes the Offer)
	 * @param  mixed $itemOffered     The item being sold. Typically, this includes a nested product, but it can also contain other item types or free text.
	 * @return void
	 */
	public function product_snippet_offers($price, $priceCurrency, $priceValidUntil = false, $availability = false, $url = false, $itemOffered = false)
	{
		// Create product
		if ( empty($this->product) ) 
			$this->create_product();

		// Validate price
		if ( !filter_var($price, FILTER_VALIDATE_FLOAT) && !filter_var($price, FILTER_VALIDATE_INT))
		{
			show_error($this->ci->lang->line('schema_not_a_number'));
			die();
		}

		// Validate currency
		if ( !in_array($priceCurrency, $this->config['allowedCurrency']) )
		{
			show_error($this->ci->lang->line('schema_not_a_valid_currency'));
			die();
		}
		$this->product->offers = new stdClass();
		$this->product->offers->{'@type'} = "Offer";

		$this->product->offers->price = $price;
		$this->product->offers->priceCurrency = $priceCurrency;

		// If Price valid until var is set
		if ( false !== $priceValidUntil && $this->validateDate($priceValidUntil) )
			$this->product->offers->priceValidUntil = $priceValidUntil;
		else {
			show_error($this->ci->lang->line('schema_date_validation'));
			die();
		}

		// If availability is set
		if ( false !== $availability && in_array($availability, $this->config['allowedAvailability']) )
			$this->product->offers->availability = "http://schema.org/".$availability;
		else if (false !== $availability ) {
			show_error($this->ci->lang->line('schema_unauthorized_value'));
			die();
		}

		// Validate url if is set
		if ( false !== $url && filter_var($url, FILTER_VALIDATE_URL) )
			$this->product->offers->url = $url;
		else if ( false !== $url ) {
			show_error($this->ci->lang->line('schema_url_validation'));
			die();
		}

		// If itemOffered is set
		if ( false !== $itemOffered )
			$this->product->offers->itemOffered = $itemOffered;
	}

	public function product_snippet_aggregateOffer($lowPrice, $priceCurrency, $highPrice = false, $offerCount = false)
	{
		// Create product
		if ( empty($this->product) ) 
			$this->create_product();

		// Validate prices
		if ( !filter_var($lowPrice, FILTER_VALIDATE_FLOAT) && !filter_var($lowPrice, FILTER_VALIDATE_INT) )
		{
			show_error($this->ci->lang->line('schema_not_a_number'));
			die();
		}

		// Validate currency
		if ( !in_array($priceCurrency, $this->config['allowedCurrency']) )
		{
			show_error($this->ci->lang->line('schema_not_a_valid_currency'));
			die();
		}

		$this->product->offers = new stdClass();
		$this->product->offers->{'@type'} = "AggregateOffer";

		$this->product->offers->lowPrice = $lowPrice;
		$this->product->offers->priceCurrency = $priceCurrency;

		if ( false !== $highPrice && !filter_var($highPrice, FILTER_VALIDATE_FLOAT) && !filter_var($highPrice, FILTER_VALIDATE_INT) )
		{
			show_error($this->ci->lang->line('schema_not_a_number'));
			die();
		} else {
			$this->product->offers->highPrice = $highPrice;
		}

		if ( false !== $offerCount && !filter_var($offerCount, FILTER_VALIDATE_FLOAT) && !filter_var($offerCount, FILTER_VALIDATE_INT) )
		{
			show_error($this->ci->lang->line('schema_not_a_number'));
			die();
		} else {
			$this->product->offers->offerCount = $offerCount;
		}
	}

	/**
	 * Set
	 * 		- Manufacture product number
	 * 		- Stock Keeping Unit
	 *   	- BarCode  GTIN 8/13/14
	 * @param  int $type The type of the product information (allowed in config file)
	 * @param  string $code the barcode or the sku or the mnp
	 * @return void
	 */
	public function product_snippet_code($type, $code)
	{
		// Validate type
		if ( !in_array(strtolower($type), $this->config["allowedProductCode"]) )
		{
			show_error($this->ci->lang->line('schema_product_code_not_allowed'));
			die();
		}

		// Create product
		if ( empty($this->product) )
			$this->create_product();

		// Validate barcode or set sku || mpn
		switch ($type) {
			case 'sku':
				$this->product->sku = $code;
				break;
			case 'gtin8':
				if ( $this->validateGtin(8, $code) )
				{
					show_error($this->ci->lang->line('schema_product_code_gtin_false'));
					die();
				}
				$this->product->gtin8 = $code;
				break;
			case 'gtin13':
				if ( $this->validateGtin(13, $code) )
				{
					show_error($this->ci->lang->line('schema_product_code_gtin_false'));
					die();
				}
				$this->product->gtin8 = $code;
				break;
			case 'gtin14':
				if ( $this->validateGtin(14, $code) )
				{
					show_error($this->ci->lang->line('schema_product_code_gtin_false'));
					die();
				}
				$this->product->gtin14 = $code;
				break;
			case 'mpn':
				$this->product->mpn = $code;
				break;
		}
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
	 * Remove a contact by index
	 * @param  int $index the contact position
	 * @return boolean        True if contact was erased else false
	 */
	public function remove_contact($index)
	{
		if ( array_key_exists($index, $this->schema->contactPoint) )
		{
			unset($this->schema->contactPoint[$index]);
			$this->schema->contactPoint = array_values(array_filter($this->schema->contactPoint));
			return ( TRUE );
		}

		return ( FALSE );
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

	public function read_product_snippet($encoded = null)
	{
		// Validate basics informations
		if ( empty($this->product) || !property_exists($this->product, "name") )
		{
			show_error($this->ci->lang->line('schema_product_missing_parameters'));
			die();
		}

		if ( property_exists($this->product, "offers") && $this->product->offers->{'@type'} == "Offer" )
		{
			if ( !isset($this->product->offers->price) || empty($this->product->offers->price)
				|| !isset($this->product->offers->priceCurrency) || empty($this->product->offers->priceCurrency) )
			{
				show_error($this->ci->lang->line('schema_product_missing_parameters'));
				die();
			}
		} else if ( $this->product->offers->{'@type'} == "AggregateOffer" )
		{
			if ( !isset($this->product->offers->lowPrice) || empty($this->product->offers->lowPrice) 
				|| !isset($this->product->offers->priceCurrency) || empty($this->product->offers->priceCurrency))
			{

				show_error($this->ci->lang->line('schema_product_missing_parameters'));
				die();
			}
		}

		if ( $encoded === null )
		{
			if ( $this->config["encoded"] === true )
				return ( json_encode($this->product, JSON_UNESCAPED_SLASHES) );
			return ( $this->product );
		} else {
			if ( $encoded === true )
				return ( json_encode($this->product, JSON_UNESCAPED_SLASHES) );
			return ( $this->product );
		}
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

	/**
	 * Helpers methods
	 */
	
	/**
	 * Validate any ISO 8601 date
	 * @param  string $value a date
	 * @return boolean        true if the date is in a good format else false
	 */
	private function validateDate($value)
	{
		try {
			$timestamp = strtotime($value);
			$date = date(DATE_ISO8601, $timestamp);
			return (true);
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
	 * Validate barcode gtin and EAN
	 * @param  int $type the gtin type (8/13/14)
	 * @param  string $code the gtin / ean string
	 * @return boolean       true if the code is valide else false
	 */
	private function validateGtin($type, $code)
	{
		switch ($type) {
			case 8:
				$tmpCode = str_split($code);
				$key = array_pop($tmpCode);
				$sum = 0;
				foreach ($tmpCode as $index => $value) {
					$sum += ( ($index + 1) % 2 == 0 ) ? $value : $value * 3;
				}
				if ( ( 10 - $sum % 10) == $key )
					return true;
				break;
			case 13:
				$tmpCode = str_split($code);
				$key = array_pop($tmpCode);
				$sum = 0;
				foreach ($tmpCode as $index => $value) {
					$sum += ( ($index + 1) % 2 == 0 ) ? $value * 3 : $value;
				}
				if ( ( 10 - $sum % 10) == $key )
					return true;
				break;
			case 14:
				$tmpCode = str_split($code);
				$productType = array_shift($tmpCode);
				if ( $productType < 0 || $productType > 8 )
					return false;
				return ( $this->validateGtin(13, implode($tmpCode)) );
			default:
				return false;
				break;
		}
		return false;
	}

	/**
	 * Create a product for initialisation
	 * @return void
	 */
	private function create_product()
	{
		$this->product = new stdClass();
		$this->product->{'@context'} = "http://schema.org/";
		$this->product->{'@type'} = "Product";
	}
}