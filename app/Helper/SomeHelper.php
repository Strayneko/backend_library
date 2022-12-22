<?php

class SomeHelper
{

    public static function getResponse($message, $data = [], $status_code, $status)
    {

        $response = [
            'status' => true,
            'status_code' => 200,
            'message' => '',
        ];
        if ($status_code != 200) $response['status'] = false;
        if (!empty($data)) $response['data'] = $data;
        return $response;
    }
}
