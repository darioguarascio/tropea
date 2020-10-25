<?php

use Carbon\Carbon;

use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Controller;

/**
 * Tracking class. Implementation of pixel-like urls that load and execute the same controllers/actions in the project.
 * The goal is to create a dataset related to the loaded page to be used as tracking data that can handle cached pages.
 *
 * How it works
 *
 * 1. User loads a normal page (IE: Index page with URL: `/?test=1`)
 * 2. Index page generates a tracking url using OutputController::afterExecuteRoute (IE: `/track/Lw--/YXNkeD0x`)
 * 3. Tracking url gets sent to browser in the form of either a `<img>` object or an ajax request
 * 4. Browser calls tracking url
 * 5. TrackerController handles the request, decoding url, matching it with a proper original controller
 * 6. Original controller generates the same dataset used to build the page
 * 7. TrackerController obtains a subset of page dataset using `getTracking()` method
 * 8. TrackerController generates a final tracking dataset, pushes it to a local queue
 * 9. TrackerController sends dummy data to browser
 */

class TrackerController extends Controller {

    private $handler;
    private $trackUrl;
    private $trackQs;
    private $trackQuery = array();
    private $query = array();

    public function beforeExecuteRoute(Dispatcher $dispatcher) {
        Resque::setBackend(getenv('TRACKER_REDIS_ADDRESS'), getenv('TRACKER_REDIS_DB') );
        $this->query = $_GET;
    }

    public function afterExecuteRoute(Dispatcher $dispatcher) {
        $_GET = $this->query;

        # Removing from tracking any query parameter starting with `_`
        foreach ($this->query as $k => $v) {
            if (substr($k,0,1) =='_') {
                unset($this->query[$k]);
            }
        }

        $return = array_filter(array(
            '@timestamp' => Carbon::now()->format('Y-m-d\TH:i:s.uP'),
            '@handler'   => $this->handler,
            '@data'      => $dispatcher->getReturnedValue(),
            '@url'       => [
                'path'  => $this->trackUrl,
                'query' => $this->trackQuery ],
            '@query'    => $this->query
        ));

        # Async logging by pushing tracking data to local queue
        Resque::enqueue( getenv('TRACKER_REDIS_QNAME'), 'Tracking', $return );

        $this->response->setHeader('Cache-Control', 'private, max-age=0, must-revalidate');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');

        # Response with dummy data
        if (conf()->debug) {
            $this->response->setJsonContent( $return, JSON_PRETTY_PRINT ) ;
        } else {
            if ($this->request->isAjax() == 'xhr') {
               $this->response->setJsonContent( [] );
            } else {
                $this->response->setHeader('Content-Type', 'image/gif');
                $this->response->setContent(base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw=='));
            }
        }
        $dispatcher->setReturnedValue($this->response);
    }


    /**
     * This action emulates any other action
     *
     * @example /track/
     * @return array
     */
    public function trackAction(string $safe64Url, string $safe64Qs = null) : array {

        $this->trackUrl = base64_decode(strtr($safe64Url, '._-', '+/='));
        $this->trackQs  = !!$safe64Qs ? base64_decode(strtr($safe64Qs, '._-', '+/=')) : '';
        parse_str($this->trackQs, $this->trackQuery);

        # Simulation of execution of original controller / action to obtain the same page dataset
        $r = clone $this->router;
        $r->handle($this->trackUrl);

        $d = clone $this->dispatcher;
        $d->setDi($this->di);

        $d->setNameSpaceName( $r->getNameSpaceName() );
        $d->setControllerName( $r->getControllerName() );
        $d->setActionName( $r->getActionName() );
        $d->setParams( $r->getParams() );

        # In order for controller to use original query string parameters.
        $_GET = $this->trackQuery;

        # Execute original action, returning its controller
        $controller = $d->dispatch();

        if ($controller instanceOf OutputController) {
            $this->handler = $controller->getInternalName();
            return $controller->getTracking();
        } else {
            return [];
        }
   }
}

