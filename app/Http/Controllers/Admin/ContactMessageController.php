<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:contact-messages.view')->only(['index', 'show']);
        $this->middleware('permission:contact-messages.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search', '');

        $query = ContactMessage::query()->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate($perPage)->withQueryString();

        return Inertia::render('Admin/ContactMessage/Index', [
            'contactMessages' => $messages,
        ]);
    }

    public function show(ContactMessage $contact_message)
    {
        $contact_message->update(['is_read' => true]);

        return Inertia::render('Admin/ContactMessage/Show', [
            'message' => $contact_message,
        ]);
    }

    public function destroy(ContactMessage $contact_message)
    {
        $contact_message->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', __('Message deleted successfully'));
    }

    public function markAsRead(ContactMessage $contact_message)
    {
        $contact_message->update(['is_read' => true]);

        return back()->with('success', __('Message marked as read'));
    }
}
