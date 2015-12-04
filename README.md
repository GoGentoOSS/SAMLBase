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

## Roadmap (Last updated 05-12-2015)

    BASICS we have achieved so far
        1. Resolve metadata from an IDP into a PHP array that we can work with
        2. Do a AuthNRequest via Redirect Binding
        3. Do a AuthNRequest via POST Binding
        4. Handle the AuthNResponse
        5. Pass thru returning attributes and claims
        6. Templates changed to twig
        7. Use a DIC to provide the classes with the necessary information
        8. Handle a Single Logout Request
        9. Support SOAP and Artifact binding

    01-01-2016
        1. Increase the amount of Unit Tests
        2. Add Scoping and Conditions to AuthnRequest
        3. Support for SP Initiated Artifact Resolution
    
    01-02-2016
        1. Add AttributeQuery and AttributeResponse
        2. Find a better way to store the SessionID and RelayState
        3. Apply Assertions
        
    01-02-2016
        1. Support multiple identifier types (BaseID, NameID, EncryptedID)
        2. Add Statement Element support
        
    01-02-2016
        1. Add Advice Element support
        
    FUTURE
        1. Make sure we can be a Attribute Authority (AttributeRequest / AttributeResponse)
        2. Increase the SAML2 scope compatibility of the library
        3. Add the URI Binding (never seen this being used in the past)
        3. Add the PAOS Binding (very tropical, hardly used)

## Examples (relative to package root)

    /example/index.php - Example AuthNRequest (Redirect and POST binding)
    /example/response.php - Example AuthNResponse target file (POST Binding)
    /example/attributes.php - WIP AttributeQuery request after being logged in (requires attributequery service on the IDP)
    /example/logout.php - WIP Logout request
    /example/logoutresponse.php - Example LogoutResponse handling
    
## License information
    This code is released under the LGPL v2.1 license
    Info about the license can be found here:  https://www.gnu.org/licenses/lgpl-2.1.html
