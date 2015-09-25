E-Ngine ESP Adapter
===================

This adapter implements the E-Ngine Webservice for the ESP Mail Interfaces.

## Requirements
You need to install the E-Ngine API.

    "cac/esp-api-engine": "@dev"

## Configuration

    esp.mail.engine.config:
      options:
        default:
          fromName: "Crazy Awesome Company"
          fromEmail: "info@crazyawesomecompany.com"
        group02:
          fromName: "Sub website name"
          fromEmail: "other@crazyaweomse.company"
      templates:
        default:
          userCreated:
            id: 11
            subject: ~
            mailinglist: 3
        group02:
          userCreated:
            id: 25
            subject: ~
            mailinglist: 7
