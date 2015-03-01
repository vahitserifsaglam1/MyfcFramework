<?php
namespace Curl;

/**
 * Class Full
 * @package Curl
 *  ZebraCurl sınıfı
 */
class Full {

    public $pause_interval;
    public $threads;
    private $_htmlentities;
    private $_info;
    private $_queue;
    private $_multi_handle;
    private $_response_messages = array(
        0   =>  'CURLE_OK',
        1   =>  'CURLE_UNSUPPORTED_PROTOCOL',
        2   =>  'CURLE_FAILED_INIT',
        3   =>  'CURLE_URL_MALFORMAT',
        4   =>  'CURLE_URL_MALFORMAT_USER',
        5   =>  'CURLE_COULDNT_RESOLVE_PROXY',
        6   =>  'CURLE_COULDNT_RESOLVE_HOST',
        7   =>  'CURLE_COULDNT_CONNECT',
        8   =>  'CURLE_FTP_WEIRD_SERVER_REPLY',
        9   =>  'CURLE_REMOTE_ACCESS_DENIED',
        11  =>  'CURLE_FTP_WEIRD_PASS_REPLY',
        13  =>  'CURLE_FTP_WEIRD_PASV_REPLY',
        14  =>  'CURLE_FTP_WEIRD_227_FORMAT',
        15  =>  'CURLE_FTP_CANT_GET_HOST',
        17  =>  'CURLE_FTP_COULDNT_SET_TYPE',
        18  =>  'CURLE_PARTIAL_FILE',
        19  =>  'CURLE_FTP_COULDNT_RETR_FILE',
        21  =>  'CURLE_QUOTE_ERROR',
        22  =>  'CURLE_HTTP_RETURNED_ERROR',
        23  =>  'CURLE_WRITE_ERROR',
        25  =>  'CURLE_UPLOAD_FAILED',
        26  =>  'CURLE_READ_ERROR',
        27  =>  'CURLE_OUT_OF_MEMORY',
        28  =>  'CURLE_OPERATION_TIMEDOUT',
        30  =>  'CURLE_FTP_PORT_FAILED',
        31  =>  'CURLE_FTP_COULDNT_USE_REST',
        33  =>  'CURLE_RANGE_ERROR',
        34  =>  'CURLE_HTTP_POST_ERROR',
        35  =>  'CURLE_SSL_CONNECT_ERROR',
        36  =>  'CURLE_BAD_DOWNLOAD_RESUME',
        37  =>  'CURLE_FILE_COULDNT_READ_FILE',
        38  =>  'CURLE_LDAP_CANNOT_BIND',
        39  =>  'CURLE_LDAP_SEARCH_FAILED',
        41  =>  'CURLE_FUNCTION_NOT_FOUND',
        42  =>  'CURLE_ABORTED_BY_CALLBACK',
        43  =>  'CURLE_BAD_FUNCTION_ARGUMENT',
        45  =>  'CURLE_INTERFACE_FAILED',
        47  =>  'CURLE_TOO_MANY_REDIRECTS',
        48  =>  'CURLE_UNKNOWN_TELNET_OPTION',
        49  =>  'CURLE_TELNET_OPTION_SYNTAX',
        51  =>  'CURLE_PEER_FAILED_VERIFICATION',
        52  =>  'CURLE_GOT_NOTHING',
        53  =>  'CURLE_SSL_ENGINE_NOTFOUND',
        54  =>  'CURLE_SSL_ENGINE_SETFAILED',
        55  =>  'CURLE_SEND_ERROR',
        56  =>  'CURLE_RECV_ERROR',
        58  =>  'CURLE_SSL_CERTPROBLEM',
        59  =>  'CURLE_SSL_CIPHER',
        60  =>  'CURLE_SSL_CACERT',
        61  =>  'CURLE_BAD_CONTENT_ENCODING',
        62  =>  'CURLE_LDAP_INVALID_URL',
        63  =>  'CURLE_FILESIZE_EXCEEDED',
        64  =>  'CURLE_USE_SSL_FAILED',
        65  =>  'CURLE_SEND_FAIL_REWIND',
        66  =>  'CURLE_SSL_ENGINE_INITFAILED',
        67  =>  'CURLE_LOGIN_DENIED',
        68  =>  'CURLE_TFTP_NOTFOUND',
        69  =>  'CURLE_TFTP_PERM',
        70  =>  'CURLE_REMOTE_DISK_FULL',
        71  =>  'CURLE_TFTP_ILLEGAL',
        72  =>  'CURLE_TFTP_UNKNOWNID',
        73  =>  'CURLE_REMOTE_FILE_EXISTS',
        74  =>  'CURLE_TFTP_NOSUCHUSER',
        75  =>  'CURLE_CONV_FAILED',
        76  =>  'CURLE_CONV_REQD',
        77  =>  'CURLE_SSL_CACERT_BADFILE',
        78  =>  'CURLE_REMOTE_FILE_NOT_FOUND',
        79  =>  'CURLE_SSH',
        80  =>  'CURLE_SSL_SHUTDOWN_FAILED',
        81  =>  'CURLE_AGAIN',
        82  =>  'CURLE_SSL_CRL_BADFILE',
        83  =>  'CURLE_SSL_ISSUER_ERROR',
        84  =>  'CURLE_FTP_PRET_FAILED',
        85  =>  'CURLE_RTSP_CSEQ_ERROR',
        86  =>  'CURLE_RTSP_SESSION_ERROR',
        87  =>  'CURLE_FTP_BAD_FILE_LIST',
        88  =>  'CURLE_CHUNK_FAILED',
    );
    function __construct($htmlentities = true)
    {

        // if the cURL extension is not available, trigger an error and stop execution
        if (!extension_loaded('curl')) trigger_error('php_curl extension is not loaded!', E_USER_ERROR);

        // set defaults for accessing HTTPS servers
        $this->ssl();

        // initialize some internal variables
        $this->_multi_handle = false;
        $this->_info = array();

        // caching is disabled by default
        $this->cache(false);

        // the default number of seconds to wait between batches of URLs
        $this->pause_interval = 0;

        // the default number of parallel, asynchronous, requests to be processed by the library at once.
        $this->threads = 10;

        // by default, run htmlentities() on the response body
        $this->_htmlentities = $htmlentities;

        return true;
    }
    public function init()
    {
        return true;
    }
    public function cache($path, $lifetime = 3600, $compress = true, $chmod = 0755)
    {

        // if we have to enable caching
        if ($path != false)

            // store cache-related properties
            $this->cache = array(
                'path'      =>  $path,
                'lifetime'  =>  $lifetime,
                'chmod'     =>  $chmod,
                'compress'  =>  $compress,
            );

        // if we have to disable caching, disable it
        else $this->cache = false;

    }
    public function cookies($path, $keep = false)
    {
        // file does not exist
        if (!is_file($path)) {

            // attempt to create it
            if (!($handle = fopen($path, 'a')))

                // if file could not be created, trigger an error
                trigger_error('File "' . $path . '" for storing cookies could not be found nor could it automatically be created! Make sure either that the path to the file points to a writable directory, or create the file yourself and make it writable.', E_USER_ERROR);

            // if file could be create, release handle
            fclose($handle);

        }

        // set these options
        $this->option(array(
            CURLOPT_COOKIEJAR   =>  $path,
            CURLOPT_COOKIEFILE  =>  $path,
        ));

    }
    public function download($url, $destination_path, $callback = '')
    {

        // if destination path is not a directory or is not writable, trigger an error message
        if (!is_dir($destination_path) || !is_writable($destination_path)) trigger_error('"' . $destination_path . '" is not a valid path or is not writable!', E_USER_ERROR);

        // set download path
        $this->download_path = rtrim($destination_path, '/\\') . '/';

        // instruct the cURL library that it has to do a binary transfer
        $this->option(array(
            CURLINFO_HEADER_OUT     =>  1,
            CURLOPT_BINARYTRANSFER  =>  1,
            CURLOPT_HEADER          =>  1,
            CURLOPT_FILE            =>  null,
            CURLOPT_HTTPGET         =>  null,
            CURLOPT_NOBODY          =>  null,
            CURLOPT_POST            =>  null,
            CURLOPT_POSTFIELDS      =>  null,
            CURLOPT_USERPWD         =>  null,
        ));

        // prior to PHP 5.3, func_get_args() cannot be used as a function parameter, so we need this intermediary step
        $arguments = func_get_args();

        // prepare the arguments to be passed to the callback function
        // (consisting from the first 3, plus any additional arguments passed to the "download" method)
        $arguments = array_merge(array($url, $callback), array_slice($arguments, 3));

        // process requests, all at once or with pause between batches of URLs
        call_user_func_array(array($this, $this->pause_interval > 0 ? '_process_paused' : '_process'), $arguments);

    }

