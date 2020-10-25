<?php
if ( ! function_exists('cache')) {
    function cache(){
       return Phalcon\DI::getDefault()->getCache();
    }
}

if ( ! function_exists('permacache')) {
    function permacache(){
     return Phalcon\DI::getDefault()->getPermacache();
    }
}


if ( ! function_exists('conf')) {
    function conf(){
       return Phalcon\DI::getDefault()->getConfig();
    }
}

if ( ! function_exists('debug'))
{
    function debug($str, $label = 'messages') {
        try {
            Phalcon\DI::getDefault()->getDebug()->addMessage($str, $label);
        } catch (\Exception $e) {
            try{
                Phalcon\DI::getDefault()->getLogger()()->debug($str,array('label'=>$label));
            } catch (\Exception $e) {}
        }
    }
}

if ( ! function_exists('di'))
{
    function di() {
        return Phalcon\DI::getDefault();
    }
}


if ( ! function_exists('d'))
{
    function d() {
      $traces = debug_backtrace(); 
      dump($traces[0]['file'].':'.$traces[0]['line']);
      call_user_func_array('dump', func_get_args());
      die;
    }
}

if ( ! function_exists('locale')) {
    function locale() {
       return Phalcon\DI::getDefault()->getLocale();
    }
}


if ( ! function_exists('redis')) {
    function redis(string $type, bool $newInstance = false) {
       return Phalcon\DI::getDefault()->getRedis($type, $newInstance);
    }
}


// use Illuminate\Support\Arr;
// use Illuminate\Support\Str;

// if ( ! function_exists('build_html_calendar'))
// {
//     /**
//      * Returns the calendar's html for the given year and month.
//      *
//      * @param $year (Integer) The year, e.g. 2015.
//      * @param $month (Integer) The month, e.g. 7.
//      * @param $events (Array) An array of events where the key is the day's date
//      * in the format "Y-m-d", the value is an array with 'text' and 'link'.
//      * @return (String) The calendar's html.
//      */
//     function build_html_calendar($year, $month, $events = null) {

//       // CSS classes
//       $css_cal = 'calendar';
//       $css_cal_row = 'calendar-row';
//       $css_cal_day_head = 'calendar-day-head';
//       $css_cal_day = 'calendar-day';
//       $css_cal_day_number = 'day-number';
//       $css_cal_day_blank = 'calendar-day-np';
//       $css_cal_day_event = 'calendar-day-event';
//       $css_cal_event = 'calendar-event';

//       // Table headings
//       $headings = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];

//       // Start: draw table
//       $calendar =
//         "<table cellpadding='0' cellspacing='0' class='{$css_cal}'>" .
//         "<tr class='{$css_cal_row}'>" .
//         "<td class='{$css_cal_day_head}'>" .
//         implode("</td><td class='{$css_cal_day_head}'>", $headings) .
//         "</td>" .
//         "</tr>";

//       // Days and weeks
//       $running_day = date('N', mktime(0, 0, 0, $month, 1, $year));
//       $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));

//       // Row for week one
//       $calendar .= "<tr class='{$css_cal_row}'>";

//       // Print "blank" days until the first of the current week
//       for ($x = 1; $x < $running_day; $x++) {
//         $calendar .= "<td class='{$css_cal_day_blank}'> </td>";
//       }

//       // Keep going with days...
//       for ($day = 1; $day <= $days_in_month; $day++) {

//         // Check if there is an event today
//         $cur_date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
//         $draw_event = false;
//         if (isset($events) && isset($events[$cur_date])) {
//           $draw_event = true;
//         }

//         // Day cell
//         $calendar .= $draw_event ?
//           "<td class='{$css_cal_day} {$css_cal_day_event}'>" :
//           "<td class='{$css_cal_day}'>";

//         // Add the day number
//         $calendar .= "<div class='{$css_cal_day_number}'>" . $day . "</div>";

//         // Insert an event for this day
//         if ($draw_event) {
//           $calendar .=
//             "<div class='{$css_cal_event}'>" .
//             "<a href='{$events[$cur_date]['href']}'>" .
//             $events[$cur_date]['text'] .
//             "</a>" .
//             "</div>";
//         }

//         // Close day cell
//         $calendar .= "</td>";

//         // New row
//         if ($running_day == 7) {
//           $calendar .= "</tr>";
//           if (($day + 1) <= $days_in_month) {
//             $calendar .= "<tr class='{$css_cal_row}'>";
//           }
//           $running_day = 1;
//         }

//         // Increment the running day
//         else {
//           $running_day++;
//         }

//       } // for $day

