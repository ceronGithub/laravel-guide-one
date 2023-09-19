<?php

namespace App\Helpers;

use App\Http\Controllers\Controller;
use App\Requests\User\RegisterRequest;
use App\Traits\Api\ApiResponses;
use App\Traits\DB\UserTable;
use App\Traits\Manager\ConnectionManager;
use DateTime;
use DateTimeZone;
use Exception;

class UtilActivityLogging
{
    use ConnectionManager;

    // List of items to remove for admin
    const ITEMS_TO_REMOVE_ADMIN = [
        "remember_token",
        "role_id",
        "first_name",
        "last_name",
        "active",
        "created_at",
        "updated_at",
        "id"
    ];

    const ITEMS_TO_REMOVE_ACTIVITY = [
        "causer_id",
        "causer_type",
        "id",

    ];

    public static function saveUserActivityLog(string $message, $properties = null, string $log_name = "")
    {
        if ($properties == null)
            $properties = ['IP' => $_SERVER["REMOTE_ADDR"]];
        else
            $properties['IP'] = $_SERVER["REMOTE_ADDR"];

        $logData = self::saveUserActivityToVMMS($message, $properties, $log_name);
        if($log_name != ""){
            self::saveToTextFile($logData);
            if(env('SPLUNK_ENABLED') == true)
                self::saveUserActivityLogToSplunk($logData);
        }
    }

    public static function saveUserActivityToVMMS(string $message, $properties = null, string $log_name = ""){
        return activity($log_name)->withProperties($properties)->log($message);
    }

    public static function saveUserActivityLogToSplunk($activity)
    {
        $encodedJson = self::generateBodyForSplunk($activity);

        try {
            (new static)->connect(
                "",
                "",
                [
                    'Content-Type: text/plain',
                    'Content-Length: ' . strlen($encodedJson),
                    "Content: " . $encodedJson,
                    "Authorization: Splunk " . env('SPLUNK_TOKEN')
                ],
                $encodedJson,
                env('SPLUNK_COLLECTOR_JSON')
            );
        } catch (Exception $e) {
            dd("Error: " . $e->getMessage());
        }
    }

    public static function generateBodyForSplunk($activity) : String {
        $logArray = $activity->toArray();

        self::convertTimeToGMTPlus8($logArray);
        self::cleanUpUserActivityLog($logArray);

        $data = self::generateSplunkLogForUser($activity, $logArray);

        $encodedJson = json_encode($data);
        return $encodedJson;
    }

    public static function saveToTextFile($activity){
        $encodedJson = self::generateBodyForSplunk($activity);

        $date = date('Ymd');

        $filename = "log-$date.txt";

        $filePath = storage_path("app/$filename");

        file_put_contents($filePath, "\n" . $encodedJson, FILE_APPEND);

    }

    private static function generateSplunkLogForUser($activity, $eventData)
    {
        $data = array(
            "event" => $eventData,
            "sourcetype" => "manual",
        );

        return $data;
    }

    private static function convertTimeToGMTPlus8(&$data)
    {
        $timezone = new DateTimeZone('Asia/Singapore');
        if (isset($data['created_at'])) {
            $createdDateTime = new DateTime($data['created_at']);
            $createdDateTime->setTimezone($timezone);
            $data['created_at'] = $createdDateTime->format('Y-m-d H:i:s');
        }
        if (isset($data['updated_at'])) {
            $updatedDateTime = new DateTime($data['updated_at']);
            $updatedDateTime->setTimezone($timezone);
            $data['updated_at'] = $updatedDateTime->format('Y-m-d H:i:s');
        }
    }

    private static function cleanUpUserActivityLog(&$logArray)
    {
        self::removeItemsInAdminActivity($logArray, self::ITEMS_TO_REMOVE_ACTIVITY);
    }

    private static function removeItemsInAdminActivity(&$data, $itemsToRemove)
    {
        foreach ($data as $key => &$value) {
            if (is_array($value) || is_object($value)) {
                if($key === "causer")
                    self::removeItemsRecursively($value, self::ITEMS_TO_REMOVE_ADMIN);
            } else {
                if (in_array($key, $itemsToRemove)) {
                    unset($data[$key]);
                }
            }
        }
    }

    private static function removeItemsRecursively(&$data, $itemsToRemove)
    {
        foreach ($data as $key => &$value) {
            if (is_array($value) || is_object($value)) {
                self::removeItemsRecursively($value, $itemsToRemove);
            } else {
                if (in_array($key, $itemsToRemove)) {
                    unset($data[$key]);
                }
            }
        }
    }
}
