<?php

use Illuminate\Database\Eloquent\Collection;

class Helper
{
    public static function getResponse(string $message = '', $data = [], int $status_code = 200, bool $status = true)
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