//       // Finish the rest of the days in the week
//       if ($running_day != 1) {
//         for ($x = $running_day; $x <= 7; $x++) {
//           $calendar .= "<td class='{$css_cal_day_blank}'> </td>";
//         }
//       }

//       // Final row
//       $calendar .= "</tr>";

//       // End the table
//       $calendar .= '</table>';

//       // All done, return result
//       return $calendar;
//     }
// }
// if ( ! function_exists('crawlerDetect'))
// {
//     function crawlerDetect($USER_AGENT = null) {
//         if (is_null($USER_AGENT)) {
//             $USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
//         }

//         $crawlers = array(
//         'Google' => 'Google',
//         'MSN' => 'msnbot',
//         'Rambler' => 'Rambler',
//         'Yahoo' => 'Yahoo',
//         'AbachoBOT' => 'AbachoBOT',
//         'accoona' => 'Accoona',
//         'AcoiRobot' => 'AcoiRobot',
//         'ASPSeek' => 'ASPSeek',
//         'CrocCrawler' => 'CrocCrawler',
//         'Dumbot' => 'Dumbot',
//         'FAST-WebCrawler' => 'FAST-WebCrawler',
//         'GeonaBot' => 'GeonaBot',
//         'Gigabot' => 'Gigabot',
//         'Lycos spider' => 'Lycos',
//         'MSRBOT' => 'MSRBOT',
//         'Altavista robot' => 'Scooter',
//         'AltaVista robot' => 'Altavista',
//         'ID-Search Bot' => 'IDBot',
//         'eStyle Bot' => 'eStyle',
//         'Scrubby robot' => 'Scrubby',
//         'Facebook' => 'facebookexternalhit',
//         );
//         $crawlers_agents = implode('|',$crawlers);
//         return  !(strpos($crawlers_agents, $USER_AGENT) === false);
//     }
// }

// if ( ! function_exists('dump_sql') )
// {
//     function dump_sql($sql, $params) {
//         foreach ($params as $k => $v) {
//             $sql = str_replace(':'.$k, $v, $sql);
//         }
//         echo($sql . "\n");
//         die;
//     }
// }

// if ( ! function_exists('_trans_timed') )
// {
//     function _trans_timed($time, $locale = null) {
//         if (is_null($locale)) { $locale = app()->getLocale(); }

//         if ($time instanceOf Carbon\Carbon) {
//             $time = $time->format('Y-m-d H:i:s');
//         }

//         if (is_numeric($time)) {
//             $t = $time;
//         } else {
//             //Check if time is complete with date 2016-10-18 04:15:00
//             $time = explode(' ', $time);
//             if(count($time) > 1)
//                 $time = $time[1];
//             else
//                 $time = $time[0];
//             $time = explode(':',$time);
//             $hours = $time[0];
//             $mins  = isset($time[1]) ? $time[1] : 0;
//             $secs  = isset($time[2]) ? $time[2] : 0;
//             $t = mktime((int)$hours,(int)$mins,(int)$secs,date('m'),date('d'),date('Y'));
//         }

//         if (date('s',$t) >= 30) {
//             $t += 60 - date('s',$t);
//         }

//         switch ($locale) {
//             case "fr":
//             case "fr_be":
//             case "fr_ca":
//             case "fr_ch":
//             case "fr_lu":
//                 return date('H\\hi',$t);
//             case 'ja':
//                 return str_replace(array('am','pm'),array('åˆå‰','åˆå¾Œ'),date('ah:i',$t));

//             case 'en':
//                 // if (app()->getTld() == 'com' && FROM_COUNTRY == 'us') {
//                 return ltrim(date('h:ia',$t),'0');
//                 // }

//             default:
//                 return date('H:i',$t);
//         }
//     }
// }

// if ( ! function_exists('_trans_durationed') )
// {
//     function _trans_durationed($duration, $locale = null) {
//         $h = floor($duration / (60*60));
//         $duration = $duration % (60*60);
//         $m = floor($duration / 60);
//         if (is_null($locale)) { $locale = app()->getLocale(); }
        
//         $params = array( 'hours' => $h, 'mins' => $m );
//         $fallback =  sprintf("%02d:%02d", $h, $m);

//         if ($h && $m) $r = app()->getTrans()->trans("main.durationFormat", $params, $fallback);
//         elseif ($h) $r = app()->getTrans()->trans("main.durationFormatHour", $params, $fallback);
//         elseif ($m) $r = app()->getTrans()->trans("main.durationFormatMinute", $params, $fallback);
//         return $r;
//     } 
// }

// if ( ! function_exists('_trans_durationed_min') )
// {
//     function _trans_durationed_min($duration, $locale = null) {
//         $h = floor($duration / (60*60));
//         $duration = $duration % (60*60);
//         $m = floor($duration / 60);
//         if (is_null($locale)) { $locale = app()->getLocale(); }
        
