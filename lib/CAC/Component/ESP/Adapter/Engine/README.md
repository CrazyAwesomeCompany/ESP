E-Ngine ESP Adapter
===================

This adapter implements the E-Ngine Webservice for the ESP Mail Interfaces.

## API Configuration ##
The Adapter uses the E-Ngine SOAP Webservice for communication. When creating the `EngineApi` class some configuration is needed

 + `domain` - The domain where E-Ngine is availabe. (e.g. `newsletter.yourdomain.com`)
 + `path` - Path to the SOAP entry point on the `domain`. (e.g. `/soap/server.live.php`)
 + `customer` - Your E-Ngine customer name
 + `user` - Your E-Ngine user name
 + `password` - Your E-Ngine password
