@extends('common_pages.layouts')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/image-picker.css') }}" />  
<link rel="stylesheet" href="{{ asset('css/ticket-templates.css') }}">
<style>
    #template-2{
        display: none;
    }
</style>
@endsection

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-edit"></i> Choose Ticket Template</h1>
          <p>Select the template you want to use on this ticket</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">              
            <div class="tile-body">
              <form method="POST" autocomplete="off" action="{{ route('add_ticket_template_post') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                 <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                    <select name="ticket_template" class="image-picker">               
                        <option data-img-src="{{ asset('ticket-template.png') }}"  data-img-class="first" data-img-alt="image" value="1">  Template 1  </option>      
                        <option @if ($event->ticket_template!==NULL && $event->ticket_template==2) selected @endif data-img-src="{{ asset('ticket-template2.png') }}"  data-img-class="first" data-img-alt="image" value="2">  Template 1</option>                  
                    </select>  
                    <input type="hidden" value="{{$event->id}}" name="event_id">
                    </div>
                   </div>
                  </div>              
                  <small>See the preview of the template below </small> 
                  <div id="template-1">
                        @include('event_organizer.ticket_templates.template_1')
                  </div>
                  <div id="template-2">
                        @include('event_organizer.ticket_templates.template_2')
                  </div>
                  <div class="tile-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                  </div>
              </form>             
            </div>
          </div>
        </div>
      </div>
    </main>
@endsection

@section('other-scripts')
<script type="text/javascript" src="{{ asset('js/plugins/image-picker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/bootstrap-notify.min.js') }}"></script>
<script>
    $(".image-picker").imagepicker();
    $(".image_picker_image").attr('width','330');
    
    $('select').on('change', function() {
        if(this.value==1){
            $('#template-2').hide();
            $('#template-1').fadeIn("slow");
        }else{
            $('#template-1').hide();
            $('#template-2').fadeIn("slow");
        }
    });
    
</script>
@if ($event->ticket_template!==NULL && $event->ticket_template==2)
<script>
    $('#template-1').hide();
    $('#template-2').fadeIn("slow");
    $(".image_picker_image").attr('width','330');
</script>
@endif
@if (session('status'))
    <script type="text/javascript">
      $.notify({
            title: "Success : ",
            message: "{{ session('status') }}",
            icon: 'fa fa-check' 
          },{
            type: "info"
      });
    </script>        
@endif 
@endsection