//         $params = array( 'hours' => $h, 'mins' => $m );
//         $fallback =  sprintf("%02d:%02d", $h, $m);

//         if ($h && $m) $r = app()->getTrans()->trans("main.durationFormat", $params, $fallback);
//         elseif ($h) $r = app()->getTrans()->trans("main.durationFormatHour", $params, $fallback);
//         elseif ($m) $r = app()->getTrans()->trans("main.durationFormatMinute", $params, $fallback);
//         return preg_replace('/\sh/', 'h', preg_replace('/\sm/', 'm', $r));
//     } 
// }

// if ( ! function_exists('_trans_named') ) 
// {
//     function _trans_named($object, $locale = null) {
//         if (!isset($locale)) { $locale = app()->getLocale(); }
//         if (isset($object['names'][$locale])) {
//             return $object['names'][$locale];
//         }
//         if (isset($object['names']['en'])) {
//             return $object['names']['en'];
//         }
//         // Generic case where there is no translation, return the first entry in names
//         // Usually applies to station names
//         if (!!$object['names']) {
//             foreach ($object['names'] as $name) {
//                 return $name;
//             }
//         }
//         //@TODO Handle case of another locale 
//     }
// }

// if ( ! function_exists('slack'))
// {
//     function slack($message, $channel, $user = null, $footer_icon = null) {
//         if (is_null($user)) $user = gethostname();
//         $url = app()->getConfig()->application->slack[ str_replace('#', '', $channel) ];
//         Requests::post($url, array(), array(
//             'payload' => json_encode(array_filter(array(
//                 'username' => $user,
//                 'text' => $message,
//                 'footer_icon' => $footer_icon
//             )))
//         ), array('timeout' => 10));
//     }
// }

// if ( ! function_exists('production_debug'))
// {
//     function production_debug($message) {
//         try {
//             //@TODO
//             Queue::connection('messages')->push('Messages', array('message' => $message, 'host' => gethostname(), 'ts' => gmdate('Y-m-d H:i:s') ));
//         } catch (Exception $e) {
//         }
//     }
// }


// }
if ( ! function_exists('_zip'))
{
    function _zip($data) {
        return base64_encode(gzdeflate(serialize($data)));
    }
}

if ( ! function_exists('_unzip'))
{
    function _unzip(&$string) {
        return !!$string ? unserialize(gzinflate(base64_decode($string))) : false;
    }
}
// if ( ! function_exists('_zip2'))
// {
//     function _zip2($data) {
//       return str_replace(array('/','+'), array('::s::','::x::'), _zip($data));
//     }
// }
// if ( ! function_exists('_unzip2'))
// {
//     function _unzip2(&$string) {
//         $string = str_replace(array('::s::','::x::'), array('/','+'), $string);
//         return _unzip($string);
//     }
// }

// if ( ! function_exists('get_locale')) {
//     function get_locale(){
//        return Phalcon\DI::getDefault()->getLocale();        
//     }

// }
// if ( ! function_exists('app')) {
//     function app(){
//        return Phalcon\DI::getDefault();
//     }
// }

// if ( ! function_exists('_metrics')) {
//     function _metrics($message = null, $data = array(), $queue = 'webmetrics') {
//         $now = microtime(true);
//         $obj = json_encode(array(
//             'v' => 1,
//             'ts'=> gmdate('Y-m-d\TH:i:s', $now).sprintf('.%03dZ',round(($now-floor($now))*1000)),
//             'host' => gethostname(),
//             'message' => $message,
//             'data' => $data
//         ));
//         app()->getRedisMetrics()->lpush(
//             $queue,
//             $obj
//         );
//     }
// }

// if ( ! function_exists('_metrics_async')) {
//     if (!isset($GLOBALS['REQ_ID'])) {
//         $GLOBALS['REQ_ID'] = uniqid();
//     }
//     function _metrics_async($data = array(), $queue = 'debug') {
//         // $backtrace = debug_backtrace(null,2);
//         // $str = array();
        
//         // foreach ($backtrace as $b) {
//         //     if(isset($b['file']) && isset($b['line'])) {
//         //         $str[] = sprintf('%s:%s', basename($b['file']),$b['line']);
//         //     }
//         // }

//         // $str = implode(' <- ', $str);

//         $defaultData = array(
//             // 'reqId' => $GLOBALS['REQ_ID'],
//             'url' => sprintf("%s%s", @$_SERVER['HTTP_HOST'], @$_SERVER['REQUEST_URI']),
//             'tld' => @$_SERVER['HTTP_TLD'],
//             'cf_c' => @$_SERVER['HTTP_CF_IPCOUNTRY'],
//             'ua' => @$_SERVER['HTTP_USER_AGENT'],
//             //'stack' => (new \MetricsSession())->getStack()
//         );