    public function ftp_download($url, $destination_path, $username = '', $password = '', $callback = '')
    {

        // if he have at least an username, set username/password
        if ($username != '') $this->option(CURLOPT_USERPWD, $username . ':' . $password);

        // download raw data
        $this->option(array(
            CURLINFO_HEADER_OUT     =>  1,
            CURLOPT_BINARYTRANSFER  =>  1,
            CURLOPT_HEADER          =>  1,
            CURLOPT_HTTPGET         =>  null,
            CURLOPT_FILE            =>  null,
            CURLOPT_NOBODY          =>  null,
            CURLOPT_POST            =>  null,
            CURLOPT_POSTFIELDS      =>  null,
            CURLOPT_USERPWD         =>  null,
        ));

        // prior to PHP 5.3, func_get_args() cannot be used as a function parameter
        // so we need this intermediary step
        $arguments = func_get_args();

        // prepare the arguments to be passed to the "download" method
        // (consisting from the first 3, plus any additional arguments passed to the "ftp_download" method)
        $arguments = array_merge(array($url, $destination_path, $callback), array_slice($arguments, 5));

        // call the "download" method
        call_user_func_array(array($this, 'download'), $arguments);

    }
    public function get($url, $callback = '')
    {

        // make sure we perform a GET request
        $this->option(array(
            CURLINFO_HEADER_OUT     =>  1,
            CURLOPT_HEADER          =>  1,
            CURLOPT_HTTPGET         =>  1,
            CURLOPT_NOBODY          =>  0,
            CURLOPT_BINARYTRANSFER  =>  null,
            CURLOPT_FILE            =>  null,
            CURLOPT_POST            =>  null,
            CURLOPT_POSTFIELDS      =>  null,
            CURLOPT_USERPWD         =>  null,
        ));

        // prior to PHP 5.3, func_get_args() cannot be used as a function parameter
        // so we need this intermediary step
        $arguments = func_get_args();

        // process requests, all at once or with pause between batches of URLs
        call_user_func_array(array($this, $this->pause_interval > 0 ? '_process_paused' : '_process'), $arguments);

    }

