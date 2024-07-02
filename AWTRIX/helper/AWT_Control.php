<?php

/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection SpellCheckingInspection */

declare(strict_types=1);

trait AWT_Control
{
    ########## Public

    public function UpdateStats(): void
    {
        $stats = json_decode($this->GetStats(), true);

        /* Dummy data
        $data = '{"bat":100,"bat_raw":682,"type":0,"lux":0,"ldr_raw":87,"ram":137924,"bri":120,"temp":31,"hum":26,"uptime":41866,"wifi_signal":-59,"messages":0,"version":"0.96","indicator1":false,"indicator2":false,"indicator3":false,"app":"Humidity","uid":"awtrix_a123456","matrix":true,"ip_address":"192.168.1.234"}';
        $stats = json_decode($data, true);
         */

        //Power: "matrix":true
        if (array_key_exists('matrix', $stats)) {
            $this->SetValue('Power', $stats['matrix']);
        }
        //Brightness: "bri":120
        if (array_key_exists('bri', $stats)) {
            $this->SetValue('Brightness', $stats['bri']);
        }
        //Battery: "bat":100
        if (array_key_exists('bat', $stats)) {
            $this->SetValue('Battery', $stats['bat']);
        }
        $this->SetTimerInterval('UpdateStats', $this->ReadPropertyInteger('StatusInterval') * 1000);
    }

    ########## Private

    private function SetAutomaticRebootTimer(): void
    {
        $milliseconds = 0;
        if ($this->ReadPropertyBoolean('UseAutomaticReboot')) {
            $milliseconds = $this->GetInterval('RebootTime');
        }
        $this->SetTimerInterval('AutomaticReboot', $milliseconds);
    }

    private function GetInterval(string $TimerPropertyName): int
    {
        $timer = json_decode($this->ReadPropertyString($TimerPropertyName));
        $now = time();
        $hour = $timer->hour;
        $minute = $timer->minute;
        $second = $timer->second;
        $definedTime = $hour . ':' . $minute . ':' . $second;
        if (time() >= strtotime($definedTime)) {
            $timestamp = mktime($hour, $minute, $second, (int) date('n'), (int) date('j') + 1, (int) date('Y'));
        } else {
            $timestamp = mktime($hour, $minute, $second, (int) date('n'), (int) date('j'), (int) date('Y'));
        }
        return ($timestamp - $now) * 1000;
    }
}