//         app()->getRedisLocal()->rpush('queue:background',json_encode(array(
//             'task' => 'metrics',
//             'data' => array_merge($defaultData, $data) 
//         )));
//     }
// }


// if ( ! function_exists('append_config'))
// {
//     /**
//      * Assign high numeric IDs to a config item to force appending.
//      *
//      * @param  array  $array
//      * @return array
//      */
//     function append_config(array $array)
//     {
//         $start = 9999;

//         foreach ($array as $key => $value)
//         {
//             if (is_numeric($key))
//             {
//                 $start++;

//                 $array[$start] = array_pull($array, $key);
//             }
//         }

//         return $array;
//     }
// }

// if ( ! function_exists('array_add'))
// {
//     /**
//      * Add an element to an array using "dot" notation if it doesn't exist.
//      *
//      * @param  array   $array
//      * @param  string  $key
//      * @param  mixed   $value
//      * @return array
//      */
//     function array_add($array, $key, $value)
//     {
//         return Arr::add($array, $key, $value);
//     }
// }

// if ( ! function_exists('array_build'))
// {
//     /**
//      * Build a new array using a callback.
//      *
//      * @param  array     $array
//      * @param  \Closure  $callback
//      * @return array
//      */
//     function array_build($array, Closure $callback)
//     {
//         return Arr::build($array, $callback);
//     }
// }

// if ( ! function_exists('array_divide'))
// {
//     /**
//      * Divide an array into two arrays. One with keys and the other with values.
//      *
//      * @param  array  $array
//      * @return array
//      */
//     function array_divide($array)
//     {
//         return Arr::divide($array);
//     }
// }

// if ( ! function_exists('array_dot'))
// {
//     /**
//      * Flatten a multi-dimensional associative array with dots.
//      *
//      * @param  array   $array
//      * @param  string  $prepend
//      * @return array
//      */
//     function array_dot($array, $prepend = '')
//     {
//         return Arr::dot($array, $prepend);
//     }
// }

// if ( ! function_exists('array_except'))
// {
//     /**
//      * Get all of the given array except for a specified array of items.
//      *
//      * @param  array  $array
//      * @param  array|string  $keys
//      * @return array
//      */
//     function array_except($array, $keys)
//     {
//         return Arr::except($array, $keys);
//     }
// }

// if ( ! function_exists('array_fetch'))
// {
//     /**
//      * Fetch a flattened array of a nested array element.
//      *
//      * @param  array   $array
//      * @param  string  $key
//      * @return array
//      */
//     function array_fetch($array, $key)
//     {
//         return Arr::fetch($array, $key);
//     }
// }

// if ( ! function_exists('array_first'))
// {
//     /**
//      * Return the first element in an array passing a given truth test.
//      *
//      * @param  array     $array
//      * @param  \Closure  $callback
//      * @param  mixed     $default
//      * @return mixed
//      */
//     function array_first($array, $callback, $default = null)
//     {
//         return Arr::first($array, $callback, $default);
//     }
// }

// if ( ! function_exists('array_last'))
// {
//     /**
//      * Return the last element in an array passing a given truth test.
//      *
//      * @param  array     $array
//      * @param  \Closure  $callback
//      * @param  mixed     $default
//      * @return mixed
//      */
//     function array_last($array, $callback, $default = null)
//     {
//         return Arr::last($array, $callback, $default);
//     }
// }

// if ( ! function_exists('array_flatten'))
// {
//     /**
//      * Flatten a multi-dimensional array into a single level.
//      *
//      * @param  array  $array
//      * @return array
//      */
//     function array_flatten($array)
//     {
//         return Arr::flatten($array);
//     }
// }

// if ( ! function_exists('array_forget'))
// {
//     /**
//      * Remove one or many array items from a given array using "dot" notation.
//      *
//      * @param  array  $array
//      * @param  array|string  $keys
//      * @return void
//      */
//     function array_forget(&$array, $keys)
//     {
//         return Arr::forget($array, $keys);
//     }
// }

// if ( ! function_exists('array_get'))
// {
//     /**
//      * Get an item from an array using "dot" notation.
//      *
//      * @param  array   $array
//      * @param  string  $key
//      * @param  mixed   $default
//      * @return mixed
//      */
//     function array_get($array, $key, $default = null)
//     {
//         return Arr::get($array, $key, $default);
//     }
// }

