<?php

/*
 * Copyright (c) 2011 Vantiv eCommerce Inc.
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */
namespace cnp\sdk;
define('CURRENT_XML_VERSION', '12.24');
define('CURRENT_SDK_VERSION', 'PHP;12.24.0');
define('MAX_TXNS_PER_BATCH', 100000);
define('MAX_TXNS_PER_REQUEST', 500000);
define('CNP_CONFIG_LIST', 'user,password,merchantId,timeout,proxy,reportGroup,version,url,cnp_requests_path,batch_requests_path,sftp_username,sftp_password,batch_url,tcp_port,tcp_ssl,tcp_timeout,print_xml,vantivPublicKeyID,gpgPassphrase,useEncryption,deleteBatchFiles,multiSite,multiSiteErrorThreshold,maxHoursWithoutSwitch,printMultiSiteDebug,multiSiteUrl1,multiSiteUrl2,sftp_timeout');

