<?php
declare(strict_types=1);

namespace App\Http\Exception;

use Cake\Http\Exception\HttpException;
use Throwable;

class BadRequestException extends HttpException {

    protected $_defaultCode = 400;


    public function __construct($data = [], ?string $message = null, ?int $code = null, ?Throwable $previous = null) {
        $attributes = [
            'message' => $message,
            'data'    => $data,
        ];
        parent::__construct($attributes, $code, $previous);
    }
}