// if ( ! function_exists('array_has'))
// {
//     /**
//      * Check if an item exists in an array using "dot" notation.
//      *
//      * @param  array   $array
//      * @param  string  $key
//      * @return bool
//      */
//     function array_has($array, $key)
//     {
//         return Arr::has($array, $key);
//     }
// }

// if ( ! function_exists('array_only'))
// {
//     /**
//      * Get a subset of the items from the given array.
//      *
//      * @param  array  $array
//      * @param  array|string  $keys
//      * @return array
//      */
//     function array_only($array, $keys)
//     {
//         return Arr::only($array, $keys);
//     }
// }

// if ( ! function_exists('array_pluck'))
// {
//     /**
//      * Pluck an array of values from an array.
//      *
//      * @param  array   $array
//      * @param  string  $value
//      * @param  string  $key
//      * @return array
//      */
//     function array_pluck($array, $value, $key = null)
//     {
//         return Arr::pluck($array, $value, $key);
//     }
// }

// if ( ! function_exists('array_pull'))
// {
//     /**
//      * Get a value from the array, and remove it.
//      *
//      * @param  array   $array
//      * @param  string  $key
//      * @param  mixed   $default
//      * @return mixed
//      */
//     function array_pull(&$array, $key, $default = null)
//     {
//         return Arr::pull($array, $key, $default);
//     }
// }

// if ( ! function_exists('array_set'))
// {
//     /**
//      * Set an array item to a given value using "dot" notation.
//      *
//      * If no key is given to the method, the entire array will be replaced.
//      *
//      * @param  array   $array
//      * @param  string  $key
//      * @param  mixed   $value
//      * @return array
//      */
//     function array_set(&$array, $key, $value)
//     {
//         return Arr::set($array, $key, $value);
//     }
// }

// if ( ! function_exists('array_sort'))
// {
//     /**
//      * Sort the array using the given Closure.
//      *
//      * @param  array     $array
//      * @param  \Closure  $callback
//      * @return array
//      */
//     function array_sort($array, Closure $callback)
//     {
//         return Arr::sort($array, $callback);
//     }
// }

// if ( ! function_exists('array_where'))
// {
//     /**
//      * Filter the array using the given Closure.
//      *
//      * @param  array     $array
//      * @param  \Closure  $callback
//      * @return array
//      */
//     function array_where($array, Closure $callback)
//     {
//         return Arr::where($array, $callback);
//     }
// }


// if ( ! function_exists('camel_case'))
// {
//     /**
//      * Convert a value to camel case.
//      *
//      * @param  string  $value
//      * @return string
//      */
//     function camel_case($value)
//     {
//         return Str::camel($value);
//     }
// }

// if ( ! function_exists('class_basename'))
// {
//     /**
//      * Get the class "basename" of the given object / class.
//      *
//      * @param  string|object  $class
//      * @return string
//      */
//     function class_basename($class)
//     {
//         $class = is_object($class) ? get_class($class) : $class;

//         return basename(str_replace('\\', '/', $class));
//     }
// }

// if ( ! function_exists('class_uses_recursive'))
// {
//     /**
//      * Returns all traits used by a class, it's subclasses and trait of their traits
//      *
//      * @param  string  $class
//      * @return array
//      */
//     function class_uses_recursive($class)
//     {
//         $results = [];

//         foreach (array_merge([$class => $class], class_parents($class)) as $class)
//         {
//             $results += trait_uses_recursive($class);
//         }

//         return array_unique($results);
//     }
// }


// if ( ! function_exists('data_get'))
// {
//     /**
//      * Get an item from an array or object using "dot" notation.
//      *
//      * @param  mixed   $target
//      * @param  string  $key
//      * @param  mixed   $default
//      * @return mixed
//      */
//     function data_get($target, $key, $default = null)
//     {
//         if (is_null($key)) return $target;

//         foreach (explode('.', $key) as $segment)
//         {
//             if (is_array($target))
//             {
//                 if ( ! array_key_exists($segment, $target))
//                 {
//                     return value($default);
//                 }

//                 $target = $target[$segment];
//             }
//             elseif (is_object($target))
//             {
//                 if ( ! isset($target->{$segment}))
//                 {
//                     return value($default);
//                 }

//                 $target = $target->{$segment};
//             }
//             else
//             {
//                 return value($default);
//             }
//         }

//         return $target;
//     }
// }

// if ( ! function_exists('dd'))
// {
//     /**
//      * Dump the passed variables and end the script.
//      *
//      * @param  mixed
//      * @return void
//      */
//     function dd()
//     {
//         array_map(function($x) { var_dump($x); }, func_get_args()); die;
//     }
// }

// if ( ! function_exists('e'))
// {
//     /**
//      * Escape HTML entities in a string.
//      *
//      * @param  string  $value
//      * @return string
//      */
//     function e($value)
//     {
//         return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
//     }
// }

