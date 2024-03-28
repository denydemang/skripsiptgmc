@if (Session::has('success'))
	<span class="notifmessagesuccess" data-notifsuccess="{{ Session::get('success') }}"></span>
@else
	<span class="notifmessagesuccess" data-notifsuccess="null"></span>
@endif
@if (Session::has('error'))
	<span class="notifmessageerror" data-notiferror="{{ Session::get('error') }}"></span>
@else
	<span class="notifmessageerror" data-notiferror="null"></span>
@endif
