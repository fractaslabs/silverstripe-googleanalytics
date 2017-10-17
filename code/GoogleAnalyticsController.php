<?php

class GoogleAnalyticsController extends Extension
{
    private static $ga_id;

    /**
    * @var bool For details see https://support.google.com/analytics/answer/2444872?hl=en#trackingcode
    */
    private static $enable_display_features = true;

    /**
    * @var bool Enable tracking code in dev mode (useful for testing)
    */
    private static $enable_in_dev = false;

    public function GAID()
    {
        if ($this->IsEnabled()) {
            return Config::inst()->get('GoogleAnalyticsController', 'ga_id');
        }
    }

    public function IsDisplayFeatured()
    {
        return Config::inst()->get('GoogleAnalyticsController', 'enable_display_features');
    }

    public function IsEnabledInDev()
    {
        return Config::inst()->get('GoogleAnalyticsController', 'enable_in_dev');
    }

    /**
    * @return bool Google Analytics is enabled by default if is in "live" mode
    */
    public function IsEnabled()
    {
        if (Director::isLive()) {
            return true;
        } elseif (Director::isDev() && $this->IsEnabledInDev()) {
            return true;
        }

        return false;
    }

    /**
    * Generates GA tracking code out of config vars
    */
    public function MultiTrackersList()
    {
        $list = Config::inst()->get('GoogleAnalyticsController', 'ga_extra_ids');

        if (!is_array($list)) {
            return false;
        }

        $extra = array();
        foreach ($list as $key => $value) {
            $extra[] = new ArrayData(array(
                'Title' => $key,
                'ID'    => $value
            ));
        }

        return new ArrayList($extra);
    }

    /**
    * Includes the GA tracking code in HTML <head> when ContentController initializes
    */
    public function onAfterInit()
    {
        if ($this->GAID()) {
            Requirements::insertHeadTags($this->owner->renderWith('GoogleAnalyticsCode'), 'GoogleAnalyticsCode');
        }
    }
}