// if ( ! function_exists('ends_with'))
// {
//     /**
//      * Determine if a given string ends with a given substring.
//      *
//      * @param  string  $haystack
//      * @param  string|array  $needles
//      * @return bool
//      */
//     function ends_with($haystack, $needles)
//     {
//         return Str::endsWith($haystack, $needles);
//     }
// }

// if ( ! function_exists('head'))
// {
//     /**
//      * Get the first element of an array. Useful for method chaining.
//      *
//      * @param  array  $array
//      * @return mixed
//      */
//     function head($array)
//     {
//         return reset($array);
//     }
// }


// if ( ! function_exists('last'))
// {
//     /**
//      * Get the last element from an array.
//      *
//      * @param  array  $array
//      * @return mixed
//      */
//     function last($array)
//     {
//         return end($array);
//     }
// }

// if ( ! function_exists('object_get'))
// {
//     /**
//      * Get an item from an object using "dot" notation.
//      *
//      * @param  object  $object
//      * @param  string  $key
//      * @param  mixed   $default
//      * @return mixed
//      */
//     function object_get($object, $key, $default = null)
//     {
//         if (is_null($key) || trim($key) == '') return $object;

//         foreach (explode('.', $key) as $segment)
//         {
//             if ( ! is_object($object) || ! isset($object->{$segment}))
//             {
//                 return value($default);
//             }

//             $object = $object->{$segment};
//         }

//         return $object;
//     }
// }

// if ( ! function_exists('preg_replace_sub'))
// {
//     /**
//      * Replace a given pattern with each value in the array in sequentially.
//      *
//      * @param  string  $pattern
//      * @param  array   $replacements
//      * @param  string  $subject
//      * @return string
//      */
//     function preg_replace_sub($pattern, &$replacements, $subject)
//     {
//         return preg_replace_callback($pattern, function($match) use (&$replacements)
//         {
//             return array_shift($replacements);

//         }, $subject);
//     }
// }

// if ( ! function_exists('snake_case'))
// {
//     /**
//      * Convert a string to snake case.
//      *
//      * @param  string  $value
//      * @param  string  $delimiter
//      * @return string
//      */
//     function snake_case($value, $delimiter = '_')
//     {
//         return Str::snake($value, $delimiter);
//     }
// }

// if ( ! function_exists('starts_with'))
// {
//     /**
//      * Determine if a given string starts with a given substring.
//      *
//      * @param  string  $haystack
//      * @param  string|array  $needles
//      * @return bool
//      */
//     function starts_with($haystack, $needles)
//     {
//         return Str::startsWith($haystack, $needles);
//     }
// }

// if ( ! function_exists('str_contains'))
// {
//     /**
//      * Determine if a given string contains a given substring.
//      *
//      * @param  string  $haystack
//      * @param  string|array  $needles
//      * @return bool
//      */
//     function str_contains($haystack, $needles)
//     {
//         return Str::contains($haystack, $needles);
//     }
// }

// if ( ! function_exists('str_finish'))
// {
//     /**
//      * Cap a string with a single instance of a given value.
//      *
//      * @param  string  $value
//      * @param  string  $cap
//      * @return string
//      */
//     function str_finish($value, $cap)
//     {
//         return Str::finish($value, $cap);
//     }
// }

// if ( ! function_exists('str_is'))
// {
//     /**
//      * Determine if a given string matches a given pattern.
//      *
//      * @param  string  $pattern
//      * @param  string  $value
//      * @return bool
//      */
//     function str_is($pattern, $value)
//     {
//         return Str::is($pattern, $value);
//     }
// }

// if ( ! function_exists('str_limit'))
// {
//     /**
//      * Limit the number of characters in a string.
//      *
//      * @param  string  $value
//      * @param  int     $limit
//      * @param  string  $end
//      * @return string
//      */
//     function str_limit($value, $limit = 100, $end = '...')
//     {
//         return Str::limit($value, $limit, $end);
//     }
// }

// if ( ! function_exists('str_plural'))
// {
//     /**
//      * Get the plural form of an English word.
//      *
//      * @param  string  $value
//      * @param  int     $count
//      * @return string
//      */
//     function str_plural($value, $count = 2)
//     {
//         return Str::plural($value, $count);
//     }
// }

// if ( ! function_exists('str_random'))
// {
//     /**
//      * Generate a more truly "random" alpha-numeric string.
//      *
//      * @param  int  $length
//      * @return string
//      *
//      * @throws \RuntimeException
//      */
//     function str_random($length = 16)
//     {
//         return Str::random($length);
//     }
// }

