<?php

namespace Fractas\GoogleAnalytics;

use SilverStripe\Admin\AdminRootController;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Middleware\HTTPMiddleware;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\View\ArrayData;
use SilverStripe\View\HTML;

class GoogleTagManagerMiddleware implements HTTPMiddleware
{
    use Injectable;
    use Configurable;

    /**
     * @var string Google Tag Manager ID
     */
    private $gtm_id = null;

    /**
     * @var string Google Tag Manager domain
     */
    private $gtm_domain = null;

    /**
     * @var string Google Analytics domain
     */
    private $ga_domain = null;

    /**
     * Process request.
     *
     * @param HTTPRequest $request
     * @param callable    $delegate
     *
     * @return $response
     */
    public function process(HTTPRequest $request, callable $delegate)
    {
        $response = $delegate($request);

        if (true === $this->getIsAdmin($request)) {
            return $response;
        }

        if ($this->getIsEnabled()) {
            $this->gtm_id = Config::inst()->get(GoogleAnalyticsController::class, 'gtm_id');
            $this->gtm_domain = Config::inst()->get(GoogleAnalyticsController::class, 'gtm_domain');
            $this->ga_domain = Config::inst()->get(GoogleAnalyticsController::class, 'ga_domain');

            $this->addBodyTag($response);
            $this->addHeadTag($response);
            $this->addPrefetchGA($response);
            $this->addPrefetchGTM($response);
            $this->addPreconnectGA($response);
            $this->addPreconnectGTM($response);
        }

        return $response;
    }

    private function addBodyTag(&$response)
    {
        $data = ArrayData::create(['GTMID' => $this->gtm_id]);
        $tag = $data->renderWith('GoogleTagManagerNoscriptCode');
        $body = $response->getBody();
        $pattern = '/\<body.*\>/';
        $replace = '${0}'."\n".$tag;
        $newBody = preg_replace($pattern, $replace, $body);
        $response->setBody($newBody);
    }

    private function addHeadTag(&$response)
    {
        $data = ArrayData::create(['GTMID' => $this->gtm_id]);
        $tag = $data->renderWith('GoogleTagManagerCode');
        $body = $response->getBody();
        $body = str_replace('<head>', '<head>'.$tag, $body);
        $response->setBody($body);
    }

    private function addPreconnectGA(&$response)
    {
        $atts = [
            'rel' => 'preconnect',
            'href' => $this->ga_domain,
        ];
        $pfTag = "\n".HTML::createTag('link', $atts)."\n";
        $body = $response->getBody();
        $body = str_replace('<head>', '<head>'.$pfTag, $body);
        $response->setBody($body);
    }

    private function addPreconnectGTM(&$response)
    {
        $atts = [
            'rel' => 'preconnect',
            'href' => $this->gtm_domain,
        ];
        $pfTag = "\n".HTML::createTag('link', $atts)."\n";
        $body = $response->getBody();
        $body = str_replace('<head>', '<head>'.$pfTag, $body);
        $response->setBody($body);
    }

    private function addPrefetchGA(&$response)
    {
        $atts = [
            'rel' => 'dns-prefetch',
            'href' => $this->ga_domain,
        ];
        $pfTag = "\n".HTML::createTag('link', $atts)."\n";
        $body = $response->getBody();
        $body = str_replace('<head>', '<head>'.$pfTag, $body);
        $response->setBody($body);
    }

    private function addPrefetchGTM(&$response)
    {
        $atts = [
            'rel' => 'dns-prefetch',
            'href' => $this->gtm_domain,
        ];
        $pfTag = "\n".HTML::createTag('link', $atts)."\n";
        $body = $response->getBody();
        $body = str_replace('<head>', '<head>'.$pfTag, $body);
        $response->setBody($body);
    }

    /**
     * Determine whether the website is being viewed from an admin protected area or not
     * (shamelessly stolen from https://github.com/silverstripe/silverstripe-subsites).
     *
     * @param HTTPRequest $request
     *
     * @return bool
     */
    private function getIsAdmin(HTTPRequest	$request)
    {
        $adminPaths = static::config()->get('admin_url_paths');
        $adminPaths[] = AdminRootController::config()->get('url_base').'/';
        $adminPaths[] = 'dev/';
        $currentPath = rtrim($request->getURL(), '/').'/';
        foreach ($adminPaths as $adminPath) {
            if (substr($currentPath, 0, \strlen($adminPath)) === $adminPath) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool Google Analytics is enabled by default if is in "live" mode
     */
    private function getIsEnabled()
    {
        if (Director::isLive()) {
            return true;
        } elseif (Director::isDev() && Config::inst()->get(GoogleAnalyticsController::class, 'enable_in_dev')) {
            return true;
        }

        return false;
    }
}
