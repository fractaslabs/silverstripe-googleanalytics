<?php

class GoogleAnalyticsController extends Extension {

	private static $ga_id;

	private static $enable_display_features = true;

	private static $enable_in_dev = false;

	public function GAID() {
		if ($this->IsEnabled()) {
			return Config::inst()->get('GoogleAnalyticsController', 'ga_id');
		}
	}

	public function IsDisplayFeatured() {
		return Config::inst()->get('GoogleAnalyticsController', 'enable_display_features');
	}

	public function IsEnabledInDev() {
		return Config::inst()->get('GoogleAnalyticsController', 'enable_in_dev');
	}

	public function IsEnabled() {
		if (Director::isLive()) {
			return true;
		} elseif (Director::isDev() && $this->IsEnabledInDev()) {
			return true;
		}

		return false;
	}

	public function MultiTrackersList() {
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

	public function onAfterInit() {
		if ($this->GAID()) {
			Requirements::insertHeadTags($this->owner->renderWith('GoogleAnalyticsCode'), 'GoogleAnalyticsCode');
		}
	}
}
