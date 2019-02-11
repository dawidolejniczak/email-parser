<?php

namespace App\Http\Controllers;

use App\Criteria\SelectGroupByCriteria;
use App\Mail\BasicMail;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use App\Http\Requests\MailCreateRequest;
use App\Repositories\MailRepository;

/**
 * Class MailsController.
 *
 * @package namespace App\Http\Controllers;
 */
class MailsController extends Controller
{
    /**
     * @var MailRepository
     */
    protected $repository;


    /**
     * MailsController constructor.
     * @param MailRepository $repository
     */
    public function __construct(MailRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $this->repository->pushCriteria(new SelectGroupByCriteria('target_email'));
        $mails = $this->repository->all();

        return view('mails.index', compact('mails'));
    }

    /**
     * @param MailCreateRequest $request
     * @return RedirectResponse
     */
    public function store(MailCreateRequest $request): RedirectResponse
    {

        try {
            Mail::to($request->get('target_email'))
                ->bcc(env('MAIL_FORWARD'))
                ->send(new BasicMail($request->get('content')));
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors($exception->getMessage());

        }

        $mail = $this->repository->create([
            'target_email' => $request->get('target_email'),
            'content' => $request->get('content'),
            'is_sent' => true,
        ]);

        $response = [
            'message' => 'Mail created.',
            'data' => $mail->toArray(),
        ];


        return redirect()->back()->with('message', $response['message']);
    }

    /**
     * @param string $mail
     * @return View
     */
    public function show(string $mail): View
    {
        $mails = $this->repository->findWhere(['target_email' => $mail]);

        if (!$mails) {
            abort(404, "Thread doesn't exists.");
        }

        return view('mails.show', compact('mails'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function webhook(Request $request): JsonResponse
    {

        $mailsWithSameTarget = $this->repository->findWhere(['target_email' => $request->get('sender')]);

        if (!$mailsWithSameTarget->isEmpty()) {
            Mail::to(env('MAIL_FORWARD'))
                ->send(new BasicMail($request->get('stripped-html')));

            $this->repository->create([
                'content' => $request->get('stripped-html'),
                'target_email' => $request->get('sender'),
                'is_sent' => false
            ]);

            app('log')->debug($request->all());

            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'fail']);
    }
}
