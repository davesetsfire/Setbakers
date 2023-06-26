<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Models\ContactUs;
use App\Notifications\ContactUsNotification;
use Auth;

class ContactUsController extends Controller {

    public function index(Request $request) {
        return view('pages.contact');
    }

    public function store(Request $request) {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:190'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'contactus_message' => 'required|string|max:1500',
        ]);

        $userId = Auth::user()->id ?? 0;
        $contactUsData = $request->only(['first_name', 'last_name', 'email', 'phone_number']);
        $contactUsData['user_id'] = $userId;
        $contactUsData['message'] = $request->input('contactus_message');

        ContactUs::create($contactUsData);

        $emailData['email'] = $request->input('email');
        $emailData['name'] = $request->input('first_name') . ' ' . $request->input('last_name');
        $emailData['phone_number'] = $request->input('phone_number');
        $emailData['message'] = $request->input('contactus_message');

        Notification::route('mail', config('app.contactus_email_id'))->notify(new ContactUsNotification($emailData));

        return redirect()->route('contact')
                        ->with('success_message', __('status_message.CONTACTUS_MESSAGE_ACCEPTED'));
    }

}
