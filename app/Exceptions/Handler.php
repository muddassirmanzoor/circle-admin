<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
class Handler extends ExceptionHandler {

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
            //
    ];

    public function ignore(string $class){}
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception) {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception) {
        if ($exception instanceof \Illuminate\Http\Exceptions\PostTooLargeException) {
            return \Illuminate\Support\Facades\Response::json(["success" => false, "message" => "File size should be less than 6MB"]);
        }
        if ($this->isHttpException($exception)) {
            switch ($exception->getStatusCode()) {

                // not authorized
                case '403':
                    return \Response::view('errors.403');
                    break;

                // not found
                case '404':
                    return \Response::view('errors.404');
                    break;

                // internal error
                case '500':
                    return \Response::view('errors.500');
                    break;

                default:
                    return $this->renderHttpException($exception);
                    break;
            }
        }
        return parent::render($request, $exception);
    }
    public function shouldReport(Throwable $exception){}
    public function renderForConsole($output, Throwable $exception){}

}
