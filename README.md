# lumen-starter-api
Small starter bootstrap for a lumen application api

[![Build Status](https://travis-ci.org/etiennemarais/lumen-starter-api.svg?branch=master)](https://travis-ci.org/etiennemarais/lumen-starter-api)


- Slack bot for notifications
- Config setups for laravel package backwards compatibility
- Domain separated business logic
- Api key middleware authentication
- API Blueprint that documents the system
- Supports soft deletes for model serialization 
- Multi tenancy that binds to a configurable database field
- API root index is the documentation from your api blueprint
- Custom validation that binds to a 406 response
- Custom Application log file path
- Backups via scheduled tasks


## Installation

To install the starter site, just run the following command:

```
composer create-project --prefer-dist etiennemarais/lumen-starter starter
```
