<?php

namespace Fractas\GoogleAnalytics;

use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Requirements;

class GoogleAnalyticsController extends Extension
{
    /**
     * @var string Primary Google Analytics tracking code
     */
    private static $ga_id;

    /**
     * @var array Other Google Analytics tracking codes
     */
    private static $ga_extra_ids;

    /**
     * @var string Google Tag Manager tracking code
     */
    private static $gtm_id;

    /**
     * @var string Google Analytics domain, used for prefetch
     */
    private static $ga_domain = 'https://www.google-analytics.com';

    /**
     * @var string Google Tag Manager domain, used for prefetch
     */
    private static $gtm_domain = 'https://www.googletagmanager.com';

    /**
     * @var bool For details see https://support.google.com/analytics/answer/2444872?hl=en#trackingcode
     */
    private static $enable_display_features = true;

    /**
     * @var bool Enable tracking code in dev mode (useful for testing)
     */
    private static $enable_in_dev = false;

    /**
     * @return string Returns Primary Google Analytics ID if module is enabled
     */
    public function GAID()
    {
        if ($this->IsEnabled()) {
            return Config::inst()->get(self::class, 'ga_id');
        }
    }

    /**
     * @return string Returns Google Tag Manager ID if module is enabled
     */
    public function GTMID()
    {
        if ($this->IsEnabled()) {
            return Config::inst()->get(self::class, 'gtm_id');
        }
    }

    /**
     * @return bool Google Analytics Display Features is enabled?
     */
    public function IsDisplayFeatured()
    {
        return Config::inst()->get(self::class, 'enable_display_features');
    }

    /**
     * @return bool Google Analytics is enabled in dev mode?
     */
    public function IsEnabledInDev()
    {
        return Config::inst()->get(self::class, 'enable_in_dev');
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
     * Generates GA tracking code out of config vars.
     */
    public function MultiTrackersList()
    {
        $list = Config::inst()->get(self::class, 'ga_extra_ids');

        if (!\is_array($list)) {
            return false;
        }

        $extra = [];
        foreach ($list as $key => $value) {
            $extra[] = new ArrayData([
                'Title' => $key,
                'ID' => $value,
            ]);
        }

        return new ArrayList($extra);
    }

    /**
     * Includes the GA tracking code in HTML <head> when ContentController initializes.
     */
    public function onAfterInit()
    {
        if ($this->GAID()) {
            Requirements::insertHeadTags($this->owner->renderWith('GoogleAnalyticsCode'), 'GoogleAnalyticsCode');
        }
    }
}
