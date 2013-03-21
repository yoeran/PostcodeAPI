PostcodeAPI
===========

PHP wrapper for the Postcode API.
Built as a CodeIgniter library, but usable in any PHP project.
Use this if you need more information about Dutch Zipcodes.


### The API
* Website: http://www.postcodeapi.nu/
* Documentation: http://api.postcodeapi.nu/docs/

### Usage

To get information about the specified zipcode:
``` $postcodeAPI->getInfo('1234AB'); ```

To get the distance in meters between two zipcodes:
``` $postcodeAPI->getDistance('1234AB','4321BA'); ```
