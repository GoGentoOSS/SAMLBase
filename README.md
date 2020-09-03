SAMLBase
=======

##Introduction
Build a feature rich SAML Service Provider that is able to communicate to literally any SAML2 Identity Provider.
It covers almost the full scope of SAML2 and the base is increasing.

The library is used to connect global market leaders to their SAML2 Identity Providers.

##Features

1. Resolve the Metadata from the Identity Provider
2. Automatic Service Provider Metadata to exchange with the Identity Provider
3. Authenticate via POST and Redirect Bindings
4. Assertion Consumer Service
5. Artifact Resolution with Redirect and POST Bindings using HTTP-Artifact
6. Handle the Authentication response from the Identity Provider
7. Single Logout via POST and Redirect
8. Identity Provider initiated Single Logout
9. Attributes mapping
10. Sign and Verify all requests and metadata
11. Multiple NameID Formats
12. Encryption and Decryption

##Setup
    composer require gogentooss/samlbase

## Coming soon
    1. Add Scoping and Conditions to AuthnRequest
    2. Add AttributeQuery and AttributeResponse
    3. Apply Assertions
    4. Support multiple identifier types (BaseID, NameID, EncryptedID)
    5. Add Statement Element support
    6. Add Advice Element support
    7. Increase the SAML2 scope compatibility of the library (Continuous, version 1.1.0 has a lot of these already)
	8. Add the SOAP Binding for ACS
    9. Add the URI Binding (never seen this being used in the past)
    10. Add the PAOS Binding (very tropical, hardly used)

## Examples (relative to package root)

    /example/metadata.php - Service Provider Metadata
    /example/index.php - Example AuthNRequest (Redirect and POST binding)
    /example/response.php - Example AuthNResponse target file (POST Binding)
    /example/attributes.php - WIP AttributeQuery request after being logged in (requires attributequery service on the IDP)
    /example/logout.php - Logout request
    /example/logoutresponse.php - Example LogoutResponse handling
    
## License information
    This code is released under the OSL v3 license
    Info about the license can be found here:  https://opensource.org/licenses/OSL-3.0