    public function header($url, $callback = '')
    {

        // no "body" for header requests but make sure we have the headers
        $this->option(array(
            CURLINFO_HEADER_OUT     =>  1,
            CURLOPT_HEADER          =>  1,
            CURLOPT_HTTPGET         =>  1,
            CURLOPT_NOBODY          =>  1,
            CURLOPT_BINARYTRANSFER  =>  null,
            CURLOPT_FILE            =>  null,
            CURLOPT_POST            =>  null,
            CURLOPT_POSTFIELDS      =>  null,
            CURLOPT_USERPWD         =>  null,
        ));

        // prior to PHP 5.3, func_get_args() cannot be used as a function parameter
        // so we need this intermediary step
        $arguments = func_get_args();

        // process requests, all at once or with pause between batches of URLs
        call_user_func_array(array($this, $this->pause_interval > 0 ? '_process_paused' : '_process'), $arguments);

    }

    public function http_authentication($username, $password, $type = CURLAUTH_ANY)
    {

        // set the required options
        $this->option(array(
            CURLOPT_HTTPAUTH    =>  $type,
            CURLOPT_USERPWD     =>  $username . ':' . $password,
        ));

    }
    public function option($option, $value = '')
    {

        // if $options is given as an array
        if (is_array($option))

            // iterate through each of the values
            foreach ($option as $name => $value)

                // if we need to "unset" an option, unset it
                if (is_null($value)) unset($this->options[$name]);

                // set the value for the option otherwise
                else $this->options[$name] = $value;

        // if option is not given as an array,
        // if we need to "unset" an option, unset it
        elseif (is_null($value)) unset($this->options[$option]);

        // set the value for the option otherwise
        else $this->options[$option] = $value;

    }

