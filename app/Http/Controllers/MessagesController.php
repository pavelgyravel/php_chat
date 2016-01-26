<?php
namespace App\Http\Controllers;
use App\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Request;

class MessagesController extends Controller
{

    public function __construct()
    {
        $allUsers = User::all();
        $currentUserId = Auth::user()->id;

        foreach ($allUsers as $i => $user) {
            $thread = Thread::between([$currentUserId, $user->id])->first();
            if ($thread != null) {
                $allUsers[$i]['unread'] = $thread->isUnread($currentUserId);
                $allUsers[$i]['thread'] = $thread->id;
            }
        }

        view()->share('allUsers', $allUsers);
        view()->share('currentUserId', $currentUserId);
    }
    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    public function index()
    {   
        //dd(new Thread);
        $currentUserId = Auth::user()->id;
        // All threads, ignore deleted/archived participants
        //$threads = Thread::getAllLatest()->get();
        // All threads that user is participating in
        $threads = Thread::forUser($currentUserId)->latest('updated_at')->get();
        // All threads that user is participating in, with new messages
        // $threads = Thread::forUserWithNewMessages($currentUserId)->latest('updated_at')->get();
        return view('messenger.index', compact('threads'));
    }
    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');
            return redirect('messages');
        }
        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();
        // don't show the current user in list
        $userId = Auth::user()->id;
        $users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();
        $thread->markAsRead($userId);
        return view('messenger.show', compact('thread', 'users'));
    }
    /**
     * Creates a new message thread.
     *
     * @return mixed
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('messenger.create', compact('users'));
    }
    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store()
    {
        $input = Input::all();
        $thread = Thread::create(
            [
                'subject' => $input['subject'],
            ]
        );
        // Message
        Message::create(
            [
                'thread_id' => $thread->id,
                'user_id'   => Auth::user()->id,
                'body'      => $input['message'],
            ]
        );
        // Sender
        Participant::create(
            [
                'thread_id' => $thread->id,
                'user_id'   => Auth::user()->id,
                'last_read' => new Carbon,
            ]
        );
        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipants($input['recipients']);
        }
        return redirect('messages');
    }
    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        try {
            //$thread = Thread::findOrFail($id);
            $thread = Thread::between([Auth::id(), $id])->first();
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');
            return redirect('messages');
        }
        $thread->activateAllParticipants();
        // Message
        Message::create(
            [
                'thread_id' => $thread->id,
                'user_id'   => Auth::id(),
                'body'      => Input::get('message'),
            ]
        );
        // Add replier as a participant
        $participant = Participant::firstOrCreate(
            [
                'thread_id' => $thread->id,
                'user_id'   => Auth::user()->id,
            ]
        );
        $participant->last_read = new Carbon;
        $participant->save();
        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipants(Input::get('recipients'));
        }

        return $thread->messages()->where('created_at', '>', Input::get('last'))->with('user')->get();
    }

    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function user($id)
    {   
        if (Auth::id() != $id) {
            $thread = Thread::between([Auth::id(), $id])->first();
            
            if ($thread == null) {
                $thread = Thread::create(
                    [
                        'subject' => "Thread between 2 users",
                    ]
                );
                Participant::create(
                    [
                        'thread_id' => $thread->id,
                        'user_id'   => Auth::user()->id,
                        'last_read' => new Carbon,
                    ]
                );
                $thread->addParticipants([$id]);
            }

            $thread->markAsRead(Auth::id());


            
            return view('messenger.show', compact('thread', 'id'));    

        } else {
            return redirect('messages');
        }
    }

    public function getMessages($id) {

        if (Auth::id() != $id) {
            $thread = Thread::between([Auth::id(), $id])->first();
            $thread->markAsRead(Auth::id());
            return $thread->messages()->where('created_at', '>', Input::get('last'))->with('user')->with('participants')->get();
        } else {
            return redirect('messages');
        }
    }

    public function newMessages() {
        return Thread::forUserWithNewMessages(Auth::user()->id)->latest('updated_at')->get();
    }

}