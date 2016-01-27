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
        $currentUser = Auth::user();

        foreach ($allUsers as $i => $user) {
            $thread = Thread::between([$currentUser->id, $user->id])->first();
            if ($thread != null) {
                $allUsers[$i]['unread'] = $thread->isUnread($currentUser->id);
                $allUsers[$i]['thread'] = $thread->id;
            }
        }

        view()->share('allUsers', $allUsers);
        view()->share('currentUser', $currentUser);
    }
    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    public function index()
    {   
        
        $currentUserId = Auth::user()->id;
        $threads = Thread::forUser($currentUserId)->latest('updated_at')->get();
        return view('messenger.index', compact('threads'));
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
            $user = User::find($id);
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


            
            return view('messenger.show', compact('thread', 'id', 'user'));    

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