    public function post($url, $values, $callback = '')
    {

        // if second argument is not an array, trigger an error
        if (!is_array($values)) trigger_error('Second argument to method "post" must be an array!', E_USER_ERROR);

        // prepare cURL for making a POST
        $this->option(array(
            CURLINFO_HEADER_OUT     =>  1,
            CURLOPT_HEADER          =>  1,
            CURLOPT_NOBODY          =>  0,
            CURLOPT_POST            =>  1,
            CURLOPT_POSTFIELDS      =>  http_build_query($values, NULL, '&'),
            CURLOPT_BINARYTRANSFER  =>  null,
            CURLOPT_HTTPGET         =>  null,
            CURLOPT_FILE            =>  null,
            CURLOPT_USERPWD         =>  null,
        ));

        // prior to PHP 5.3, func_get_args() cannot be used as a function parameter
        // so we need this intermediary step
        $arguments = func_get_args();

        // remove the $_POST values from the arguments
        unset($arguments[1]);

        // process requests, all at once or with pause between batches of URLs
        call_user_func_array(array($this, $this->pause_interval > 0 ? '_process_paused' : '_process'), $arguments);

    }
    public function proxy($proxy, $port = 80, $username = '', $password = '')
    {

        // if not disabled
        if ($proxy) {

            // set the required options
            $this->option(array(
                CURLOPT_HTTPPROXYTUNNEL     =>  1,
                CURLOPT_PROXY               =>  $proxy,
                CURLOPT_PROXYPORT           =>  $port,
            ));

            // if a username is also specified
            if ($username != '')

                // set authentication values
                $this->option(CURLOPT_PROXYUSERPWD, $username . ':' . $password);

            // if disabled
        } else

            // unset proxy-related options
            $this->option(array(
                CURLOPT_HTTPPROXYTUNNEL     =>  null,
                CURLOPT_PROXY               =>  null,
                CURLOPT_PROXYPORT           =>  null,
            ));

    }

    public function ssl($verify_peer = false, $verify_host = 2, $file = false, $path = false)
    {

        // set default options
        $this->option(array(
            CURLOPT_SSL_VERIFYPEER => $verify_peer,
            CURLOPT_SSL_VERIFYHOST => $verify_host,
        ));

        // if a path to a file holding one or more certificates to verify the peer with was given
        if ($file !== false)

            // if file could be found, use it
            if (is_file($file)) $this->option(CURLOPT_CAINFO, $file);

            // if file was not found, trigger an error
            else trigger_error('File "' . $file . '", holding one or more certificates to verify the peer with, was not found!', E_USER_ERROR);

        // if a directory holding multiple CA certificates was given
        if ($path !== false)

            // if folder could be found, use it
            if (is_dir($path)) $this->option(CURLOPT_CAPATH, $path);

            // if folder was not found, trigger an error
            else trigger_error('Directory "' . $path . '", holding one or more CA certificates to verify the peer with, was not found!', E_USER_ERROR);

    }

