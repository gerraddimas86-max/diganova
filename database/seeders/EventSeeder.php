<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventGroup;
use App\Models\EventRegistration;
use App\Models\EventSchedule;
use App\Models\TicketCategory;
use App\Models\User;
use App\Models\VenueMap;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('username', 'admin')->first();
        if (!$admin) {
            $this->command->warn('⚠️  Admin user belum ada. Jalankan UserSeeder dulu.');
            return;
        }

        // ============ EVENT 1: Festival Utama ============
        $event = Event::updateOrCreate(
            ['slug' => 'diganova-festival-2026'],
            [
                'title' => 'DIGANOVA Festival 2026',
                'description' => "Festival tahunan DIGANOVA yang menggabungkan musik, cosplay, seni, dan turnamen game. Tiga hari penuh hiburan dengan artis nasional, kompetisi cosplay, pameran seni digital, dan turnamen Navo Clash dengan hadiah total 50 juta rupiah.\n\nDapatkan pengalaman festival digital pertama yang menyatukan event, komunitas, dan hiburan dalam satu ekosistem!",
                'category' => 'festival',
                'location' => 'Jakarta Convention Center',
                'address' => 'Jl. Gatot Subroto, Jakarta Pusat, DKI Jakarta 10270',
                'latitude' => -6.2146,
                'longitude' => 106.8009,
                'start_date' => now()->addMonths(2)->startOfDay(),
                'end_date' => now()->addMonths(2)->addDays(2)->endOfDay(),
                'registration_deadline' => now()->addMonths(2)->subDays(3),
                'banner_image' => 'events/diganova-festival-2026.jpg',
                'max_participants' => 5000,
                'status' => 'published',
                'created_by' => $admin->id,
            ]
        );

        // Ticket categories
        $tickets = [
            ['name' => 'Daily Pass', 'description' => 'Tiket 1 hari (pilih hari saat check-in)', 'price' => 50000, 'quota' => 3000],
            ['name' => 'Full Pass', 'description' => 'Tiket akses penuh 3 hari', 'price' => 150000, 'quota' => 1500],
            ['name' => 'VIP Pass', 'description' => 'Tiket VIP + akses backstage + merchandise eksklusif', 'price' => 300000, 'quota' => 500],
        ];

        foreach ($tickets as $ticket) {
            TicketCategory::updateOrCreate(
                ['event_id' => $event->id, 'name' => $ticket['name']],
                array_merge($ticket, ['event_id' => $event->id, 'is_active' => true])
            );
        }

        // Schedules
        $schedules = [
            ['title' => 'Opening Ceremony', 'description' => 'Pembukaan festival + sambutan', 'start_time' => $event->start_date->copy()->setTime(9, 0), 'end_time' => $event->start_date->copy()->setTime(10, 0), 'location' => 'Main Stage', 'sort_order' => 1],
            ['title' => 'Cosplay Competition', 'description' => 'Lomba cosplay kategori solo & grup', 'start_time' => $event->start_date->copy()->setTime(10, 30), 'end_time' => $event->start_date->copy()->setTime(13, 0), 'location' => 'Hall A', 'sort_order' => 2],
            ['title' => 'Navo Clash Tournament - Qualifier', 'description' => 'Babak kualifikasi turnamen Navo Clash', 'start_time' => $event->start_date->copy()->setTime(14, 0), 'end_time' => $event->start_date->copy()->setTime(17, 0), 'location' => 'Game Zone', 'sort_order' => 3],
            ['title' => 'Music Concert - Day 1', 'description' => 'Konser musik hari pertama', 'start_time' => $event->start_date->copy()->setTime(19, 0), 'end_time' => $event->start_date->copy()->setTime(22, 0), 'location' => 'Main Stage', 'sort_order' => 4],
            ['title' => 'Art Exhibition', 'description' => 'Pameran seni digital & lukisan', 'start_time' => $event->start_date->copy()->addDay()->setTime(10, 0), 'end_time' => $event->start_date->copy()->addDay()->setTime(18, 0), 'location' => 'Hall B', 'sort_order' => 5],
            ['title' => 'Navo Clash Tournament - Final', 'description' => 'Grand final turnamen Navo Clash', 'start_time' => $event->start_date->copy()->addDays(2)->setTime(15, 0), 'end_time' => $event->start_date->copy()->addDays(2)->setTime(18, 0), 'location' => 'Game Zone', 'sort_order' => 6],
        ];

        foreach ($schedules as $schedule) {
            EventSchedule::updateOrCreate(
                ['event_id' => $event->id, 'title' => $schedule['title']],
                array_merge($schedule, ['event_id' => $event->id])
            );
        }

        // Venue map
        VenueMap::updateOrCreate(
            ['event_id' => $event->id],
            [
                'image_path' => 'events/venue-maps/festival-2026.png',
                'description' => 'Denah venue DIGANOVA Festival 2026: Hall A (Cosplay), Hall B (Art), Main Stage, Game Zone, Food Court, Merchandise Area.',
            ]
        );

        // Event group (otomatis dibuat)
        $group = EventGroup::updateOrCreate(
            ['event_id' => $event->id],
            [
                'name' => 'DIGANOVA Festival 2026 Community',
                'description' => 'Grup resmi peserta festival. Dapatkan info terkini & ngobrol bareng peserta lain!',
            ]
        );

        // Add admin ke grup sebagai admin
        $group->members()->syncWithoutDetaching([
            $admin->id => ['role' => 'admin', 'joined_at' => now()],
        ]);

        // ============ EVENT 2: Turnamen Navo Clash ============
        $event2 = Event::updateOrCreate(
            ['slug' => 'navo-clash-championship'],
            [
                'title' => 'Navo Clash Championship Season 1',
                'description' => "Turnamen resmi Navo Clash untuk memperebutkan gelar Champion Season 1. Total hadiah 25 juta rupiah + skin eksklusif.\n\nFormat: Single elimination, best of 3. Terbuka untuk semua rank.",
                'category' => 'game_tournament',
                'location' => 'Online - DIGANOVA Playground',
                'address' => null,
                'latitude' => null,
                'longitude' => null,
                'start_date' => now()->addWeeks(3)->startOfDay(),
                'end_date' => now()->addWeeks(3)->endOfDay(),
                'registration_deadline' => now()->addWeeks(2),
                'banner_image' => 'events/navo-clash-championship.jpg',
                'max_participants' => 256,
                'status' => 'published',
                'created_by' => $admin->id,
            ]
        );

        $tickets2 = [
            ['name' => 'Free Entry', 'description' => 'Gratis untuk semua pemain', 'price' => 0, 'quota' => 256],
        ];

        foreach ($tickets2 as $ticket) {
            TicketCategory::updateOrCreate(
                ['event_id' => $event2->id, 'name' => $ticket['name']],
                array_merge($ticket, ['event_id' => $event2->id, 'is_active' => true])
            );
        }

        EventGroup::updateOrCreate(
            ['event_id' => $event2->id],
            [
                'name' => 'Navo Clash Championship Group',
                'description' => 'Grup peserta turnamen. Info bracket & jadwal match.',
            ]
        );

        // ============ DUMMY REGISTRATIONS ============
        $rani = User::where('username', 'rani')->first();
        $bagas = User::where('username', 'bagas')->first();
        $fullPass = TicketCategory::where('event_id', $event->id)->where('name', 'Full Pass')->first();
        $vipPass = TicketCategory::where('event_id', $event->id)->where('name', 'VIP Pass')->first();

        if ($rani && $fullPass) {
            EventRegistration::updateOrCreate(
                ['event_id' => $event->id, 'user_id' => $rani->id],
                [
                    'ticket_category_id' => $fullPass->id,
                    'order_code' => 'DGN-' . strtoupper(Str::random(8)),
                    'payment_status' => 'verified',
                    'payment_verified_by' => $admin->id,
                    'payment_verified_at' => now(),
                    'e_ticket_code' => strtoupper(Str::random(16)),
                ]
            );
            $fullPass->increment('sold_count');
            $group->members()->syncWithoutDetaching([$rani->id => ['role' => 'member', 'joined_at' => now()]]);
        }

        if ($bagas && $vipPass) {
            EventRegistration::updateOrCreate(
                ['event_id' => $event->id, 'user_id' => $bagas->id],
                [
                    'ticket_category_id' => $vipPass->id,
                    'order_code' => 'DGN-' . strtoupper(Str::random(8)),
                    'payment_status' => 'pending',
                    'e_ticket_code' => strtoupper(Str::random(16)),
                ]
            );
            $group->members()->syncWithoutDetaching([$bagas->id => ['role' => 'member', 'joined_at' => now()]]);
        }

        $this->command->info('✅ 2 events + tickets + schedules + groups + dummy registrations');
    }
}