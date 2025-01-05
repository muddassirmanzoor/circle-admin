@if ($freelancer_uuid)
    <input type="hidden" name="freelancer_uuid" id="freelancer_uuid" value="{{ $freelancer_uuid }}">
@endif
<div class="clearfix">
    <h4 class="block">
        Freelancer Time Schedule represented by color:
        <span class="text-center">
            <button type="button" class="btn btn-success">Appointments</button>
            <button type="button" class="btn btn-info">Classess</button>
            <button type="button" class="fc-today-button fc-button fc-button-primary"
                style="font-size: 0.8em;">Blocktime</button>
            <button type="button" class="btn btn-danger">Session</button>

            {{-- <button type="button" class="btn btn-primary">Pending</button> --}}
            {{-- <button type="button" class="btn btn-warning">Rejected</button> --}}
        </span>
        <span class="pull-right hidden">
            <a href="{{ route('newAppointment', ['id' => $freelancer_uuid]) }}" class="btn btn-info">Add New
                Appointment</a>
        </span>
    </h4>
</div>
<br>
<div id="freelancerDataCalendar" class="has-toolbar"> </div>
