<?php


namespace cnp\sdk;
class CnpRequest
{
    # file name that holds the batch requests once added
    public $batches_file;

    public $request_file;

    public $response_file;

    private $config;

    public $num_batch_requests = 0;
    # note that a single cnp request cannot hold more than 500,000 transactions
    public $total_transactions = 0;

    public $closed = false;
    /*
     * Creates the intermediate request file and preps it to have batches added
     */
    public function __construct($overrides=array())
    {
        $config = Obj2xml::getConfig($overrides);

        $this->config= $config;
        $request_dir = $config['cnp_requests_path'];

        if (!is_dir($request_dir)) {
            mkdir($request_dir);
        }

        if (mb_substr($request_dir, -1, 1) != DIRECTORY_SEPARATOR) {
            $request_dir = $request_dir . DIRECTORY_SEPARATOR;
        }

        $ts = str_replace(" ", "", mb_substr(microtime(), 2));
        $batches_filename = $request_dir . "request_" . $ts . "_batches";
        $request_filename = $request_dir . "request_" . $ts;
        $response_filename = $request_dir . "response_" . $ts;
        // if either file already exists, let's try again!
        if (file_exists($batches_filename) || file_exists($request_filename) || file_exists($response_filename)) {
            $this->__construct();
        }

        // if we were unable to write the file
        if (file_put_contents($batches_filename, "") === FALSE) {
            throw new \RuntimeException("A request file could not be written at $batches_filename. Please check your privilege.");
        }
        $this->batches_file = $batches_filename;

        // if we were unable to write the file
        if (file_put_contents($request_filename, "") === FALSE) {
            throw new \RuntimeException("A request file could not be written at $request_filename. Please check your privilege.");
        }
        $this->request_file = $request_filename;

        if (file_put_contents($response_filename, "") === FALSE) {
            throw new \RuntimeException("A response file could not be written at $response_filename. Please check your privilege.");
        }
        $this->response_file = $response_filename;
    }

    public function wouldFill($addl_txns_count)
    {
        return ($this->total_transactions + $addl_txns_count) > MAX_TXNS_PER_REQUEST;
    }

