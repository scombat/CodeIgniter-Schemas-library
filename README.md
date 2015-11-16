# CodeIgniter-Schemas-library
####CodeIgniter Schemas Library gives some helpfull shortcuts
#####to build google's custom structured-data like :

* Organization's Logo in search results and the Knowledge Graph.
* Corporate Contacts informations.
* Social profiles informations.
* Google breadcrumbs and site name.

#####And in the near future :
* Promotion events for :
  * Performers
  * Venues
  * Ticketers
* Rich Snippets for :
  *  Products
  *  Recipes
  *  Reviews
  *  Events
  *  Software Apps
  *  Videos
  *  Articles

#####In a more far away future :
* A testing tool
* A Preview and a live preview results
* A website + 
* WYSIWYG Editor
* Microdata support
* RDFA support
* Automatic generation support

### Quick Start
***
######Let's get started ! First of all, we will load the Google Schemas's Library
```php
$this->load->library('schemas');
```

######Now let's set the schema base
```php
$this->schemas->set_type('Organization');
$this->schemas->site_name('Microsoft', 'My Beautifull schema site name', base_url());
$this->schemas->set_logo(base_url('my_fabulous_logo.png'));
```
What should look like:

![alt text](http://img15.hostingpics.net/pics/812512Capturedcran20151115225618.png "Logo")

We have now a basic schema's object.
######What do you think about add your social network profile ?

```php
$this->schemas->add_social('https://www.facebook.com/castoretpollux/');
$this->schemas->add_social('https://twitter.com/castoretpollux_');
$this->schemas->add_social('https://www.linkedin.com/company/castor-&-pollux');
$this->schemas->add_social('https://instagram.com/stevejobs/');
$this->schemas->add_social('https://plus.google.com/+CastorPolluxParis/');
```
![alt text](http://img15.hostingpics.net/pics/817835Capturedcran20151115230845.png "Social profile")

######Can we add a breadcrumb on google results ? Yes, very simply !
```php
// Create a new breadcrumb
$this->schemas->create_breadcrumb();
$this->schemas->add_breadcrumb_item(1, current_url(), 'Schema');
$this->schemas->add_breadcrumb_item(2, site_url('/example'), 'Example');
$myFabulousBreadCrumb = $this->schemas->read_breadcrumb();
```
![alt text](http://img15.hostingpics.net/pics/987795Capturedcran20151115232819.png "Breadcrumb")

######Now, we add some contacts for our customers
```php
$this->schemas->add_contact('0123456789', 'customer support');
$this->schemas->add_contact('0123456789', 'technical support', array("US","CA","MX"));
$this->schemas->add_contact('0123456789', 'billing support', false, 'HearingImpairedSupported');
$this->schemas->add_contact('0123456789', 'emergency', array("US","CA","FR"), 'TollFree', array("French", "English", "Spanish"));
```

######At the end, you'll want to get the schema, breadcrumb, sitename etc...
For manipulate schema's object or json string before make all what you want with it.

If you want to get the object you have two options :
* Modify the configuration file
* Add parameter 'true' to the read_* methods.

Else if you want to get the string you have also two options:
* Modify the configuration file
* Add parameter 'false' to the read_*'s methods.
```php
// Read breadcrumb
$breadcrumb = $this->schemas->read_breadcrumb();

// Read schema
$schem = $this->schemas->read_schema();

// Read Sitename
$this->schemas->read_sitename(false);
```
