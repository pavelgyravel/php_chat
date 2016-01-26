<div class="panel panel-default">
  <div class="panel-heading">
    All users: 
  </div>
  <div class="panel-body" id="JQ_users_list"> 
    @foreach($allUsers as $user)
      @if($user->id != $currentUserId)
      <?php $class = $user['unread'] ? 'new_message' : ''; ?>
        <div class="chat_user {{$class}}" id="JQ_thread_{{$user->thread}}">
          @if (isset($id) && $user->id == $id)
            <b>{{ $user->name }}</b>
          @else
            <a href="{{URL::to('messages/user', $user->id)}}" class="JQ_chat_user_link chat_user_link">{{ $user->name }}</a> 
          @endif
          
        </div>
      @endif
    @endforeach
  </div>
</div>