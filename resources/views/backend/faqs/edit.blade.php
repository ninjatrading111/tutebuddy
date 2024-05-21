@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Create a Faq</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.faqs.index') }}">Faq List</a>
                        </li>

                        <li class="breadcrumb-item active">
                            New Faq
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.faqs.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section">
        <div class="container page__container">
            <div class="row">
                <div class="col-8">

                {!! Form::model($faq, ['method' => 'PATCH', 'route' => ['admin.faqs.update', $faq->id]]) !!}

                    <div class="form-group">
                        <label class="form-label">Question:</label>
                        {!! Form::textarea('question', null, array('placeholder' => 'Question','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-label">Answer:</label>
                        {!! Form::textarea('answer', null, array('placeholder' => 'Answer','class' => 'form-control')) !!}
                    </div>
                    <hr>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

</div>

@endsection