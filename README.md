SAMLBase
=======

##Introduction
Build SAML Connections in php object based.

##Status
Currently its possible to dynamically load the metadata, and after that, do a request via a POST or Redirect binding.
You can sign requests with a certificate. The response can be read, decrypted, verified and attributes can be retrieved.

##Setup
    composer install

Thats all!

## Roadmap (Last updated 30-03-2016)

    BASICS we have achieved so far
        1. Resolve metadata from an IDP into a PHP array that we can work with
        2. Do a AuthNRequest via Redirect Binding
        3. Do a AuthNRequest via POST Binding
        4. Handle the AuthNResponse
        5. Pass thru returning attributes and claims
        6. Templates changed to twig
        7. Use a DIC to provide the classes with the necessary information
        8. Handle a Single Logout Request
	9. Support AssertionConsumerService over HTTP-Artifact (a SOAP Envelope) with Redirect/POST binding

    Version 1.1.0 (Updated at 30-03-2016)
        1. Support for SP Initiated Artifact Resolution
	2. Signing of templates is now back in the proper location as second element in the request template

    Upcoming:
        1. Increase the amount of Unit Tests
        2. Add Scoping and Conditions to AuthnRequest
        3. Add AttributeQuery and AttributeResponse
        4. Apply Assertions
        5. Support multiple identifier types (BaseID, NameID, EncryptedID)
        6. Add Statement Element support
        7. Add Advice Element support
        8. Increase the SAML2 scope compatibility of the library (Continuous, version 1.1.0 has a lot of these already)

    FUTURE
        1. Make sure we can be a Attribute Authority (AttributeRequest / AttributeResponse)
	2. Add the SOAP Binding for ACS
        3. Add the URI Binding (never seen this being used in the past)
        4. Add the PAOS Binding (very tropical, hardly used)

## Examples (relative to package root)

    /example/index.php - Example AuthNRequest (Redirect and POST binding)
    /example/response.php - Example AuthNResponse target file (POST Binding)
    /example/attributes.php - WIP AttributeQuery request after being logged in (requires attributequery service on the IDP)
    /example/logout.php - WIP Logout request
    /example/logoutresponse.php - Example LogoutResponse handling
    
## License information
    This code is released under the LGPL v2.1 license
    Info about the license can be found here:  https://www.gnu.org/licenses/lgpl-2.1.html
