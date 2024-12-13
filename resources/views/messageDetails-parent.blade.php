<div class="email-top">
    <div class="row">
      <div class="col-md-6 xl-100 col-sm-12">
        <div class="media">
          <img class="me-3 rounded-circle" src="{{ asset('assets/images/logo/logoidzmir.png') }}" style="width: 50px" alt="">
          <div class="media-body">
            <h6>Admin <small><span>{{ \Carbon\Carbon::parse($message->created_at)->format('d F') }}</span> <span>{{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}</span></small></h6>
            <p>{{ $message->subject }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="email-wrapper">
    <p>{{ $message->message }}</p> 
  
    @if ($message->attachments)

    @php
    $attachments = json_decode($message->attachments, true);
@endphp
      <hr>
      <div class="d-inline-block">
        <h6 class="text-muted"><i class="icofont icofont-clip"></i> ATTACHMENTS</h6>
        @foreach ($attachments as $attachment)
        <a class="text-muted text-end right-download" href="{{ asset('attachments/' . basename($attachment)) }}" target="_blank">
            {{ basename($attachment) }}">
          <i class="fa fa-long-arrow-down me-2"></i>Download Attachment
        </a>
        @endforeach
        @else
                                <p class="text-muted">No attachments</p>
@endif
      </div>
    <hr>
  </div>
  