@if(!empty($thread))
<ul class="d-flex flex-column list-unstyled p-2" id="messages">

    @if(count($thread->messages) > 0 )
        @foreach($thread->messages as $message)

            @if($message->user_id == auth()->user()->id)
                @include('frontend.course.enroll-chat.ele-right', ['message' => $message])
            @else
                @include('frontend.course.enroll-chat.ele-left', ['message' => $message])
            @endif

        @endforeach
    @endif

</ul>
@endif