    /**
     *  Returns the set options in "human-readable" format.
     *
     *  @return string  Returns the set options in "human-readable" format.
     *
     *  @access private
     */
    private function _debug()
    {

        $result = '';

        // iterate through the defined constants
        foreach(get_defined_constants() as $name => $number)

            // iterate through the set options
            foreach ($this->options as $index => $value)

                // if this is a curl-related constant and it is one of the options that are set, add it to the result
                if (substr($name, 0, 7) == 'CURLOPT' && $number == $index) $result .= $name . ' => ' . $value . '<br>';

        // return the result
        return $result;

    }
    private function _parse_headers($headers)
    {

        $result = array();

        // if we have nothing to work with
        if ($headers != '') {

            // split multiple headers by blank lines
            $headers = preg_split('/^\s*$/m', trim($headers));

            // iterate through the headers
            foreach($headers as $index => $header) {

                $arguments_count = func_num_args();

                // get all the lines in the header
                // lines in headers look like [name] : [value]
                // also, the first line, the status, does not have a name, so we add the name now
                preg_match_all('/^(.*?)\:\s(.*)$/m', ($arguments_count == 2 ? 'Request Method: ' : 'Status: ') . trim($header), $matches);

                // save results
                foreach ($matches[0] as $key => $value)

                    $result[$index][$matches[1][$key]] = trim($matches[2][$key]);

            }

        }

        // return headers as an array
        return $result;

    }
    private function _process($urls, $callback = '')
    {

        // if caching is enabled but path doesn't exist or is not writable
        if ($this->cache !== false && (!is_dir($this->cache['path']) || !is_writable($this->cache['path'])))

            // trigger an error and stop execution
            trigger_error('Cache path does not exists or is not writable!', E_USER_ERROR);

        // if callback function doesn't exists
        if ($callback != '' && !is_callable($callback))

            // trigger an error and stop execution
            trigger_error('Callback function "' . $callback . '" does not exist!', E_USER_ERROR);

        $urls = !is_array($urls) ? (array)$urls : $urls;

        // if
        if (

            // caching is enabled
            $this->cache !== false &&

            // we're making a GET request (including calls to header() method)
            ((isset($this->options[CURLOPT_HTTPGET]) && $this->options[CURLOPT_HTTPGET] == 1) ||

                // or we're making a POST request
                (isset($this->options[CURLOPT_POST]) && $this->options[CURLOPT_POST] == 1))

        ) {

            // iterate through the URLs
            foreach ($urls as $url) {

                // get the path to the cache file associated with the URL
                $cache_path = rtrim($this->cache['path'], '/') . '/' . md5($url . (isset($this->options[CURLOPT_POSTFIELDS]) ? serialize($this->options[CURLOPT_POSTFIELDS]) : ''));

                // if cache file exists and is not expired
                if (file_exists($cache_path) && filemtime($cache_path) + $this->cache['lifetime'] > time()) {

                    // if we have a callback
                    if ($callback != '') {

                        // the arguments passed to the "_process" method
                        $arguments = func_get_args();

                        // prepare the arguments to pass to the callback function
                        $arguments = array_merge(

                        // made of the result from the cache file...
                            array(unserialize($this->cache['compress'] ? gzuncompress(file_get_contents($cache_path)) : file_get_contents($cache_path))),

                            // ...and any additional arguments (minus the first 2)
                            array_slice($arguments, 2)

                        );

                        // feed them as arguments to the callback function
                        call_user_func_array($callback, $arguments);

                    }

                    // if no cache file, or cache file is expired
                } else $this->_queue[] = $url;

            }

            // if we're not making a GET request or caching is disabled, we don't bother with cache: we need to process all the URLs
        } else $this->_queue = $urls;

        // if there are any URLs to process
        if (!empty($this->_queue)) {

            // initialize the multi handle
            // this will allow us to process multiple cURL handles in parallel
            $this->_multi_handle = curl_multi_init();

            // queue the first batch of URLs
            // (as many as defined by the "threads" property or less if there aren't as many URLs)
            $this->_queue_requests();

            $running = null;

            // loop
            do {

                // get status update
                while (($status = curl_multi_exec($this->_multi_handle, $running)) == CURLM_CALL_MULTI_PERFORM);

                // if no request has finished yet, keep looping
                if ($status != CURLM_OK) break;

                // if a request was just completed, we'll have to find out which one
                while ($info = curl_multi_info_read($this->_multi_handle)) {

                    // get handle of the completed request
                    $handle = $info['handle'];

                    // get content associated with the handle
                    $content = curl_multi_getcontent($handle);

                    // get the handle's ID
                    $resource_number = preg_replace('/Resource id #/', '', $handle);

                    // create a new object in which we will store all the data associated with the handle,
                    // as properties of this object
                    $result = new stdClass();

                    // get information about the request
                    $result->info = curl_getinfo($handle);

                    // extend the "info" property with the original URL
                    $result->info = array('original_url' => $this->_info['fh' . $resource_number]['original_url']) + $result->info;

                    // last request headers
                    $result->headers['last_request'] =

                        (

                            // if CURLINFO_HEADER_OUT is set
                            isset($this->options[CURLINFO_HEADER_OUT]) &&

                            // if CURLINFO_HEADER_OUT is TRUE
                            $this->options[CURLINFO_HEADER_OUT] == 1 &&

                            // if we actually have this information
                            isset($result->info['request_header'])

                            // extract request headers
                        ) ? $this->_parse_headers($result->info['request_header'], true) : '';

                    // remove request headers information from its previous location
                    unset($result->info['request_header']);

                    // get headers (unless we were explicitly told not to)
                    $result->headers['responses'] = (isset($this->options[CURLOPT_HEADER]) && $this->options[CURLOPT_HEADER] == 1) ?

                        $this->_parse_headers(substr($content, 0, $result->info['header_size'])) :

                        '';

                    // get output (unless we were explicitly told not to)
                    $result->body = !isset($this->options[CURLOPT_NOBODY]) || $this->options[CURLOPT_NOBODY] == 0 ?

                        (isset($this->options[CURLOPT_HEADER]) && $this->options[CURLOPT_HEADER] == 1 ?

                            substr($content, $result->info['header_size']) :

                            $content) :

                        '';

                    // if we have a body, we're not doing a binary transfer, and _htmlentities is set to TRUE, run htmlentities() on it
                    if ($result->body != '' && !isset($this->options[CURLOPT_BINARYTRANSFER]) && $this->_htmlentities) {

                        // since PHP 5.3.0, htmlentities will return an empty string if the input string contains an
                        // invalid code unit sequence within the given encoding (utf-8 in our case)
                        // so take care of that
                        if (defined(ENT_IGNORE)) $result->body = htmlentities($result->body, ENT_IGNORE, 'utf-8');

                        // for PHP versions lower than 5.3.0
                        else htmlentities($result->body);

                    }

                    // get CURLs response code and associated message
                    $result->response = array($this->_response_messages[$info['result']], $info['result']);

                    // if we have a callback
                    if ($callback != '') {

                        // the arguments passed to the "_process" method
                        $arguments = func_get_args();

                        // prepare the arguments to pass to the callback function
                        $arguments = array_merge(

                        // made of the "result" object...
                            array($result),

                            // ...and any additional arguments (minus the first 2)
                            array_slice($arguments, 2)

                        );

                        // feed them as arguments to the callback function
                        // and save the callback's response, if any
                        $callback_response = call_user_func_array($callback, $arguments);

                        // if no callback function, we assume the response is TRUE
                    } else $callback_response = true;

                    // if
                    if (

                        // caching is enabled
                        $this->cache !== false &&

                        // the callback function did not return FALSE
                        $callback_response !== false &&

                        // we're making a GET request (including calls to header() method)
                        ((isset($this->options[CURLOPT_HTTPGET]) && $this->options[CURLOPT_HTTPGET] == 1) ||

                            // or we're making a POST request
                            (isset($this->options[CURLOPT_POST]) && $this->options[CURLOPT_POST] == 1))

                    ) {

                        // get the path to the cache file associated with the URL
                        $cache_path = rtrim($this->cache['path'], '/') . '/' . md5($result->info['original_url'] . (isset($this->options[CURLOPT_POSTFIELDS]) ? serialize($this->options[CURLOPT_POSTFIELDS]) : ''));

                        // cache the result
                        file_put_contents($cache_path, $this->cache['compress'] ? gzcompress(serialize($result)) : serialize($result));

                        // set rights on the file
                        chmod($cache_path, intval($this->cache['chmod'], 8));

                    }

                    // if there are more URLs to process, queue the next one
                    if (!empty($this->_queue)) $this->_queue_requests();

                    // remove the handle that we finished processing
                    // this needs to be done *after* we've already queued a new URL for processing
                    curl_multi_remove_handle($this->_multi_handle, $handle);

                    // make sure the handle gets closed
                    curl_close($handle);

                    // if we're downloading something
                    if (isset($this->options[CURLOPT_BINARYTRANSFER]) && $this->options[CURLOPT_BINARYTRANSFER])

                        // close the associated file pointer
                        fclose($this->_info['fh' . $resource_number]['file_handler']);

                    // remove information associated with this resource
                    unset($this->_info['fh' . $resource_number]);

                }

                // waits until curl_multi_exec() returns CURLM_CALL_MULTI_PERFORM or until the timeout, whatever happens first
                // call usleep() if a select returns -1 - workaround for PHP bug: https://bugs.php.net/bug.php?id=61141
                if ($running && curl_multi_select($this->_multi_handle) === -1) usleep(100);

                // as long as there are threads running
            } while ($running);

            // close the multi curl handle
            curl_multi_close($this->_multi_handle);

        }

    }

