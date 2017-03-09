<?php

namespace App\Util;

use Illuminate\Http\Response;

/**
 * This class consists of all the common methods which have to be accessed by all other classes.
 */
class CommonMethods {
	
	/**
	 * Function generates the success response to be sent post execution
	 * of a request.
	 *
	 * @param array|string $successData The data to be returned in the success 'data' section of the response body. Can be an array as well as a string.
	 * @return Response Object of the Illuminate\Http\Response class containing the necessary values.
	 */
	public static function generateSuccessResponse($successData) {
		return response()->json(['status' => 'success', 'data' => $successData]);
	}

	/**
	 * Function generates the error response to be sent post execution
	 * of a request.
	 *
	 * @param array $errorData Array containing the error code depicting which type of error was generated and the corresponding error message. Error codes are present in config/errorcodes.php.
	 * @param int $statusCode Status code of the response object created. Default value is 400 Bad Request.
	 * @return Response Object of the Illuminate\Http\Response class containing the necessary values.
	 */
	public static function generateErrorResponseWithArray($errorData, $statusCode = 400) {
		return response()->json(['status' => 'failed', 'data' => $errorData], $statusCode);
	}

	/**
	 * Function generates the error response to be sent post execution
	 * of a request.
	 *
	 * @param int $errorCode The error code depicting which type of error was generated. Error codes are present in config/errorcodes.php.
	 * @param string $errorMessage The error message giving more details on the error.
	 * @param int $statusCode Status code of the response object created. Default value is 400 Bad Request.
	 * @return Response Object of the Illuminate\Http\Response class containing the necessary values.
	 */
	public static function generateErrorResponse($errorCode, $errorMessage, $statusCode = 400) {
		return response()->json(['status' => 'failed', 'data' => ['errorCode' => $errorCode, 'errorMessage' => $errorMessage]], $statusCode);
	}
}
