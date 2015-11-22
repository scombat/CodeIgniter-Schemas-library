<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 	Prototypes
 * 
 * 	$this->schemas->...
 * 
 * 		read_schema($encoded = null)
 *	  	read_breadcrumb($encoded = null)
 *	   	read_sitename($encoded = null)
 * 
 * 		site_name($name, $alt, $url)
 *   	
 *    	create_breadcrumb()
 *     	clear_breadcrumb()
 *      add_breadcrumb_item($position, $id, $name)
 *      
 *      add_social($profile_url)
 *     	
 *      add_contact(
 *      	$telephone,
 *       	$contactType,
 *        	$areaServed = false,
 *         	$contactOption = false,
 *          $availableLanguages = false
 *      )
 * 
 * 		set_context($context)
 * 		set_type($type)
 * 		set_logo($logo)
 *   	
 */
class Schemas_example extends CI_Controller {

	public function index()
	{
		// Load the library
		$this->load->library('schemas');

		/**
		 * Configure Schema
		 */
		
		// Set the Schema Context (@Context)
		$this->schemas->set_context('http://schema.org');

		// Set the Schema Type (@type)
		$this->schemas->set_type('Organization');

		// Set the Schema Site name
		$this->schemas->site_name('Schema Example', 'My Beautifull schema site name', base_url());

		// Set the Schema Logo
		$this->schemas->set_logo(base_url('assets/images/my_logo.png'));

		// See the result :
		// echo $this->schemas->read_schema();
		// echo $this->schemas->read_sitename();

		/**
		 * End of basics configurations
		 * --------------------------------------------------------------------
		 */
		


		/**
		 * Common functions
		 */
		
		// Read Sitename
		// 		• with configuration encode
		$this->schemas->read_sitename();
		// 		• with force encoded opt
		$this->schemas->read_sitename();
		// 		• With force not encoded opt
		$this->schemas->read_sitename(false);

		// Read full Schema
		// 		• with configuration encode
		$this->schemas->read_schema();
		// 		• with force encoded opt
		$this->schemas->read_schema(true);
		// 		• With force not encoded opt
		$this->schemas->read_schema(false);

		// Read BreadCrumb
		// 		• with configuration encode
		$this->schemas->read_breadcrumb();
		// 		• with force encoded opt
		$this->schemas->read_breadcrumb(true);
		// 		• With force not encoded opt
		$this->schemas->read_breadcrumb(false);

		/**
		 * End of basics configurations
		 * --------------------------------------------------------------------
		 */
		


		/**
		 * Contact part
		 */

		// Add some contacts
		// A contact without additionnals options
		$this->schemas->add_contact('0123456789', 'customer support');

		// A contact with areas
		$this->schemas->add_contact('0123456789', 'technical support', array("US","CA","MX"));

		// A contact with options
		$this->schemas->add_contact('0123456789', 'billing support', false, 'HearingImpairedSupported');

		// A contact with full options
		$this->schemas->add_contact('0123456789', 'emergency', array("US","CA","FR"), 'TollFree', array("French", "English", "Spanish"));

		// Remove a contact
		$this->schemas->remove_contact(1);

		// See the result :
		// echo $this->schemas->read_schema();

		
		/**
		 * End of Contact part
		 * --------------------------------------------------------------------
		 */
		


		/**
		 * Social part
		 */
		
		$this->schemas->add_social('https://www.facebook.com/castoretpollux/');
		$this->schemas->add_social('https://twitter.com/castoretpollux_');
		$this->schemas->add_social('https://plus.google.com/+CastorPolluxParis/');

		// See the result :
		// echo $this->schemas->read_schema();

		/**
		 * End of Social part
		 * --------------------------------------------------------------------
		 */



		/**
		 * Breadcrumb part
		 */

		// Create a new breadcrumb
		$this->schemas->create_breadcrumb();

		// And add an item
		$this->schemas->add_breadcrumb_item(1, current_url(), 'Schema');

		// Append a new item into current breadcrumb
		$this->schemas->add_breadcrumb_item(2, site_url('/example'), 'Example');

		// Save current breadcrumb
		$oldBreadcrumb = $this->schemas->read_breadcrumb();

		// // Clear breadcrumb (Erase old bc)
		$this->schemas->clear_breadcrumb();

		// // Create and add an item into new breadcrumb
		$this->schemas->add_breadcrumb_item(1, current_url(), 'Schema examples');

		// // Get the last version of breadcrumb
		$newBreadcrumb = $this->schemas->read_breadcrumb();

		// See the result :
		// echo $oldBreadcrumb;
		// echo $newBreadcrumb;

		/**
		 * End of Breadcrumb part
		 * --------------------------------------------------------------------
		 */
		
		/**
		 * Product Rich Snippet
		 */
		
		$this->schemas->product_snippet_name("Executive Anvil");
		$this->schemas->product_snippet_image("http://www.example.com/anvil_executive.jpg");
		$this->schemas->product_snippet_description("Sleeker than ACME's Classic Anvil, the Executive Anvil is perfect for the business traveler looking for something to drop from a height.");
		$this->schemas->product_snippet_code("mpn", "925872");
		$this->schemas->product_snippet_brand("ACME");
		$this->schemas->product_snippet_aggregateRating(4.4, 89);
		$this->schemas->product_snippet_offers(119.99, "USD", "2020-11-05", "InStock");

		// See the result :
		// echo $this->schemas->read_product_snippet();

		/**
		 * End of Product Rich Snippet part
		 * --------------------------------------------------------------------
		 */
	}
}
