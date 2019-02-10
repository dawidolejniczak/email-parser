<?php

namespace App\Http\Controllers;

use App\Criteria\SelectGroupByCriteria;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Auth;
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
            'user_id' => Auth::user()->id
        ]);

        $response = [
            'message' => 'Mail created.',
            'data' => $mail->toArray(),
        ];

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
}
