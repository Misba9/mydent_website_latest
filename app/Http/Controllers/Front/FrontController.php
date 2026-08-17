<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\AppBaseController;
use App\Models\ClinicSchedule;
use App\Models\Doctor;
use App\Models\DoctorSession;
use App\Models\Faq;
use App\Models\FrontPatientTestimonial;
use App\Models\Patient;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Specialization;
use App\Models\User;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FrontController extends AppBaseController
{
    /**
     * @return Application|Factory|View
     */
    public function medical(): \Illuminate\View\View
    {
        $doctors = Doctor::with('user', 'specializations')->whereHas('user', function (Builder $query) {
            $query->where('status', User::ACTIVE);
        })->latest()->take(10)->get()->pluck('user.full_name', 'id');
        $sliders = Slider::with('media')->first() ?? new Slider();
        $frontMedicalServicesArray = Service::with('media')->whereStatus(Service::ACTIVE)->latest()->get()->toArray();
        $frontMedicalServices = array_chunk($frontMedicalServicesArray, 2);
        $frontPatientTestimonials = FrontPatientTestimonial::with('media')->latest()->take(6)->get();
        $aboutExperience = Setting::where('key', 'about_experience')->first();

        return view('fronts.medicals.index',
            compact('doctors', 'sliders', 'frontMedicalServices', 'frontPatientTestimonials',
                'aboutExperience'));
    }

    /**
     * @return Application|Factory|View
     */
    public function medicalAboutUs(): \Illuminate\View\View
    {
        $data = [];
        $data['doctorsCount'] = Doctor::whereHas('user', function (Builder $query) {
            $query->where('status', true);
        })->count();
        $data['patientsCount'] = Patient::count();
        $data['servicesCount'] = Service::whereStatus(true)->count();
        $data['specializationsCount'] = Specialization::count();
        $clinicSchedules = ClinicSchedule::all();
        $setting = Setting::where('key', 'about_us_image')->first();
        $frontPatientTestimonials = FrontPatientTestimonial::with('media')->latest()->take(6)->get();
        $doctors = Doctor::with('user', 'appointments', 'specializations')->whereHas('user', function (Builder $query) {
            $query->where('status', User::ACTIVE);
        })->withCount('appointments')->orderBy('appointments_count', 'desc')->take(3)->get();

        return view('fronts.medical_about_us',
            compact('doctors', 'data', 'setting', 'clinicSchedules', 'frontPatientTestimonials'));
    }

    /**
     * @return Application|Factory|View
     */
    public function medicalServices(): \Illuminate\View\View
    {
        $data = [];
        $serviceCategories = ServiceCategory::with('activatedServices')->withCount('services')->get();
        $setting = Setting::pluck('value', 'key')->toArray();
        $services = Service::with('media')->whereStatus(Service::ACTIVE)->latest()->get();
        $data['doctorsCount'] = Doctor::whereHas('user', function (Builder $query) {
            $query->where('status', true);
        })->count();
        $data['patientsCount'] = Patient::count();
        $data['servicesCount'] = Service::whereStatus(true)->count();
        $data['specializationsCount'] = Specialization::count();

        return view('fronts.medical_services', compact('serviceCategories', 'setting', 'services', 'data'));
    }

    /**
     * @return Application|Factory|View
     */
    public function medicalAppointment(): \Illuminate\View\View
    {
        $faqs = Faq::latest()->get();

        $appointmentDoctors = Doctor::with('user')->whereIn('id',
            DoctorSession::pluck('doctor_id')->toArray())->get()->where('user.status',
                User::ACTIVE)->pluck('user.full_name', 'id');

        return view('fronts.medical_appointment', compact('faqs', 'appointmentDoctors'));
    }

    /**
     * @return Application|Factory|View
     */
    public function medicalDoctors(): \Illuminate\View\View
    {
        $doctors = Doctor::with('specializations', 'user')->whereHas('user', function (Builder $query) {
            $query->where('status', User::ACTIVE);
        })->latest()->take(9)->get();

        return view('fronts.medical_doctors', compact('doctors'));
    }

    /**
     * @return Application|Factory|View
     */
    public function medicalContact(): \Illuminate\View\View
    {
        $clinicSchedules = ClinicSchedule::all();

        return view('fronts.medical_contact', compact('clinicSchedules'));
    }


    public function ecom(): \Illuminate\View\View
    {
        $products = Product::all();
    $categories = Product::select('category')->distinct()->pluck('category');
    

        return view('fronts.medical_ecom', compact('products', 'categories'));
    }




    /**
     * @return Application|Factory|View
     */
    public function termsCondition(): \Illuminate\View\View
    {
        $termsSetting = Setting::where('key', 'terms_conditions')->first();
        $content = $termsSetting ? $termsSetting->value : null;

        if (empty($content) || str_contains($content, 'coming soon')) {
            $content = '
                <h3 class="fw-bold mb-4 text-primary">1. Introduction</h3>
                <p>Welcome to MyDent Dental Clinic System ("MyDent", "we", "us", or "our"). By accessing or using our website, purchasing our clear aligners, or scheduling dental appointments, you agree to be bound by these Terms and Conditions.</p>

                <h3 class="fw-bold mb-4 text-primary">2. Website Usage</h3>
                <p>You agree to use this website only for lawful purposes related to viewing information, purchasing dental products, or managing appointments. Unauthorized access, data scraping, or disruption of site services is strictly prohibited.</p>

                <h3 class="fw-bold mb-4 text-primary">3. Account Registration</h3>
                <p>When creating an account on MyDent, you must provide accurate and complete information. You are responsible for maintaining the confidentiality of your account credentials and for all activities occurring under your account.</p>

                <h3 class="fw-bold mb-4 text-primary">4. Products & Clear Aligners</h3>
                <p>MyDent clear aligners and dental hygiene products are provided based on prescription or professional evaluation. Alignment results may vary depending on compliance with wearing guidelines (20-22 hours/day) and individual anatomical differences.</p>

                <h3 class="fw-bold mb-4 text-primary">5. Orders & Payments</h3>
                <p>All product prices are stated in our local currency. Orders are subject to verification and acceptance. Payment must be completed prior to order dispatch or appointment confirmation.</p>

                <h3 class="fw-bold mb-4 text-primary">6. Appointments & Cancellations</h3>
                <p>Appointments scheduled with MyDent doctors must be cancelled or rescheduled at least 24 hours in advance. Failure to attend a scheduled appointment may incur a fee or forfeiture of deposit.</p>

                <h3 class="fw-bold mb-4 text-primary">7. Dental Treatment Disclaimer</h3>
                <p>Information provided on this website is for educational purposes and does not constitute professional medical advice. Always consult a licensed MyDent dentist or specialist for clinical diagnosis and treatment planning.</p>

                <h3 class="fw-bold mb-4 text-primary">8. Returns & Refund Policy</h3>
                <p>Due to health and safety regulations, opened or custom-manufactured dental clear aligners cannot be returned once delivered, unless defective or damaged upon arrival.</p>

                <h3 class="fw-bold mb-4 text-primary">9. Contact Information</h3>
                <p>For questions regarding these Terms & Conditions, please contact us at support@mydent.com or via our contact page.</p>
            ';
        }

        $termConditions = ['terms_conditions' => $content];
        return view('fronts.terms_conditions', compact('termConditions'));
    }

    /**
     * @return Application|Factory|View
     */
    public function privacyPolicy(): \Illuminate\View\View
    {
        $privacySetting = Setting::where('key', 'privacy_policy')->first();
        $content = $privacySetting ? $privacySetting->value : null;

        if (empty($content) || str_contains($content, 'coming soon')) {
            $content = '
                <h3 class="fw-bold mb-4 text-primary">1. Information We Collect</h3>
                <p>We collect personal information necessary to deliver dental services, process orders, and manage appointments. This includes your name, email address, phone number, shipping address, and dental history records.</p>

                <h3 class="fw-bold mb-4 text-primary">2. Account & Profile Information</h3>
                <p>When you register an account, schedule a 3D dental scan, or purchase products, your data is securely transmitted and stored in compliance with healthcare data protection standards.</p>

                <h3 class="fw-bold mb-4 text-primary">3. Order & Payment Security</h3>
                <p>All financial transactions are encrypted using Industry Standard SSL technology. We do not store raw credit card credentials on our servers; payments are processed securely by certified payment providers.</p>

                <h3 class="fw-bold mb-4 text-primary">4. Cookies & Analytics</h3>
                <p>Our website utilizes essential cookies to manage shopping carts, maintain authenticated user sessions, and enhance site navigation. Analytic tools help us understand site usage to improve user experience.</p>

                <h3 class="fw-bold mb-4 text-primary">5. How We Use Your Data</h3>
                <p>Your information is used exclusively to provide dental treatment plans, deliver purchases, send appointment reminders, and communicate critical updates regarding your clear aligner progress.</p>

                <h3 class="fw-bold mb-4 text-primary">6. Data Protection & Sharing</h3>
                <p>We respect your privacy. We never sell, rent, or lease your personal data to third parties. Data is shared only with authorized healthcare personnel directly involved in your care.</p>

                <h3 class="fw-bold mb-4 text-primary">7. Your Data Rights</h3>
                <p>You have the right to request access to your personal information, request corrections, or request deletion of your account where legally permissible.</p>

                <h3 class="fw-bold mb-4 text-primary">8. Contact Us</h3>
                <p>If you have any privacy concerns or wish to exercise your rights, please reach out to our Privacy Office at privacy@mydent.com.</p>
            ';
        }

        $privacyPolicy = ['privacy_policy' => $content];
        return view('fronts.privacy_policy', compact('privacyPolicy'));
    }

    /**
     * @return Application|Factory|View
     */
    public function faq(): \Illuminate\View\View
    {
        $faqs = Faq::latest()->get();

        if ($faqs->isEmpty()) {
            $defaultFaqs = [
                ['question' => 'What are clear aligners?', 'answer' => 'Clear aligners are custom-made, transparent plastic trays designed to gradually move your teeth into optimal alignment without metal wires or brackets.'],
                ['question' => 'How do MyDent aligners work?', 'answer' => 'MyDent aligners apply gentle, continuous force to specific teeth to shift them into position. You receive a series of custom trays, changing to a new set every 1-2 weeks.'],
                ['question' => 'How long does treatment take?', 'answer' => 'Treatment duration varies based on complexity, typically ranging between 4 to 12 months. Most patients begin seeing visible improvements within 8 to 12 weeks.'],
                ['question' => 'Are aligners painful?', 'answer' => 'Aligners are designed for maximum comfort. You may feel mild pressure or discomfort for the first 1–2 days of wearing a new set, which indicates that your teeth are moving as planned.'],
                ['question' => 'How often should aligners be worn?', 'answer' => 'Aligners should be worn for 20 to 22 hours per day, removing them only to eat, drink non-water beverages, brush, and floss.'],
                ['question' => 'Can I eat normally during treatment?', 'answer' => 'Yes! Since clear aligners are removable, you can enjoy all your favorite foods without dietary restrictions. Simply remove them before eating.'],
                ['question' => 'How do I clean my aligners?', 'answer' => 'Rinse your aligners daily with lukewarm water and gently brush them using a soft-bristled toothbrush. Avoid hot water as it can warp the plastic.'],
                ['question' => 'Who can use clear aligners?', 'answer' => 'Clear aligners are suitable for teens and adults with mild to complex orthodontic needs, including crowding, spacing, overbites, and crossbites.'],
                ['question' => 'How much does treatment cost?', 'answer' => 'MyDent aligners are up to 60% more affordable than traditional braces. Flexible monthly payment plans are available.'],
                ['question' => 'How do I book an appointment?', 'answer' => 'You can book a 3D scan or consultation directly through our website by clicking the Book button in the navigation header.']
            ];

            $faqs = collect($defaultFaqs)->map(function ($item) {
                $faq = new Faq();
                $faq->question = $item['question'];
                $faq->answer = $item['answer'];
                return $faq;
            });
        }

        return view('fronts.faq', compact('faqs'));
    }


    /**
     * @return mixed
     */
    public function changeLanguage(Request $request)
    {
        Session::put('languageName', $request->input('languageName'));

        return $this->sendSuccess(__('messages.flash.language_change'));
    }
}