    /*
     * Adds a closed batch request to the Cnp Request. This entails copying the completed batch file into the intermediary
     * request file
     */
    public function addBatchRequest($batch_request)
    {

        if ($this->wouldFill($batch_request->total_txns)) {
            throw new \RuntimeException("Couldn't add the batch to the Cnp Request. The total number of transactions would exceed the maximum allowed for a request.");
        }

        if ($this->closed) {
            throw new \RuntimeException("Could not add the batchRequest. This cnpRequest is closed.");
        }

        if (!$batch_request->closed) {
            $batch_request->closeRequest();
        }
        $handle = @fopen($batch_request->batch_file,"r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                file_put_contents($this->batches_file, $buffer, FILE_APPEND);
            }
            if (!feof($handle)) {
                throw new \RuntimeException("Error when reading batch file at $batch_request->batch_file. Please check your privilege.");
            }
            fclose($handle);

            unlink($batch_request->batch_file);
            unset($batch_request->batch_file);
            $this->num_batch_requests += 1;
            $this->total_transactions += $batch_request->total_txns;
        } else {
            throw new \RuntimeException("Could not open batch file at $batch_request->batch_file. Please check your privilege.");
        }
    }

    public function createRFRRequest($hash_in)
    {
        if ($this->num_batch_requests > 0) {
            throw new \RuntimeException("Could not add the RFR Request. A single Cnp Request cannot have both an RFR request and batch requests together.");
        }

        if ($this->closed) {
            throw new \RuntimeException("Could not add the RFR Request. This cnpRequest is closed.");
        }
        $RFRXml = Obj2xml::rfrRequestToXml($hash_in);
        file_put_contents($this->request_file, Obj2xml::generateRequestHeader($this->config, $this->num_batch_requests), FILE_APPEND);
        file_put_contents($this->request_file, $RFRXml, FILE_APPEND);
        file_put_contents($this->request_file, "</cnpRequest>", FILE_APPEND);
        unlink($this->batches_file);
        unset($this->batches_file);
        $this->closed = true;
    }
    /*
     * Fleshes out the XML needed for the Cnp Request. Returns the file name of the completed request file
     */
    public function closeRequest()
    {
        $handle = @fopen($this->batches_file,"r");
        if ($handle) {
            file_put_contents($this->request_file, Obj2xml::generateRequestHeader($this->config, $this->num_batch_requests), FILE_APPEND);
            while (($buffer = fgets($handle, 4096)) !== false) {
                file_put_contents($this->request_file, $buffer, FILE_APPEND);
            }
            if (!feof($handle)) {
                throw new \RuntimeException("Error when reading batches file at $this->batches_file. Please check your privilege.");
            }
            fclose($handle);
            file_put_contents($this->request_file, "</cnpRequest>", FILE_APPEND);



            unlink($this->batches_file);
            unset($this->batches_file);
            $this->closed = true;
            if (isset($this->config['print_xml']) and $this->config['print_xml']){
                $handle = @fopen($this->request_file,"r");
                while (($buffer = fgets($handle, 4096)) !== false) {
                }
                fclose($handle);
            }
        } else {
            throw new \RuntimeException("Could not open batches file at $this->batches_file. Please check your privilege.");
        }
    }

    /*
     * Alias for the preferred method of sFTP delivery
     */
    public function sendToCnp()
    {
        $this->sendToCnpSFTP();

        return $this->response_file;
    }

    /*
     * Deliver the Cnp Request over sFTP using the credentials given by the config. Returns the name of the file retrieved from the server
     */
    public function sendToCnpSFTP()
    {
        if (!$this->closed) {
            $this->closeRequest();
        }

        $requestFilename = $this->request_file;
        $useEncryption = $this->config['useEncryption'];
        if($useEncryption){
            $publicKey = $this->config['vantivPublicKeyID'];
            $requestFilename = $this->request_file . ".encrypted";
            PgpHelper::encrypt($this->request_file, $requestFilename, $publicKey);
        }

        $session = $this->createSFTPSession();
        # with extension .prg
        $session->put('/inbound/' . basename($this->request_file) . '.prg', $requestFilename, \phpseclib\Net\SFTP::SOURCE_LOCAL_FILE);
        # rename when the file upload is complete
        $session->rename('/inbound/' . basename($this->request_file) . '.prg', '/inbound/' . basename($this->request_file) . '.asc');

        $deleteBatchFiles = $this->config['deleteBatchFiles'];
        CnpResponseProcessor::$deleteBatchFiles = $deleteBatchFiles;
        if($deleteBatchFiles){
            if(file_exists($this->request_file)){
                unlink($this->request_file);
            }
            if(file_exists($requestFilename)){
                unlink($requestFilename);
            }
        }

        $this->retrieveFromCnpSFTP($session);
    }

    /*
     * Given a timeout (defaults to 7200 seconds - two hours), periodically poll the SFTP directory, looking for the response file for this request.
     */
    public function retrieveFromCnpSFTP($session)
    {
        $sftp_timeout = (float) $this->config['sftp_timeout'];
        $time_spent = 0;
        $this->resetSFTPSession($session);
        while ($time_spent < $sftp_timeout) {
            # we'll get booted off periodically; make this a non-issue by periodically reconnecting
            if ($time_spent % 180 == 0) {
                $this->resetSFTPSession($session);
            }

            $files = $session->nlist('/outbound');

            if (in_array(basename($this->request_file) . '.asc', $files)) {
                $this->downloadFromCnpSFTP($session,$time_spent, $sftp_timeout);
                $useEncryption = $this->config['useEncryption'];
                if($useEncryption){
                    $passphrase = $this->config['gpgPassphrase'];
                    rename($this->response_file, $this->response_file.".encrypted");
                    PgpHelper::decrypt($this->response_file.".encrypted", $this->response_file, $passphrase);
                }
                return;
            }

            $time_spent += 20;
            sleep(20);
        }

        throw new \Exception("Response file can not be retrieved because of timeout (Duration : " . $sftp_timeout . " seconds)");

    }

    /*
     * Creates SFTP Session with given login credentials
     */
    public function createSFTPSession()
    {
        $sftp_url = $this->config['batch_url'];
        $sftp_username = $this->config['sftp_username'];
        $sftp_password = $this->config['sftp_password'];
        $session = new \phpseclib\Net\SFTP($sftp_url);
        if (!$session->login($sftp_username, $sftp_password)) {
            throw new \RuntimeException("Failed to SFTP with the username $sftp_username and the password $sftp_password to the host $sftp_url. Check your credentials!");
        }

        return $session;
    }

    /*
     * Resets SFTP Session if Session is unseeted or timed out
     */
     public function resetSFTPSession($session)
     {
        if (!isset($session)) {
            $session = $this->createSFTPSession();
        }
     }

    /*
     * Downloads the response file from the SFTP server to local system iteratively
     */
    public function downloadFromCnpSFTP($session, $time_spent, $sftp_timeout)
    {
        $sftp_remote_file = '/outbound/' . basename($this->request_file) . '.asc';
        $this->resetSFTPSession($session);
        while ($time_spent < $sftp_timeout) {
            try {
                if ($time_spent % 180 == 0) {
                    $this->resetSFTPSession($session);
                }

                // response files are initially created with permissions that prevent download. These permissions are
                // updated to allow download within a few minutes, so retry the download until it actually succeeds.
                if (!$session->get($sftp_remote_file, $this->response_file)) {
                    $time_spent += 20;
                    sleep(20);
                }

                $session->delete($sftp_remote_file);
                $this->response_file = str_replace("request", "response", $this->response_file);
                unset ($session);

                if (isset($this->config['print_xml']) and $this->config['print_xml']){
                    $handle = @fopen($this->response_file,"r");
                    while (($buffer = fgets($handle, 4096)) !== false) {
                    }
                    fclose($handle);
                }
                return;
            } catch (\Exception $exception) {
                $message = $exception->getMessage();
                if (stristr($message, "errno=32 broken pipe")) {
                    $time_spent += 20;
                    sleep(20);
                } else {
                    throw new \Exception($message);
                }
            }
        }
    }
}
