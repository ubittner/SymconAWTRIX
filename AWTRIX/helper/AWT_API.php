<?php

/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection SpellCheckingInspection */

declare(strict_types=1);

trait AWT_API
{
    ########## Public

    ##### Device

    public function PowerDevice(bool $State): string
    {
        $power = 'false';
        if ($State) {
            $power = 'true';
        }
        return $this->SendDataToDevice('power', 'POST', '{"power": ' . $power . '}');
    }

    public function SetBrightness(int $Brightness): string
    {
        return $this->SendDataToDevice('moodlight', 'POST', '{"brightness": ' . $Brightness . '}');
    }

    public function GetStats(): string
    {
        return $this->SendDataToDevice('stats', 'GET', '');
    }

    public function GetLiveView(): string
    {
        //Retrieve the current matrix screen as an array of 24bit colors.
        return $this->SendDataToDevice('screen', 'GET', '');
    }

    public function RebootDevice(): void
    {
        $this->SendDataToDevice('reboot', 'POST', '');
        $this->SetAutomaticRebootTimer();
    }

    ########## Custom Apps

    public function GetAppsInLoop(): string
    {
        return $this->SendDataToDevice('loop', 'GET', '');
    }

    public function AddCustomApp(string $AppName, string $payload): void
    {
        $this->SendDataToDevice('custom?name=' . $AppName, 'POST', $payload);
    }

    public function DeleteCustomApp(string $CustomAppName): void
    {
        $this->SendDataToDevice('custom?name=' . $CustomAppName, 'POST', '');
    }

    ##### Notification

    public function SendNotification(string $payload): void
    {
        $this->SendDataToDevice('notify', 'POST', $payload);
    }

    ########## Private

    private function SendDataToDevice(string $Endpoint, string $CustomRequest, string $Postfields): string
    {
        $this->SendDebug(__FUNCTION__, 'Endpoint: ' . $Endpoint, 0);
        $this->SendDebug(__FUNCTION__, 'CustomRequest: ' . $CustomRequest, 0);
        $this->SendDebug(__FUNCTION__, 'Postfields: ' . $Postfields, 0);
        $deviceIP = $this->ReadPropertyString('DeviceIP');
        if ($deviceIP == '') {
            return '{}';
        }
        //Check credentials
        $userName = $this->ReadPropertyString('UserName');
        $password = $this->ReadPropertyString('Password');
        $auth = false;
        if ($userName != '' && $password != '') {
            $auth = true;
        }
        $header = '';
        if ($auth) {
            $header = [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode("$userName:$password")
            ];
        }
        $url = 'http://' . $deviceIP . '/api/' . $Endpoint;
        //Send data
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST     => $CustomRequest,
            CURLOPT_URL               => $url,
            CURLOPT_HEADER            => true,
            CURLOPT_RETURNTRANSFER    => true,
            CURLOPT_FAILONERROR       => true,
            CURLOPT_ENCODING          => '',
            CURLOPT_MAXREDIRS         => 10,
            CURLOPT_FOLLOWLOCATION    => true,
            CURLOPT_HTTP_VERSION      => CURL_HTTP_VERSION_1_1,
            CURLOPT_CONNECTTIMEOUT_MS => $this->ReadPropertyInteger('Timeout'),
            CURLOPT_TIMEOUT           => 60,
            CURLOPT_POSTFIELDS        => $Postfields,
            CURLOPT_HTTPHEADER        => $header]);
        $response = curl_exec($ch);
        $body = '{}';
        if (!curl_errno($ch)) {
            switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                case 200:  # OK
                    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                    $header = substr($response, 0, $header_size);
                    $body = substr($response, $header_size);
                    $this->SendDebug(__FUNCTION__, 'Header: ' . $header, 0);
                    $this->SendDebug(__FUNCTION__, 'Body: ' . $body, 0);
                    break;

                default:
                    $this->SendDebug(__FUNCTION__, 'HTTP Code: ' . $http_code, 0);
            }
        } else {
            $error_msg = curl_error($ch);
            $this->SendDebug(__FUNCTION__, 'An error has occurred: ' . json_encode($error_msg), 0);
        }
        curl_close($ch);
        return $body;
    }
}