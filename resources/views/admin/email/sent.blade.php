@extends('layouts.simple.master-admin')
@section('title', 'Sent Messages')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Announcement</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Announcement</li>
@endsection

@section('content')
<div class="container-fluid">
   <div class="email-wrap">
     <div class="row">
       <div class="col-xl-12 box-col-15">
         <div class="email-right-aside">
           <div class="card email-body">
             <div class="row">
               <div class="col-xl-2 col-md-12 box-md-12 pr-0">
                 <div class="pe-0 b-r-light"></div>
                 <div class="email-top">
                   <div class="row">
                     <div class="col">
                       <h5>All Sent Mails</h5>
                     </div>
                   </div>
                 </div>
                 <div class="inbox">
                   <!-- Loop through the messages and display them as clickable subjects -->
                   @if($messages->isNotEmpty())
                     @foreach($messages as $message)
                     <div class="media" data-message-id="{{ $message->id }}" style="cursor:pointer;">
                       <div class="media-body">
                         <h6>{{ $message->subject }} <br><small><span>({{ \Carbon\Carbon::parse($message->created_at)->format('d F') }})</small></h6>
                         <p>{{ Str::limit($message->message, 100) }}</p> <!-- Limit the message to 100 characters -->
                       </div>
                     </div>
                     @endforeach
                   @else
                     <div class="media">
                       <div class="media-body">
                         <h6>No new messages</h6>
                         <p>There are no announcements at the moment.</p>
                       </div>
                     </div>
                   @endif
                 </div>
               </div>

               <div class="col-xl-8 col-md-12 box-md-12 pl-0">
                 <div class="email-right-aside">
                   <div class="email-body radius-left">
                     <div class="ps-0">
                       <div class="tab-content">
                         <div class="tab-pane fade active show" id="email-content" role="tabpanel">
                           <div class="email-content">
                             <!-- This section will be updated dynamically with AJAX -->
                             <div id="message-details">
                               <p>Select a message to view its details.</p>
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
@endsection

@section('script')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>

<script>
$(document).ready(function() {
    // When a message in the inbox is clicked
    $('.media').on('click', function() {
        var messageId = $(this).data('message-id'); // Get the message ID from the clicked element

        // Send AJAX request to fetch the message details
        $.ajax({
            url: "{{ route('admin.email.fetch') }}", // Route to fetch message details
            method: 'GET',
            data: { id: messageId },
            success: function(response) {
                // Update the message details section with the fetched data
                $('#message-details').html(response);
            },
            error: function(xhr, status, error) {
                console.log('Error fetching message: ' + error);
            }
        });
    });
});
</script>
@endsection
