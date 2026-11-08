<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\DemoRequest;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'services' => Service::count(),
            'team_members' => TeamMember::count(),
            'portfolios' => Portfolio::count(),
            'testimonials' => Testimonial::count(),
            'unread_messages' => ContactMessage::unread()->count(),
            'total_messages' => ContactMessage::count(),
            'demo_requests_month' => DemoRequest::where('created_at', '>=', now()->startOfMonth())->count(),
            'demo_requests_pending' => DemoRequest::pending()->count(),
            'messages_month' => ContactMessage::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        $recentMessages = ContactMessage::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentMessages'));
    }
}
