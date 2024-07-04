<?php

/** @noinspection PhpRedundantMethodOverrideInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection SpellCheckingInspection */
/** @noinspection PhpUnused */

declare(strict_types=1);

include_once __DIR__ . '/helper/autoload.php';

class AWTRIX extends IPSModule
{
    //Helper
    use AWT_API;
    use AWT_BuiltInApps;
    use AWT_ConfigurationForm;
    use AWT_Control;
    use AWT_CustomApps;
    use AWT_Notifications;
    use AWT_Settings;

    //Constants
    private const LIBRARY_GUID = '{CE8941EB-E52B-A75B-098C-ABE5740A64E9}';
    private const MODULE_GUID = '{BD05743E-37B4-9761-C5E8-6FC2367A967B}';

    public function Create(): void
    {
        //Never delete this line!
        parent::Create();

        ########## Properties

        //Info
        $this->RegisterPropertyString('Note', '');

        //Device
        $this->RegisterPropertyString('DeviceIP', '');
        $this->RegisterPropertyString('UserName', '');
        $this->RegisterPropertyString('Password', 'awtrix');
        $this->RegisterPropertyInteger('Timeout', 5000);
        $this->RegisterPropertyInteger('StatusInterval', 300);
        $this->RegisterPropertyBoolean('UseAutomaticReboot', false);
        $this->RegisterPropertyString('RebootTime', '{"hour":1,"minute":0,"second":0}');

        //Settings
        $this->RegisterPropertyInteger('AppDisplayDuration', 7);
        $this->RegisterPropertyInteger('TimeAppStyle', 1);

        //Built-in Apps
        $this->RegisterPropertyBoolean('UseBuiltInAppTime', false);
        $this->RegisterPropertyBoolean('UseBuiltInAppDate', false);
        $this->RegisterPropertyBoolean('UseBuiltInAppTemperature', false);
        $this->RegisterPropertyBoolean('UseBuiltInAppHumidity', false);
        $this->RegisterPropertyBoolean('UseBuiltInAppBattery', false);

        //Custom apps
        $this->RegisterPropertyString('CustomApps', '[]');
        $this->RegisterPropertyInteger('CustomAppsUpdateInterval', 60);

        //Notification
        $this->RegisterPropertyString('Notifications', '[]');

        ########## Variables

        //Power
        $this->RegisterVariableBoolean('Power', 'Power', '~Switch', 10);
        $this->EnableAction('Power');

        //Brightness
        $this->RegisterVariableInteger('Brightness', 'Helligkeit', '~Intensity.255', 20);
        $this->EnableAction('Brightness');

        //Battery
        $this->RegisterVariableInteger('Battery', 'Batterie', '~Intensity.100', 30);

        ########## Timer
        $modulePrefix = IPS_GetModule(self::MODULE_GUID)['Prefix'];
        $this->RegisterTimer('UpdateStats', 0, $modulePrefix . '_UpdateStats(' . $this->InstanceID . ');');
        $this->RegisterTimer('AutomaticReboot', 0, $modulePrefix . '_RebootDevice(' . $this->InstanceID . ');');
        $this->RegisterTimer('UpdateCustomApps', 0, $modulePrefix . '_UpdateCustomApps(' . $this->InstanceID . ', true);');
    }

    public function Destroy(): void
    {
        //Never delete this line!
        parent::Destroy();
    }

    public function ApplyChanges(): void
    {
        //Wait until IP-Symcon is started
        $this->RegisterMessage(0, IPS_KERNELSTARTED);

        //Never delete this line!
        parent::ApplyChanges();

        //Check runlevel
        if (IPS_GetKernelRunlevel() != KR_READY) {
            return;
        }

        //Delete all references
        foreach ($this->GetReferenceList() as $referenceID) {
            $this->UnregisterReference($referenceID);
        }

        //Delete all update messages
        foreach ($this->GetMessageList() as $senderID => $messages) {
            foreach ($messages as $message) {
                if ($message == VM_UPDATE) {
                    $this->UnregisterMessage($senderID, VM_UPDATE);
                }
            }
        }

        $variables = json_decode($this->ReadPropertyString('Notifications'), true);
        foreach ($variables as $variable) {
            if (!$variable['Use']) {
                continue;
            }
            //Primary condition
            if ($variable['PrimaryCondition'] != '') {
                $primaryCondition = json_decode($variable['PrimaryCondition'], true);
                if (array_key_exists(0, $primaryCondition)) {
                    if (array_key_exists(0, $primaryCondition[0]['rules']['variable'])) {
                        $id = $primaryCondition[0]['rules']['variable'][0]['variableID'];
                        if ($id > 1 && @IPS_ObjectExists($id)) {
                            $this->RegisterReference($id);
                            $this->RegisterMessage($id, VM_UPDATE);
                        }
                    }
                }
            }
            //Secondary condition, multi
            if ($variable['SecondaryCondition'] != '') {
                $secondaryConditions = json_decode($variable['SecondaryCondition'], true);
                if (array_key_exists(0, $secondaryConditions)) {
                    if (array_key_exists('rules', $secondaryConditions[0])) {
                        $rules = $secondaryConditions[0]['rules']['variable'];
                        foreach ($rules as $rule) {
                            if (array_key_exists('variableID', $rule)) {
                                $id = $rule['variableID'];
                                if ($id > 1 && @IPS_ObjectExists($id)) {
                                    $this->RegisterReference($id);
                                }
                            }
                        }
                    }
                }
            }
        }

        //Timer
        $this->SetAutomaticRebootTimer();
        $this->SetTimerInterval('UpdateCustomApps', $this->ReadPropertyInteger('CustomAppsUpdateInterval') * 1000);

        //Updates
        $this->UpdateStats();
        $this->UpdateSettings();
        $this->UpdateCustomApps(false);

        //Will update the built-in apps and reboot the device
        $this->UpdateBuiltInApps();
    }

    public function MessageSink($TimeStamp, $SenderID, $Message, $Data): void
    {
        $this->SendDebug(__FUNCTION__, $TimeStamp . ', SenderID: ' . $SenderID . ', Message: ' . $Message . ', Data: ' . print_r($Data, true), 0);
        switch ($Message) {
            case IPS_KERNELSTARTED:
                $this->KernelReady();
                break;

            case VM_UPDATE:

                //$Data[0] = actual value
                //$Data[1] = value changed
                //$Data[2] = last value
                //$Data[3] = timestamp actual value
                //$Data[4] = timestamp value changed
                //$Data[5] = timestamp last value

                //Trigger notifications
                $this->TriggerNotifications($SenderID, $Data[1]);
                break;

        }
    }

    public function RequestAction($Ident, $Value): void
    {

        switch ($Ident) {
            case 'Power':
                $this->SetValue($Ident, $Value);
                $this->PowerDevice($Value);
                break;

            case 'Brightness':
                $this->SetValue($Ident, $Value);
                $this->SetBrightness($Value);
                break;

        }
    }

    ########## Private

    private function KernelReady(): void
    {
        $this->ApplyChanges();
    }
}