<?php

namespace Botble\Base\Supports;

use Illuminate\Support\Arr;

class Core
{

    /**
     * @var string
     */
    protected $productId;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $apiLanguage;

    /**
     * @var string
     */
    protected $verifyType;

    /**
     * @var int
     */
    protected $verificationPeriod;

    /**
     * @var string
     */
    protected $licenseFile;

    /**
     * @var string
     */
    protected $sessionKey = '44622179e10cab6';

    /**
     * Core constructor.
     */
    public function __construct()
    {
        $this->apiUrl = 'https://license.botble.com/';
        $this->apiKey = 'CAF4B17F6D3F656125F9';
        $this->apiLanguage = 'english';
        $this->verificationPeriod = 1;
        $this->licenseFile = storage_path('.license');

        $core = get_file_data(core_path('core.json'));

        if ($core) {
            $this->productId = Arr::get($core, 'productId');
            $this->verifyType = Arr::get($core, 'source');
        }
    }

    /**
     * @return string
     */
    public function getLicenseFilePath()
    {
        return $this->licenseFile;
    }

    /**
     * @param string $license
     * @param string $client
     * @param bool $createLicense
     * @return array
     */
    public function activateLicense($license, $client, $createLicense = true)
    {
        $dataArray = [
            'product_id'   => $this->productId,
            'license_code' => $license,
            'client_name'  => $client,
            'verify_type'  => $this->verifyType,
        ];

        $getData = $this->callApi(
            'POST',
            $this->apiUrl . 'api/activate_license',
            json_encode($dataArray)
        );

        $response = json_decode($getData, true);

        if (!empty($createLicense)) {
            if ($response['status']) {
                $license = trim($response['lic_response']);
                file_put_contents($this->licenseFile, $license, LOCK_EX);
            } else {
                @chmod($this->licenseFile, 0777);
                if (is_writeable($this->licenseFile)) {
                    unlink($this->licenseFile);
                }
            }
        }

        return $response;
    }

    /**
     * @param string $method
     * @param string $url
     * @param string $data
     * @return bool|false|string
     */
    protected function callApi(string $method, string $url, ?string $data)
    {
        $curl = curl_init();
        switch ($method) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            default:
                if ($data) {
                    $url = sprintf('%s?%s', $url, http_build_query($data));
                }
        }

        $thisServerSame = request()->server('SERVER_NAME') ?: request()->server('HTTP_HOST');

        $thisHttpOrHttps = request()->server('HTTPS') == 'on' || request()->server('HTTP_X_FORWARDED_PROTO') == 'https'
            ? 'https://' : 'http://';

        $thisUrl = $thisHttpOrHttps . $thisServerSame . request()->server('REQUEST_URI');
        $thisIp = request()->server('SERVER_ADDR') ?: $this->getIpFromThirdParty() ?: gethostbyname(gethostname());

        curl_setopt($curl, CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                'LB-API-KEY: ' . $this->apiKey,
                'LB-URL: ' . $thisUrl,
                'LB-IP: ' . $thisIp,
                'LB-LANG: ' . $this->apiLanguage,
            ]
        );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($curl);
        if (!$result && config('app.debug')) {
            $rs = [
                'status'  => false,
                'message' => 'Server is unavailable at the moment, please try again.',
            ];
            return json_encode($rs);
        }
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpStatus != 200) {
            if (config('app.debug')) {
                $tempDecode = json_decode($result, true);
                $rs = [
                    'status'  => false,
                    'message' => !empty($tempDecode['error']) ? $tempDecode['error'] : $tempDecode['message'],
                ];
                return json_encode($rs);
            }
            $rs = [
                'status'  => false,
                'message' => 'Server returned an invalid response, please contact support.',
            ];
            return json_encode($rs);
        }
        curl_close($curl);

        return $result;
    }

    /**
     * @return bool|string
     */
    protected function getIpFromThirdParty()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://ipecho.net/plain');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    /**
     * @param bool $timeBasedCheck
     * @param bool $license
     * @param bool $client
     * @return array|mixed
     */
    public function verifyLicense($timeBasedCheck = false, $license = false, $client = false)
    {
        if (!empty($license) && !empty($client)) {
            $dataArray = [
                'product_id'   => $this->productId,
                'license_file' => null,
                'license_code' => $license,
                'client_name'  => $client,
            ];
        } elseif ($this->checkLocalLicenseExist()) {
            $dataArray = [
                'product_id'   => $this->productId,
                'license_file' => file_get_contents($this->licenseFile),
                'license_code' => null,
                'client_name'  => null,
            ];
        } else {
            $dataArray = [
                'product_id'   => $this->productId,
                'license_file' => null,
                'license_code' => null,
                'client_name'  => null,
            ];
        }
        $res = ['status' => true, 'message' => 'Verified! Thanks for purchasing our product.'];
        if ($timeBasedCheck && $this->verificationPeriod > 0) {
            $type = (int)$this->verificationPeriod;
            $today = date('d-m-Y');
            if (!session($this->sessionKey)) {
                session([$this->sessionKey => '00-00-0000']);
            }
            if ($type == 1) {
                $typeText = '1 day';
            } elseif ($type == 3) {
                $typeText = '3 days';
            } elseif ($type == 7) {
                $typeText = '1 week';
            } else {
                $typeText = $type . ' days';
            }

            if (strtotime($today) >= strtotime(session($this->sessionKey))) {
                $getData = $this->callApi(
                    'POST',
                    $this->apiUrl . 'api/verify_license',
                    json_encode($dataArray)
                );
                $res = json_decode($getData, true);
                if ($res['status'] == true) {
                    $tomorrow = date('d-m-Y', strtotime($today . ' + ' . $typeText));
                    session([$this->sessionKey => $tomorrow]);
                }
            }
        } else {
            $getData = $this->callApi(
                'POST',
                $this->apiUrl . 'api/verify_license',
                json_encode($dataArray)
            );
            $res = json_decode($getData, true);
        }

        return $res;
    }

    /**
     * @return bool
     */
    public function checkLocalLicenseExist()
    {
        return is_file($this->licenseFile);
    }

    /**
     * @param bool $license
     * @param bool $client
     * @return mixed
     */
    public function deactivateLicense($license = false, $client = false)
    {
        if (!empty($license) && !empty($client)) {
            $dataArray = [
                'product_id'   => $this->productId,
                'license_file' => null,
                'license_code' => $license,
                'client_name'  => $client,
            ];
        } elseif (is_file($this->licenseFile)) {
            $dataArray = [
                'product_id'   => $this->productId,
                'license_file' => file_get_contents($this->licenseFile),
                'license_code' => null,
                'client_name'  => null,
            ];
        } else {
            $dataArray = [];
        }

        $getData = $this->callApi(
            'POST',
            $this->apiUrl . 'api/deactivate_license',
            json_encode($dataArray)
        );

        $response = json_decode($getData, true);
        if ($response['status']) {
            session()->forget($this->sessionKey);
            @chmod($this->licenseFile, 0777);
            if (is_writeable($this->licenseFile)) {
                unlink($this->licenseFile);
            }
        }

        return $response;
    }
}
