# SilverStripe Google Analytics

## Overview

Fast, configurable & simple Google Analytics module with support for multiple GA ID's

## Requirements

- SilverStripe Framework 4

## Installation

- Install via Composer

  ```
  composer require "fractas/googleanalytics" "^2.0"
  ```

- Run dev/build

- Add to your mysite configuration file your Google Analytics ID:

  ```yaml
  Fractas\GoogleAnalytics\GoogleAnalyticsController:
    enable_display_features: true
    enable_in_dev: false
    ga_id: UA-xxxxxxxx-x0 #main GA ID property
    ga_extra_ids:
      newTracker1: UA-xxxxxxxx-x1 #multiple GA properties
      newTracker2: UA-xxxxxxxx-x2
      newTracker3: UA-xxxxxxxx-x3
  ```

## Bugtracker

Bugs are tracked on [github.com](https://github.com/fractaslabs/silverstripe-google-analytics/issues)

## Licence

- See [Licence](https://github.com/fractaslabs/silverstripe-google-analytics/blob/master/LICENSE)