    /**
     *  A wrapper for the _process method used when we need to pause between batches of URLs to process.
     *
     *  @return null
     *
     *  @access private
     */
    private function _process_paused($urls, $callback = '') {

        // while there are URLs to process
        while (!empty($urls)) {

            // get from the entire list of URLs as many as specified by the "threads" property
            $urls_to_process = array_splice($urls, 0, $this->threads, array());

            // process those URLs
            $this->_process($urls_to_process, $callback);

            // wait for as many seconds as specified by the "pause_interval" property
            sleep($this->pause_interval);

        }

    }

    private function _queue_requests()
    {

        // get the length of the queue
        $queue_length = count($this->_queue);

        // iterate through the items in the queue
        for ($i = 0; $i < ($queue_length < $this->threads ? $queue_length : $this->threads); $i++) {

            // remove first URL from the queue
            $url = array_shift($this->_queue);

            // initialize individual cURL handle with the URL
            $handle = curl_init($url);

            // make sure defaults are set
            $this->_set_defaults();

            // get the handle's ID
            $resource_number = preg_replace('/Resource id #/', '', $handle);

            // save the original URL
            // (because there may be redirects, and because "curl_getinfo" returns information only about the last
            // request, this can be lost otherwise)
            $this->_info['fh' . $resource_number]['original_url'] = $url;

            // if we're downloading something
            if (isset($this->options[CURLOPT_BINARYTRANSFER]) && $this->options[CURLOPT_BINARYTRANSFER]) {

                // open a file and save the file pointer
                $this->_info['fh' . $resource_number]['file_handler'] = fopen($this->download_path . basename($url), 'w');

                // no headers
                $this->option(CURLOPT_HEADER, 0);

                // tell cURL to use the file for streaming the download
                $this->option(CURLOPT_FILE, $this->_info['fh' . $resource_number]['file_handler']);

            }

            // set options for the handle
            curl_setopt_array($handle, $this->options);

            // add the normal handle to the multi handle
            curl_multi_add_handle($this->_multi_handle, $handle);

        }

    }