// if ( ! function_exists('str_replace_array'))
// {
//     /**
//      * Replace a given value in the string sequentially with an array.
//      *
//      * @param  string  $search
//      * @param  array   $replace
//      * @param  string  $subject
//      * @return string
//      */
//     function str_replace_array($search, array $replace, $subject)
//     {
//         foreach ($replace as $value)
//         {
//             $subject = preg_replace('/'.$search.'/', $value, $subject, 1);
//         }

//         return $subject;
//     }
// }

// if ( ! function_exists('str_singular'))
// {
//     /**
//      * Get the singular form of an English word.
//      *
//      * @param  string  $value
//      * @return string
//      */
//     function str_singular($value)
//     {
//         return Str::singular($value);
//     }
// }

// if ( ! function_exists('studly_case'))
// {
//     /**
//      * Convert a value to studly caps case.
//      *
//      * @param  string  $value
//      * @return string
//      */
//     function studly_case($value)
//     {
//         return Str::studly($value);
//     }
// }

// if ( ! function_exists('trait_uses_recursive'))
// {
//     /**
//      * Returns all traits used by a trait and its traits
//      *
//      * @param  string  $trait
//      * @return array
//      */
//     function trait_uses_recursive($trait)
//     {
//         $traits = class_uses($trait);

//         foreach ($traits as $trait)
//         {
//             $traits += trait_uses_recursive($trait);
//         }

//         return $traits;
//     }
// }

// if ( ! function_exists('trans'))
// {
//     /**
//      * Translate the given message.
//      *
//      * @param  string  $id
//      * @param  array   $parameters
//      * @return string
//      */
//     function trans($id, $parameters = array())
//     {
//         return Phalcon\DI::getDefault()->getTrans()->trans(
//             $id, $parameters
//         );
//     }
// }

// if ( ! function_exists('value'))
// {
//     /**
//      * Return the default value of the given value.
//      *
//      * @param  mixed  $value
//      * @return mixed
//      */
//     function value($value)
//     {
//         return $value instanceof Closure ? $value() : $value;
//     }
// }

// if ( ! function_exists('with'))
// {
//     /**
//      * Return the given object. Useful for chaining.
//      *
//      * @param  mixed  $object
//      * @return mixed
//      */
//     function with($object)
//     {
//         return $object;
//     }
// }

// if ( ! function_exists('digitsToSec'))
// {
//     function digitsToSec($time){
//         if (is_null($time)) 
//             return $time;

//         list($h,$m) = explode(':', $time);
//         return intval($h)*60*60 + intval($m)*60;
//     }
// }

// if ( ! function_exists('digitsToMin'))
// {
//     function digitsToMin($time){
//         if (is_null($time)) 
//             return $time;

//         $time = explode(':', $time);
//         return ($time[0] * 60) + $time[1];
//     }
// }

// if ( ! function_exists('stringClean'))
// {
//     function stringClean($string) {
//        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
//        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
//     }
// }

// if ( ! function_exists('currentUrl'))
// {
//     function currentUrl() {
//         return sprintf('%s://%s%s', @$_SERVER['REQUEST_SCHEME'], @$_SERVER['HTTP_HOST'], @$_SERVER['REQUEST_URI']);
//     }
// }

// if ( ! function_exists('baseUrl'))
// {
//     function baseUrl() {
//         return sprintf('%s://%s', @$_SERVER['REQUEST_SCHEME'], @$_SERVER['HTTP_HOST']);
//     }
// }

// if ( ! function_exists('carbonToMoment'))
// {
//     function carbonToMoment($date) {
//         $match = [
//             'd' => 'DD',
//             'D' => 'ddd',
//             'jS' => 'Do',
//             'j' => 'D',
//             'l' => 'dddd',
//             'N' => '',
//             'w' => 'd',
//             'z' => 'DDD',
//             'W' => 'W',
//             'F' => 'MMMM',
//             'm' => 'MM',
//             'M' => 'MMM',
//             'n' => 'M',
//             't' => '',
//             'L' => '',
//             'o' => 'Y',
//             'Y' => 'YYYY',
//             'y' => 'YY',
//             'a' => 'a',
//             'A' => 'A',
//             'B' => '',
//             'g' => 'h',
//             'G' => 'H',
//             'h' => 'hh',
//             'H' => 'HH',
//             'i' => 'mm',
//             's' => 'ss',
//             'u' => 'SSSSSSSSS',
//             'e' => '',
//             'I' => '',
//             'O' => 'ZZ',
//             'P' => 'Z',
//             'T' => 'zz',
//             'Z' => '',
//             'c' => '',
//             'r' => '',
//             'U' => 'X'
//         ];

//         return strtr($date, $match);
//     }
// }

