@if($freelancer_uuid)
    <input type="hidden" name="freelancer_uuid" id="freelancer_uuid" value="{{ $freelancer_uuid }}">
@endif
<div class="clearfix">
    <h4 class="block">
        Appointment status represented by color:
        <span class="text-center">
            <button type="button" class="btn btn-info">Completed</button>
            <button type="button" class="btn btn-success">Confirmed</button>
            <button type="button" class="btn btn-primary">Pending</button>
            <button type="button" class="btn btn-danger">Cancelled</button>
            <button type="button" class="btn btn-warning">Rejected</button>
        </span>
        <span class="pull-right">
            <a href="{{ route('newAppointment', ['id' => $freelancer_uuid]) }}" class="btn btn-info">Add New Appointment</a>
        </span>
    </h4>
</div>
<br>
<div id="freeAppointCalendar" class="has-toolbar"> </div>

