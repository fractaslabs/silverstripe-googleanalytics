# SilverStripe Google Analytics
[![Latest Stable Version](https://poser.pugx.org/fractas/googleanalytics/v/stable)](https://packagist.org/packages/fractas/googleanalytics)
[![Latest Unstable Version](https://poser.pugx.org/fractas/googleanalytics/v/unstable)](https://packagist.org/packages/fractas/googleanalytics)
[![Total Downloads](https://poser.pugx.org/fractas/googleanalytics/downloads)](https://packagist.org/packages/fractas/googleanalytics)
[![License](https://poser.pugx.org/fractas/googleanalytics/license)](https://packagist.org/packages/fractas/googleanalytics)

## Overview

Fast, configurable, simple Google Analytics and Google Tag Manager module with support for multiple GA ID's

## Requirements

- SilverStripe Framework 4

## Version info
The master branch of this module is currently aiming for SilverStripe 4.x compatibility
- [SilverStripe 3.0+ compatible version](https://github.com/fractaslabs/silverstripe-googleanalytics/tree/3.0)

## Installation

- Install via Composer

  ```
  composer require "fractas/googleanalytics" "^3.0"
  ```

- Add to your **mysite.yml** configuration file your Google Analytics ID, and enable extra features (if needed):

  ```yaml
  Fractas\GoogleAnalytics\GoogleAnalyticsController:
    enable_display_features: true # https://developers.google.com/analytics/devguides/collection/analyticsjs/display-features
    enable_in_dev: false
    ga_id: UA-xxxxxxxx-x0 #main Google Analytics ID property
    gtm_id: GTM-xxxxxxxx #Google Tag Manager ID
    ga_extra_ids:
      newTracker1: UA-xxxxxxxx-x1 #you can add multiple Google Analytics ID properties
      newTracker2: UA-xxxxxxxx-x2
      newTrackerX: UA-xxxxxxxx-xX
    excluded_controllers:
      # Controllers that should be excluded from including GA codes.
      - Wilr\GoogleSitemaps\Control\GoogleSitemapController
  ```

- Run flush=all in your browser

## Bugtracker

Bugs are tracked on [github.com](https://github.com/fractaslabs/silverstripe-google-analytics/issues)

## Licence

- See [Licence](https://github.com/fractaslabs/silverstripe-google-analytics/blob/master/LICENSE)