    private function _set_defaults()
    {
        if (!isset($this->options[CURLINFO_HEADER_OUT])) $this->option(CURLINFO_HEADER_OUT, 1);
        if (!isset($this->options[CURLOPT_AUTOREFERER])) $this->option(CURLOPT_AUTOREFERER, 1);
        if (!isset($this->options[CURLOPT_COOKIEFILE])) $this->option(CURLOPT_COOKIEFILE, '');
        if (!isset($this->options[CURLOPT_CONNECTTIMEOUT])) $this->option(CURLOPT_CONNECTTIMEOUT, 10);
        if (!isset($this->options[CURLOPT_ENCODING])) $this->option(CURLOPT_ENCODING, 'gzip,deflate');
        if (!isset($this->options[CURLOPT_FOLLOWLOCATION])) $this->option(CURLOPT_FOLLOWLOCATION, 1);
        if (!isset($this->options[CURLOPT_HEADER])) $this->option(CURLOPT_HEADER, 1);
        if (!isset($this->options[CURLOPT_MAXREDIRS])) $this->option(CURLOPT_MAXREDIRS, 50);
        if (!isset($this->options[CURLOPT_TIMEOUT])) $this->option(CURLOPT_TIMEOUT, 30);
        if (!isset($this->options[CURLOPT_USERAGENT])) $this->option(CURLOPT_USERAGENT, $this->_user_agent());
        $this->option(CURLOPT_RETURNTRANSFER, 1);

    }
    private function _user_agent()
    {

        $version = rand(9, 10);
        $major_version = 6;
        $minor_version =

            $version == 8 || $version == 9 ? rand(0, 2) :

                // for IE10 Windows will have "2" as major version number
                2;

        // add some extra information
        $extras = rand(0, 3);

        // return the random user agent string
        return 'Mozilla/5.0 (compatible; MSIE ' . $version . '.0; Windows NT ' . $major_version . '.' . $minor_version . ($extras == 1 ? '; WOW64' : ($extras == 2 ? '; Win64; IA64' : ($extras == 3 ? '; Win64; x64' : ''))) . ')';

    }


}

?>