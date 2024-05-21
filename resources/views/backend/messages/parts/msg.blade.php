@if(!empty($partner))
<div class="card">
    <div class="card-body d-flex align-items-center">
        <div class="mr-3">
            <div class="avatar avatar-md">
                @if(!empty($partner->avatar))
                    <img src="{{ asset('/storage/avatars/' . $partner->avatar ) }}"
                        alt="people" class="avatar-img rounded-circle">
                @else
                    <span
                        class="avatar-title rounded-circle">{{ mb_substr($partner->name, 0, 2) }}</span>
                @endif
            </div>
        </div>
        <div class="flex">
            <h4 class="mb-0">{{ $partner->name }}</h4>
            <p class="text-50 mb-0">{{ $partner->headline }} &nbsp;</p>
        </div>
    </div>
</div>
@endif

@if(!empty($thread))
<ul class="d-flex flex-column list-unstyled" id="messages">

    @if(count($thread->messages) > 0 )
        @foreach($thread->messages as $message)

            @if($message->user_id == auth()->user()->id)
                @include('backend.messages.parts.ele-right', ['message' => $message])
            @else
                @include('backend.messages.parts.ele-left', ['message' => $message])
            @endif

        @endforeach
    @endif

</ul>
@endif
