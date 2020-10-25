<?php

use Illuminate\Support\Arr;

use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Controller;

/**
 *
 */
class OutputController extends Controller {

    /**
     * To identify which controller handled the request, using dot notation
     */
    public function getInternalName() : string {
        // d($this);
        return trim(strtolower(sprintf(
            '%s.%s.%s',
            str_replace('\\','.', $this->di->getRouter()->getNameSpaceName()),
            $this->di->getRouter()->getControllerName(),
            $this->di->getRouter()->getActionName())
        ), '.');
    }
    /**
     * List of datapoints to enter the tracking flow, in dot notation
     *
     * @see OutputController::getTracking()
     */
    protected function getTrackableFields() : array {
        return [];
    }

    /**
     * Function to check if request is to be tracked
     *
     * @return bool
     */
    protected function isTrackingAllowed() : bool {
        return true;
    }

    /**
     * Generates a list of datapoints to track based on view data
     * List of datapoints can be defined in constant `TRACKING` withing controllers
     *
     * @return array
     */
    final public function getTracking() : array {
        $return = array();
        foreach ($this->getTrackableFields() as $field) {
            $val = Arr::get( $this->view->getParamsToView(), $field );
            if (!is_null($val)) {
                $return[ $field ] = $val;
            }
        }
        // d($return);
        return array_undot($return);
    }


    public function beforeExecuteRoute(Dispatcher $dispatcher) {
        Timing::start($this->getInternalName());
    }

    public function afterExecuteRoute(Dispatcher $dispatcher) {
        Timing::stop($this->getInternalName());

        $this->view->setVars(array_merge(
            // Dataset coming from controller
            $dispatcher->getReturnedValue(),
            // Defaults
            array(
                'config'  => $this->di->getConfig()->toArray(),
                'tracker' => $this->di->getUrl()->get([
                    'for' => 'tracker',
                    'url' => strtr(base64_encode(strtok($_SERVER["REQUEST_URI"], '?')), '+/=', '._-'),
                    'qs'  => strtr(base64_encode($_SERVER['QUERY_STRING']), '+/=', '._-'),
                ])
            )
        ));

        Timing::break('total');
        $metrics = array(
            't' => Timing::getTimings($round = 3, $slowThreshold = -INF)
        );
        

        // if ($timings[0]) {
        //     $metrics['timings'] = array(
        //         'slowest' => $timings[0],
        //         't' => $timings[1],
        //         'total' => Timing::getElapsed()
        //     );
        // }

        $this->response->setHeader('X-Metrics', json_encode($metrics) );

        if ($this->view->isDisabled()) {
            $this->response->setJsonContent( $this->view->getParamsToView(), JSON_PRETTY_PRINT ) ;
            // $this->response->setContent( json_encode($this->view->getParamsToView(), JSON_PRETTY_PRINT )) ;
            $dispatcher->setReturnedValue($this->response);
        } else {

            if (!$this->view->getMainView() ) {
                $file = sprintf('%s/%s/%s',
                    str_replace('\\', '/', $dispatcher->getNameSpaceName()),
                    $dispatcher->getControllerName(),
                    $dispatcher->getActionName()
                );
                $view = APP_PATH . '/views/' . $file . '.twig';
                if (!file_exists($view)) {
                    throw new Exception("View doesnt exist: $view");
                }
                $this->view->setMainView($file);
            }
        }
    }
}