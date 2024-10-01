@extends('layouts.simple.master-cs')
@section('title', ' Inbox')

@section('css')
@endsection


@section('style')
@endsection



@section('breadcrumb-title')
    <h3>Announcement/Inbox</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">View Announcement</li>
@endsection



@section('content')
<div class="container-fluid">
    <div class="email-wrap">
        <div class="row">
            <div class="card">
            <div class="card-body">
    <h4>All Sent Email</h4>

    @if($messages->isEmpty())
        <div class="alert alert-warning" role="alert">
            No messages found.
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Recipient</th>
                    <th>Sent On</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $message)
                    <tr>
                        <td>{{ $message->subject }}</td>
                        <td>{{ $message->message }}</td>
                        <td>{{ $message->recipient }}</td> <!-- Assuming you have a recipient_name field -->
                        <td>{{ \Carbon\Carbon::parse($message->created_at)->format('M d, Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div></div></div></div></div></div>

@endsection
