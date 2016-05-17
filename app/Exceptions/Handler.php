<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Log;
use EDAM\Error\EDAMSystemException,
    EDAM\Error\EDAMUserException,
    EDAM\Error\EDAMErrorCode,
    EDAM\Error\EDAMNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {


        // Evernote系のエラーログとる
        if($e instanceof EDAMSystemException) {         
            if (isset(EDAMErrorCode::$__names[$e->errorCode])) {
                Log::error('Error listing notebooks: ' . EDAMErrorCode::$__names[$e->errorCode] . ": " . $e->parameter);
            } else {
                Log::error('Error listing notebooks: ' . $e->getCode() . ": " . $e->getMessage());
            }
        } else if($e instanceof EDAMUserException) {
            if (isset(EDAMErrorCode::$__names[$e->errorCode])) {
                Log::error('Error listing notebooks: ' . EDAMErrorCode::$__names[$e->errorCode] . ": " . $e->parameter);
            } else {
                Log::error('Error listing notebooks: ' . $e->getCode() . ": " . $e->getMessage());
            }
        } else if($e instanceof EDAMNotFoundException) {
            if (isset(EDAMErrorCode::$__names[$e->errorCode])) {
                Log::error('Error listing notebooks: ' . EDAMErrorCode::$__names[$e->errorCode] . ": " . $e->parameter);
            } else {
                Log::error('Error listing notebooks: ' . $e->getCode() . ": " . $e->getMessage());
            }
        } 


        Log::error($e);

       // parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        return parent::render($request, $e);
    }


    /**
     * エラーページ共通化
     *
     **/
    protected function renderHttpException(HttpException $e)
    {
        $status = $e->getStatusCode();
        return response()->view("errors.common", ['exception' => $e], $status);
    }
}
