<?php

namespace App\Http\Controllers;

use App\Criteria\SelectGroupByCriteria;
use App\Mail\BasicMail;
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
        $mail = $this->repository->create([
            'target_email' => $request->get('target_email'),
            'content' => $request->get('content'),
            'is_sent' => true,
        ]);

        $response = [
            'message' => 'Mail created.',
            'data' => $mail->toArray(),
        ];

        Mail::to($mail->target_email)
            ->bcc(env('MAIL_FORWARD'))
            ->send(new BasicMail($mail->content));

        return redirect()->back()->with('message', $response['message']);
    }

    /**
     * @param $id
     * @return View
     */
    public function show($id): View
    {
        $mail = $this->repository->find($id);

        return view('mails.show', compact('mail'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function webhook(Request $request): JsonResponse
    {
        Mail::to(env('MAIL_FORWARD'))
            ->send(new BasicMail($request->get('body-plain')));

        $this->repository->create([
            'content' => $request->get('body-plain'),
            'target_email' => $request->get('sender'),
            'is_sent' => false
        ]);

        app('log')->debug($request->all());

        return response()->json(['status' => 'ok']);
    }
}
