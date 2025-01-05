<?php $customer_appointments = $data['customer_appointments']; ?>
<div class="clearfix">
    <!-- <h4 class="block">
        Appointment status represented by color:
        <span class="pull-right">
            <button type="button" class="btn btn-info">Completed</button>
            <button type="button" class="btn btn-success">Confirmed</button>
            <button type="button" class="btn btn-primary">Pending</button>
            <button type="button" class="btn btn-danger">Cancelled</button>
            <button type="button" class="btn btn-warning">Rejected</button>
        </span>
    </h4> -->
</div>
<br>

<div id="custAppointCalendar" class="has-toolbar"> </div>
@if ($customer_appointments)
    <input type="hidden" name="customer_uuid" id="customer_uuid"
        value="{{ $customer_appointments[0]['customer_uuid'] }}">
    <input type="hidden" name="freelancer_id" id="freelancer_id"
        value="{{ $customer_appointments[0]['freelancer_uuid'] }}">
@endif
