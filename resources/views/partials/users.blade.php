<div class="panel panel-default">
  <div class="panel-heading">
    All users: 
  </div>
  <div class="panel-body" id="JQ_users_list"> 
    @foreach($allUsers as $user)
      @if($user->id != $currentUserId)
        <div class="chat_user ">
          <a href="{{URL::to('messages/user', $user->id)}}" class="JQ_chat_user_link chat_user_link">{{ $user->name }}</a> 
        </div>
      @endif
    @endforeach
  </div>
</div>