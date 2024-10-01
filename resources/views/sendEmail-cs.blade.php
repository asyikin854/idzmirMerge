@extends('layouts.simple.master-cs')
@section('title', 'Announcement Compose')

@section('css')
@endsection


@section('style')
@endsection



@section('breadcrumb-title')
    <h3>Announcement/Email Compose</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active"> Create Announcement</li>
@endsection



@section('content')
    <div class="container-fluid">
        <div class="email-wrap">
            <div class="row">
                <div class="col-xl-3 box-col-12">
                    <div class="md-sidebar"><a class="btn btn-primary md-sidebar-toggle" href="javascript:void(0)">email
                            filter</a>
                        <div class="md-sidebar-aside job-left-aside custom-scrollbar">
                            <div class="email-left-aside">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="email-app-sidebar">
                                            <div class="media">
                                                <div class="media-size-email"><img class="me-3 rounded-circle"
                                                        src="{{ asset('assets/images/user/user.png') }}" alt=""></div>
                                                <div class="media-body">
                                                    <h6 class="f-w-600">{{$csName}}</h6>
                                                    <p>{{$csEmail}} </p>
                                                </div>
                                            </div>
                                            <ul class="nav main-menu" role="tablist">
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 box-col-12">
                    <div class="email-right-aside">
                        <div class="card email-body">
                            <div class="row">
                                <div class="col-xl-4 col-md-12 box-md-12 pr-0">
                                    <div class="pe-0 b-r-light"></div>
                                </div>
                                <div class="col-xl-12 col-md-12 box-md-12">
                                    <div class="email-right-aside">
                                        <div class="email-body radius-left">
                                            <div class="ps-0">
                                                <div class="tab-content">
                                                    <div class="tab-pane fade active show" id="pills-darkhome"
                                                        role="tabpanel" aria-labelledby="pills-darkhome-tab">
                                                        <div class="email-compose">
                                                            <div class="email-top compose-border">
                                                                <div class="row">
                                                                    <div class="col-sm-8 xl-50">
                                                                        <h4 class="mb-0">New Message</h4>
                                                                        <form action="{{ route('sendEmail-cs') }}" method="POST">
                                                                            @csrf
                                                                    </div>
                                                                    <div class="col-sm-4 btn-middle xl-50">
                                                                        <button
                                                                            class="btn btn-primary btn-block btn-mail text-center mb-0 mt-0 w-100"
                                                                            type="submit"><i
                                                                                class="fa fa-paper-plane me-2"></i>
                                                                            SEND</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="email-wrapper">
                                                                <form class="theme-form">
                                                                    <div class="mb-3">
                                                                        <label for="to">To</label>
            <select name="to[]" class="form-control" multiple>
                @foreach($parents as $parent)
                    <option value="{{ $parent->email }}">{{ $parent->username }} ({{ $parent->email }})</option>
                @endforeach
            </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="exampleInputPassword1">Subject</label>
                                                                        <input type="text" name="subject" class="form-control" required>
                                                                    </div>
                                                                    <div>
                                                                        <label class="text-muted">Message</label>
                                                                        <textarea name="message" class="form-control" rows="10" required></textarea>                                                        </textarea>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="pills-darkprofile" role="tabpanel"
                                                        aria-labelledby="pills-darkprofile-tab">
                                                        <div class="email-content">
                                                            <div class="email-top">
                                                                <div class="row">
                                                                    <div class="col-md-6 xl-100 col-sm-12">
                                                                        <div class="media"><img
                                                                                class="me-3 rounded-circle"
                                                                                src="{{ asset('assets/images/user/user.png') }}"
                                                                                alt="">
                                                                            <div class="media-body">
                                                                                <h6>Lorm lpsa <small><span>(20</span>
                                                                                        January) <span>6:00</span>
                                                                                        AM</small></h6>
                                                                                <p>Mattis luctus. Donec nisi diam text.</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-12 xl-100">
                                                                        <div class="float-end d-flex">
                                                                            <p class="user-emailid">
                                                                                Lormlpsa<span>23</span>@company.com</p><i
                                                                                class="fa fa-star-o f-18 mt-1"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="email-wrapper">
                                                                <p>Hello</p>
                                                                <p>Dear Sir Good Morning,</p>
                                                                <h5>Elementum varius nisi vel tempus. Donec eleifend egestas
                                                                    viverra.</h5>
                                                                <p class="m-b-20">Lorem ipsum dolor sit amet, consectetur
                                                                    adipiscing elit. Curabitur non diam facilisis, commodo
                                                                    libero et, commodo sapien. Pellentesque sollicitudin
                                                                    massa sagittis dolor facilisis, sit amet vulputate nunc
                                                                    molestie. Pellentesque maximus nibh id luctus porta. Ut
                                                                    consectetur dui nec nulla mattis luctus. Donec nisi
                                                                    diam, congue vitae felis at, ullamcorper bibendum
                                                                    tortor. Vestibulum pellentesque felis felis. Etiam ac
                                                                    tortor felis. Ut elit arcu, rhoncus in laoreet vel,
                                                                    gravida sed tortor.</p>
                                                                <p>In elementum varius nisi vel tempus. Donec eleifend
                                                                    egestas viverra. Donec dapibus sollicitudin blandit.
                                                                    Donec scelerisque purus sit amet feugiat efficitur.
                                                                    Quisque feugiat semper sapien vel hendrerit. Mauris
                                                                    lacus felis, consequat nec pellentesque viverra,
                                                                    venenatis a lorem. Sed urna</p>
                                                                <hr>
                                                                <div class="d-inline-block">
                                                                    <h6 class="text-muted"><i
                                                                            class="icofont icofont-clip"></i> ATTACHMENTS
                                                                    </h6><a class="text-muted text-end right-download"
                                                                        href="#"><i
                                                                            class="fa fa-long-arrow-down me-2"></i>Download
                                                                        All</a>
                                                                    <div class="clearfix"></div>
                                                                </div>
                                                                <div class="attachment">
                                                                    <ul class="list-inline">
                                                                        <li class="list-inline-item"><img
                                                                                class="img-fluid"
                                                                                src="{{ asset('assets/images/email/1.jpg') }}"
                                                                                alt=""></li>
                                                                        <li class="list-inline-item"><img
                                                                                class="img-fluid"
                                                                                src="{{ asset('assets/images/email/2.jpg') }}"
                                                                                alt=""></li>
                                                                        <li class="list-inline-item"><img
                                                                                class="img-fluid"
                                                                                src="{{ asset('assets/images/email/3.jpg') }}"
                                                                                alt=""></li>
                                                                    </ul>
                                                                </div>
                                                                <hr>
                                                                <div class="action-wrapper">
                                                                    <ul class="actions">
                                                                        <li><a class="text-muted" href="#"><i
                                                                                    class="fa fa-reply me-2"></i>Reply</a>
                                                                        </li>
                                                                        <li><a class="text-muted" href="#"><i
                                                                                    class="fa fa-reply-all me-2"></i>Reply
                                                                                All</a></li>
                                                                        <li><a class="text-muted" href="#"><i
                                                                                    class="fa fa-share me-2"></i></a>Forward
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@endsection

@section('script')
    <script src="{{ asset('assets/js/editor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/editor/ckeditor/adapters/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/email-app.js') }}"></script>
@endsection
