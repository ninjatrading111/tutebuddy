@extends('layouts.app')

@section('content')

@push('after-styles')
<style>
[dir=ltr] #messages_content .message:nth-child(2n) .message__body {
    margin-left: inherit;
}
[dir=ltr] #messages_content .message:nth-child(2n) .message__aside {
    order: 0;
    margin-left: 0;
    margin-right: 1rem;
}
[dir=ltr] #messages_content .message.right .message__aside {
    order: 1;
    margin-right: 0;
    margin-left: 1rem;
}
[dir=ltr] #messages_content .message.right .message__body {
    margin-left: auto;
}
[dir=ltr] div.dropdown-content {
    position: absolute;
    top: -216px;
    right: 0;
    width: 50%;
    box-shadow: 0 3px 3px -2px rgb(39 44 51 / 10%), 0 3px 4px 0 rgb(39 44 51 / 4%), 0 1px 8px 0 rgb(39 44 51 / 2%);
    transition: box-shadow .28s cubic-bezier(.4, 0, .2, 1);
    will-change: box-shadow;
    border-radius: .5rem;
}
[dir=ltr] div.dropdown-content span {
    width: 35px;
    float: left;
    cursor: pointer;
    padding: 0px 5px 0px 5px;
    border-radius: 5px;
}
[dir=ltr] div.dropdown-content span:hover {
    background-color: #99ccff;
}
</style>
@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div data-push data-responsive-width="768px" data-has-scrollable-region data-fullbleed
        class="mdk-drawer-layout js-mdk-drawer-layout" style="height: calc(100vh - 160px);">
        <div class="mdk-drawer-layout__content">

            <div class="app-messages__container d-flex flex-column h-100 pb-4">
                
                <div class="flex pt-4" style="position: relative;" data-perfect-scrollbar id="wrap_message_content">
                    <div class="container page__container page__container align-bottom" id="messages_content"></div>
                </div>
                
                <div class="container page__container page__container">
                    <form method="post" action="{{route('admin.messages.reply')}}" id="message_reply">@csrf
                        <div class="input-group input-group-merge">
                            <input type="text" name="message" class="form-control form-control-appended form-control-lg" autofocus="" required=""
                                placeholder="Type message">
                            <div class="input-group-append">
                                <div class="input-group-text pr-2">
                                    <button id="btn_emoji" class="btn btn-flush" type="button">
                                        <i class="material-icons icon-16pt">tag_faces</i>
                                    </button>
                                    <div for="emoji" class="dropdown-content p-3 bg-white text-left d-none">
                                        @for($i = 128512; $i < 128556; $i++)
                                        <span class="mr-8pt icon-24pt">&#{!! $i !!};</span>
                                        @endfor
                                    </div>
                                </div>
                                <div class="input-group-text pl-0">
                                    <div class="custom-file custom-file-naked d-flex"
                                        style="width: 24px; overflow: hidden;">
                                        <input type="file" class="custom-file-input" id="customFile">
                                        <label class="custom-file-label" style="color: inherit;" for="customFile">
                                            <i class="material-icons icon-16pt">attach_file</i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="mdk-drawer js-mdk-drawer" data-align="end" id="messages-drawer">
            <div class="mdk-drawer__content top-navbar">
                <div class="sidebar sidebar-right sidebar-light bg-white o-hidden">
                    <div class="d-flex flex-column h-100">

                        <div class="d-flex px-3 pt-4 pb-3 border-bottom-1">
                            <div class="mr-3">
                                <div class="avatar avatar-online avatar-sm">
                                    @if(!empty(auth()->user()->avatar))
                                    <img src="{{ asset('/storage/avatars/' . auth()->user()->avatar ) }}" alt="people"
                                        class="avatar-img rounded-circle">
                                    @else
                                    <span class="avatar-title rounded-circle">{{ mb_substr(auth()->user()->avatar, 0, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex flex-column" style="max-width: 150px; font-size: 15px">
                                <strong class="text-body">{{ auth()->user()->name }}</strong>
                                <span class="text-50 text-ellipsis">{{ auth()->user()->headline }}</span>
                            </div>
                        </div>

                        <div class="d-flex flex-column justify-content-center navbar-height">
                            <div class="px-3 form-group mb-0">
                                <div class="input-group input-group-merge input-group-rounded flex-nowrap">
                                    <input type="text" id="filter" class="form-control form-control-prepended"
                                        placeholder="@lang('labels.backend.messages.search_placeholder')">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="material-icons">filter_list</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex" data-perfect-scrollbar>

                            <ul id="filter_result" class="list-group list-group-flush mb-3"></ul>

                            <div class="sidebar-heading">My Contact List</div>
                            
                            <ul id="contacts" class="list-group list-group-flush mb-3">

                                @foreach($partners as $partner)

                                <li class="list-group-item px-3 py-12pt bg-light" data-id="{{ $partner['user']->id }}" data-thread="@if($partner['thread']){{ $partner['thread']->id }}@endif">
                                    <a href="javascript:void(0)" class="d-flex align-items-center position-relative">
                                        <span class="avatar avatar-sm avatar-online mr-3 flex-shrink-0">
                                            @if(!empty($partner['user']->avatar))
                                            <img src="{{ asset('/storage/avatars/' . $partner['user']->avatar) }}" alt="" class="avatar-img rounded-circle">
                                            @else
                                            <span class="avatar-title rounded-circle">{{ mb_substr($partner['user']->name, 0, 2) }}</span>
                                            @endif
                                        </span>

                                        <span class="flex d-flex flex-column" style="max-width: 175px;">
                                            <strong class="text-body">{{ $partner['user']->name }}</strong>
                                            @if(!empty($partner['thread']) && $partner['thread']->userUnreadMessagesCount(Auth::id()) > 0)
                                            <span class="badge badge-notifications badge-accent" style="position: absolute; right: 0;">
                                                {{ $partner['thread']->userUnreadMessagesCount(Auth::id()) }}
                                            </span>
                                            @endif
                                            <span class="text-muted text-ellipsis">
                                                @if(!empty($partner['thread']))
                                                {{ $partner['thread']->latestMessage->body }}
                                                @else
                                                <i>...Start Chat...</i>
                                                @endif
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<script>

$(function() {

    let partner_id = '';
    let thread_id = '';

    // Send message
    $('#message_reply').on('submit', function(e) {

        e.preventDefault();
        
        $(this).ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                formData.push({
                    name: 'thread_id',
                    type: 'text',
                    value: thread_id
                });

                formData.push({
                    name: 'recipients',
                    type: 'text',
                    value: partner_id
                });
            },
            success: function(res) {
                if(res.success) {
                    if(res.action == 'send') {
                        thread_id = res.thread_id;
                        $('#messages_content').append($('<ul class="d-flex flex-column list-unstyled"></ul>'));
                    }
                    $(res.html).hide().appendTo('#messages_content ul').toggle(500);
                    updateScroll();
                    $('input[name="message"]').val('');
                }
            }
        });
    });

    // Listen message
    var channel = Echo.channel('chat');
    channel.listen('MessageSent', function(data) {
        alert(JSON.stringify(data));
    });

    $('#filter').on('keypress', function(e) {

        if(e.which == 13 && $(this).val() !== '') {

            $.ajax({
                method: 'GET',
                url: "/dashboard/messages/users/" + $(this).val(),
                success: function(res) {

                    if (res.success) {
                        $('#filter_result').html('');
                        $(res.html).hide().appendTo('#filter_result').toggle(500);
                    }
                },
                error: function(err) {
                    var errMsg = getErrorMessage(err);
                    console.log(errMsg);
                }
            });
        }
    });

    $('.sidebar').on('click', '#filter_result li, #contacts li', function(e) {

        var old_partner = partner_id;

        partner_id = $(this).attr('data-id');
        thread_id = $(this).attr('data-thread');

        $('#filter_result li, #contacts li').removeClass('bg-primary-light');
        $(this).removeClass('bg-light');
        $(this).addClass('bg-primary-light');

        // Load Message
        if(partner_id != '' && partner_id != old_partner ) {
            loadMessage(partner_id, thread_id);
            setTimeout(() => {
                $(this).find('.badge-notifications').hide();
            }, 1000);
        }
    });

    $('#btn_emoji').on('click', function() {
        if($('div[for="emoji"]').hasClass('d-none')) {
            $('div[for="emoji"]').removeClass('d-none');
        } else {
            $('div[for="emoji"]').addClass('d-none');
        }
    });

    $('div.dropdown-content').on('click', 'span', function(){
        var emoji = $(this).html();
        $('input[name="message"]').val($('input[name="message"]').val() + emoji);
    });

    setTimeout(() => {
        $('#contacts').find('li').first().trigger('click');        
    }, 1000);

    function loadMessage(partner, thread) {

        $.ajax({
            method: 'GET',
            url: "/dashboard/messages/get?partner=" + partner + "&thread=" + thread,
            success: function(res) {

                if (res.success) {
                    $('#messages_content').html('');
                    $(res.html).hide().appendTo('#messages_content').toggle(500);
                    updateScroll();
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                console.log(errMsg);
            }
        });
    }

    var x = setInterval(function() {

        if(partner_id != '' && thread_id != '') {
            
            $.ajax({
                method: 'GET',
                url: "/dashboard/messages/last?partner=" + partner_id + "&thread=" + thread_id,
                success: function(res) {
                    if (res.success && res.html != '') {
                        $(res.html).hide().appendTo('#messages_content ul').toggle(500);
                        updateScroll();
                    }
                },
                error: function(err) {
                    var errMsg = getErrorMessage(err);
                    console.log(errMsg);
                }
            });
        }

    }, 3000);

    function updateScroll() {
        var element = document.getElementById("wrap_message_content");
        element.scrollTop = element.scrollHeight;
    }

});
</script>

@endpush

@endsection