// if ( ! function_exists('localeToCountryCode'))
// {
//     function localeToIcon($locale) {
//         $match = [
//             'en' => 'flag-eu',
//             'en_be' => 'flag-be',
//             'de_ch' => 'flag-ch',
//             'de_at' => 'flag-at',
//             'bg' => 'flag-bg',
//             'es_ar' => 'flag-ar',
//             'tr' => 'flag-tr',
//             'en_ie' => 'flag-ie',
//             'pt' => 'flag-pt',
//             'pt_br' => 'flag-br',
//             'el' => 'flag-gr',
//             'en_cn' => 'flag-cn',
//             'en_vi' => 'flag-vn',
//             'en_ca' => 'flag-ca',
//             'da' => 'flag-dk',
//             'it' => 'flag-it',
//             'fr' => 'flag-fr',
//             'es' => 'flag-es',
//             'ca' => 'flag-ct',
//             'de' => 'flag-de',
//             'en_uk' => 'flag-gb',
//             'nl' => 'flag-nl',
//             'hu' => 'flag-hu',
//             'ja' => 'flag-jp',
//             'ru' => 'flag-ru',
//             'ro' => 'flag-ro',
//             'pl' => 'flag-pl',
//             'en_in' => 'flag-in',
//             'fr_lu' => 'flag-lu',
//             'hr' => 'flag-hr',
//             'cs' => 'flag-cz',
//             'th' => 'flag-th',
//             'vi' => 'flag-vn',
//             'uk' => 'flag-ua',
//         ];

//         return strtr($locale, $match);
//     }
// }

// if ( ! function_exists('isDevStagingTesting')) {
//     function isDevStagingTesting() {
//         $isProduction = false;
//         if(app()->getEnv() != 'dev')
//             if(app()->getEnv() != 'testing')
//                 if(app()->getEnv() != 'staging')
//                     $isProduction = true;
//         return $isProduction;
//     }
// }

// if ( ! function_exists('getTransportIcon')) {
//     function getTransportIcon($transportsString = null) {
//         if(is_null($transportsString))
//             return null;

//         return baseUrl()."/assets/icons/transports/".$transportsString.".svg";
//     }
// }

// if ( ! function_exists('dated')) {
//     function dated($date, $locale = null, $format = null) {
//         $date = Carbon\Carbon::parse($date);

//         if(!!$format) {
//             $date->setLocale($locale);
//             return $date->format($format);
//         }

//         $weekdays           = explode(',', app()->getTrans()->trans('main.grams.weekdays'));
//         $months             = explode(',', app()->getTrans()->trans('main.grams.months'));
//         $readableDateFormat = app()->getTrans()->trans('main.grams.readableFormat');
//         $s = '';
//         $timestamp = strtotime($date);
//         $iterator = new \ArrayIterator(str_split($readableDateFormat));
//         foreach($iterator as $m) {
//             switch($m) {
//                 case 'f': $s .= strtolower(trim($months[date('n', $timestamp) - 1])); break;
//                 case 'F': $s .= trim($months[date('n', $timestamp) - 1]); break;
//                 case 'M': $s .= substr($months[date('n', $timestamp) - 1], 0, 3); break;
//                 case 'l': $s .= trim($weekdays[date('w', $timestamp)]); break;
//                 case 'D': $s .= substr($weekdays[date('w', $timestamp)], 0, 3); break;
//                 case '\\': 
//                     $iterator->next();
//                     $s .= $iterator->current();
//                 break;
//                 default : $s .= date($m, $timestamp); break;
//             }
//         }
//         return $s;
//     }
// }

// if ( ! function_exists('exec')) {
//     function exec($cmd, $timeout = 60) {
//         $descriptorspec = array(
//             0 => array("pipe", "r"),
//             1 => array("pipe", "w"),
//             2 => array("pipe", "w")
//         );
//         $pipes = array();

//         $endtime = time()+$timeout;

//         $process = proc_open($cmd, $descriptorspec, $pipes);

//         $output = '';
//         if (is_resource($process)) {
//             do {
//                 $timeleft = $endtime - time();
//                 $read = array($pipes[1]);
//                 $exeptions = NULL;
//                 $write = NULL;
//                 stream_select($read, $write, $exeptions, $timeleft, NULL);
//                 if(!empty($read)) {
//                     $output .= fread($pipes[1], 8192);
//                 }
//             } while(!feof($pipes[1]) && $timeleft > 0);
//             if ($timeleft <= 0) {
//                 $this->log->debug('Timeout ('.$timeout.'): ' . $cmd);
//                 proc_terminate($process);
//                 return false;
//             } else {
//                 return $output;
//             }
//         } else {
//             return false;
//         }
//     }
// }