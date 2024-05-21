@extends('layouts.app')

@section('content')

@push('after-styles')
<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">
@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="container page__container">
        <div class="page-section">

            <div class="row">
                <div class="col-md-8">

                    <h1 class="h2 measure-lead-max mb-2">{{ $discussion->title }}</h1>
                    <p class="text-muted d-flex align-items-center mb-lg-32pt">
                        <a href="{{ route('admin.discussions.index') }}" class="mr-3">@lang('labels.backend.discussion_detail.back')</a>
                        <!-- <a href="{{ route('admin.discussions.edit', $discussion->id) }}" class="text-50" style="text-decoration: underline;">Edit</a> -->
                    </p>

                    <div class="card card-body">
                        <div class="d-flex">
                            <a href="" class="avatar avatar-sm avatar-online mr-12pt">
                                @if(!empty($discussion->user->avatar))
                                <img src="{{ asset('/storage/avatars/' . $discussion->user->avatar) }}" alt="people" class="avatar-img rounded-circle">
                                @else
                                <span class="avatar-title rounded-circle">{{ mb_substr($discussion->user->name, 0, 2) }}</span>
                                @endif
                            </a>
                            <div class="flex">
                                <p class="d-flex align-items-center mb-2">
                                    <a href="" class="text-body mr-2"><strong>{{ $discussion->user->name }}</strong></a>
                                    <small class="text-muted">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($discussion->updated_at))->diffForHumans() }}</small>
                                </p>
                                <p>{{ $discussion->question }}</p>
                                <!-- <div class="d-flex align-items-center">
                                    <a href="" class="text-50 d-flex align-items-center text-decoration-0">
                                        <i class="material-icons mr-1" style="font-size: inherit;">favorite_border</i> 30</a>
                                    <a href="" class="text-50 d-flex align-items-center text-decoration-0 ml-3">
                                        <i class="material-icons mr-1" style="font-size: inherit;">thumb_up</i> 130</a>
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <div id="comments" class="pt-3 mb-64pt">
                        <h4>{{ $discussion->results->count() }} @lang('labels.backend.discussion_detail.comments')</h4>

                        @foreach($discussion->results as $result)

                        @if(empty($result->parent))
                        <div class="d-flex mb-3">
                            <a href="" class="avatar avatar-sm mr-12pt">
                                @if(!empty($result->user->avatar))
                                <img src="{{ asset('/storage/avatars/' . $result->user->avatar) }}" alt="people" class="avatar-img rounded-circle">
                                @else
                                <span class="avatar-title rounded-circle">{{ mb_substr($result->user->name, 0, 2) }}</span>
                                @endif
                            </a>
                            <div class="flex comment-group">
                                <a href="" class="text-body"><strong>{{ $result->user->name }}</strong></a><br>
                                <p class="mt-1 text-70">{!! $result->content !!}</p>
                                
                                <div class="d-flex align-items-center">
                                    <small class="text-50 mr-2">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($result->updated_at))->diffForHumans() }}</small>
                                    @if($result->user->id != auth()->user()->id)
                                    <button class="btn btn-sm btn-outline-secondary add-comment">@lang('labels.backend.discussion_detail.add')</button>
                                    @endif
                                </div>
                                
                                <div class="mt-3 card p-3 post-form d-none">
                                    <form method="post" action="{{ route('admin.ajax.postComment') }}" class="reply-form">@csrf
                                        <div class="form-group">
                                            <label class="form-label">reply</label>
                                            <textarea class="form-control" name="content" rows="8" placeholder="Type here to reply to Matney ..."></textarea>
                                        </div>
                                        <input type="hidden" name="discussion_id" value="{{ $discussion->id }}">
                                        <input type="hidden" name="post_user_id" value="{{ $result->user->id }}">
                                        <input type="hidden" name="parent" value="{{ $result->id }}">
                                        <button type="submit" class="btn btn-outline-secondary">@lang('labels.backend.discussion_detail.post')</button>
                                    </form>
                                </div>

                                @if(!empty($result->childs))
                                    @foreach($result->childs as $child)
                                    <div class="ml-sm-32pt mt-3 card p-3">
                                        <div class="d-flex">
                                            <a href="#" class="avatar avatar-sm mr-12pt">
                                                <span class="avatar-title rounded-circle">{{ mb_substr($child->user->name, 0, 2) }}</span>
                                            </a>
                                            <div class="flex">
                                                <div class="d-flex align-items-center">
                                                    <a href="" class="text-body"><strong>{{ $child->user->name }}</strong></a>
                                                    <small class="ml-auto text-muted">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($child->updated_at))->diffForHumans() }}</small>
                                                </div>
                                                <p class="mb-1 text-70">{!! $child->content !!}</p>

                                                <div class="d-flex align-items-center">
                                                    <!-- <a href="" class="text-50 d-flex align-items-center text-decoration-0"><i class="material-icons mr-1" style="font-size: inherit;">favorite_border</i> 3</a>
                                                    <a href="" class="text-50 d-flex align-items-center text-decoration-0 ml-3"><i class="material-icons mr-1" style="font-size: inherit;">thumb_up</i> 13</a> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                                
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <hr>

                    <div class="card card-body">
                        <div class="d-flex mb-16pt">
                            <a href="" class="avatar avatar-sm mr-12pt">
                                @if(!empty(auth()->user()->avatar))
                                <img src="{{ asset('/storage/avatars/' . auth()->user()->avatar) }}" alt="people" class="avatar-img rounded-circle">
                                @else
                                <span class="avatar-title rounded-circle">{{ mb_substr(auth()->user()->name, 0, 2) }}</span>
                                @endif
                            </a>
                            <div class="flex">
                                <a href="" class="text-body"><strong>{{ auth()->user()->name }}</strong></a><br>
                                <label class="form-label">@lang('labels.backend.general.your_reply')</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <form id="frm_commet" method="post" action="{{ route('admin.ajax.postComment') }}">@csrf
                                <div class="form-group">
                                    
                                    <div id="comment_editor" style="height: 300px;"></div>
                                </div>
                                <input type="hidden" name="discussion_id" value="{{ $discussion->id }}">
                                <input type="hidden" name="post_user_id" value="{{ $discussion->user->id }}">
                                <button type="submit" class="btn btn-outline-secondary">@lang('labels.backend.discussion_detail.post')</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.discussion_detail.top_contributors.title')</div>
                    </div>
                    <p class="text-70 mb-24pt">@lang('labels.backend.discussion_detail.top_contributors.description')</p>

                    <div class="mb-4">

                        @foreach($top_discussions as $item)
                        <div class="d-flex align-items-center mb-2">
                            <a href="" class="avatar avatar-xs mr-8pt">
                                @if(!empty($item->user->avatar))
                                <img src="{{ asset('/storage/avatars/' . $item->user->avatar) }}" alt="" class="avatar-img rounded-circle">
                                @else
                                <span class="avatar-title rounded-circle">{{ mb_substr($item->user->name, 0, 2) }}</span>
                                @endif
                            </a>
                            <a href="" class="flex mr-2 text-body"><strong>{{ $item->user->name }}</strong></a>
                            <span class="text-70 mr-2">{{ $item->results->count() }}</span>
                            <i class="text-muted material-icons font-size-16pt">forum</i>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>
    $(function() {

        var toolbarOptions = [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'color': [] }, { 'background': [] }],  
            ['bold', 'italic', 'underline'],
            ['link', 'blockquote', 'code', 'image'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
        ];

        // Init Quill Editor for Assignment Content
        var comment_editor = new Quill('#comment_editor', {
            theme: 'snow',
            placeholder: 'Comment Content',
            modules: {
                toolbar: toolbarOptions
            },
        });

        $('#frm_commet').on('submit', function(e){
            e.preventDefault();

            $(this).ajaxSubmit({
                beforeSubmit: function(formData, formObject, formOptions) {
                    var content = comment_editor.root.innerHTML;

                    // Append Course ID
                    formData.push({
                        name: 'content',
                        type: 'text',
                        value: content
                    });
                },
                success: function(res) {
                    $(res.result).hide().appendTo($('#comments')).toggle(500);
                }
            });
        });

        $('button.add-comment').on('click', function() {
            var form = $(this).closest('.comment-group').find('form');            
            var postForm = $(this).closest('.comment-group').find('.post-form');
            if(postForm.hasClass('d-none')) {
                $(this).closest('.comment-group').find('.post-form').removeClass('d-none');
                $(this).text('Cancel');
            } else {
                $(this).closest('.comment-group').find('.post-form').addClass('d-none');
                $(this).text('Add Comment');
            }
        });

        $('form.reply-form').on('submit', function(e) {
            e.preventDefault();

            var form_group = $(this).closest('.comment-group');

            $(this).ajaxSubmit({
                success: function(res) {
                    $(res.result).hide().appendTo(form_group).toggle(500);
                    form_group.find('.post-form').addClass('d-none');
                }
            });
        });
    });
</script>
@endpush

@endsection