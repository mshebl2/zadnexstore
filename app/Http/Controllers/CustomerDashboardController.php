<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserAddress;
use App\Models\ReferralCommission;
use App\Models\PayoutRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerDashboardController extends Controller
{
    /**
     * Unified Customer Dashboard (Overview, Orders, Affiliate, Addresses, Profile).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $activeTab = $request->query('tab', 'overview');

        // Customer Orders
        $orders = Order::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
        })->with('items')->latest()->paginate(10);

        // Quick Metrics
        $totalOrdersCount = Order::where('user_id', $user->id)->orWhere('customer_email', $user->email)->count();
        $activeOrdersCount = Order::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
        })->whereIn('status', ['pending', 'processing', 'shipped'])->count();

        // Affiliate & Referral Metrics
        $totalReferralsCount = ReferralCommission::where('referrer_id', $user->id)->count();
        $totalEarnings = (float) ReferralCommission::where('referrer_id', $user->id)->sum('commission_amount');
        $referralCommissions = ReferralCommission::where('referrer_id', $user->id)->with('order')->latest()->take(10)->get();
        $payoutRequests = PayoutRequest::where('user_id', $user->id)->latest()->take(5)->get();

        // Saved Delivery Addresses
        $addresses = UserAddress::where('user_id', $user->id)->latest()->get();

        return view('pages.customer.dashboard', compact(
            'user',
            'activeTab',
            'orders',
            'totalOrdersCount',
            'activeOrdersCount',
            'totalReferralsCount',
            'totalEarnings',
            'referralCommissions',
            'payoutRequests',
            'addresses'
        ));
    }

    /**
     * View specific order invoice & details.
     */
    public function orderShow($orderNumber)
    {
        $user = Auth::user();
        $order = Order::where('order_number', $orderNumber)
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('customer_email', $user->email);
            })
            ->with(['items.product', 'payments'])
            ->firstOrFail();

        return view('pages.customer.order-show', compact('order', 'user'));
    }

    /**
     * Store new shipping address.
     */
    public function addressStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'recipient_phone' => 'required|string|max:25',
            'city' => 'required|string|max:50',
            'district' => 'nullable|string|max:100',
            'street_address' => 'required|string|max:200',
            'building_no' => 'nullable|string|max:50',
            'is_default' => 'nullable|boolean',
        ], [
            'recipient_name.required' => 'يرجى كتابة اسم المستلم.',
            'recipient_phone.required' => 'يرجى إدخال رقم جوال المستلم.',
            'city.required' => 'يرجى تحديد المدينة.',
            'street_address.required' => 'يرجى كتابة تفاصيل العنوان والشارع.',
        ]);

        $user = Auth::user();

        // If marked default or first address, handle default flags
        $isDefault = $request->boolean('is_default') || $user->addresses()->count() === 0;

        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create(array_merge($validated, [
            'is_default' => $isDefault,
        ]));

        return back()->with('success', 'تم حفظ العنوان بنجاح');
    }

    /**
     * Delete delivery address.
     */
    public function addressDestroy($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        $address->delete();

        return back()->with('success', 'تم حذف العنوان بنجاح');
    }

    /**
     * Set address as default.
     */
    public function addressSetDefault($id)
    {
        $user = Auth::user();
        $user->addresses()->update(['is_default' => false]);
        $user->addresses()->where('id', $id)->update(['is_default' => true]);

        return back()->with('success', 'تم تعيين العنوان كعنوان رئيسي للتوصيل');
    }

    /**
     * Request affiliate commission payout to Saudi IBAN.
     */
    public function payoutRequest(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:50', 'max:' . $user->wallet_balance],
            'bank_name' => 'required|string|max:100',
            'account_name' => 'required|string|max:100',
            'iban' => ['required', 'string', 'regex:/^SA[0-9]{22}$/i'],
        ], [
            'amount.min' => 'الحد الأدنى لطلب سحب الأرباح هو 50 ر.س.',
            'amount.max' => 'المبلغ المطلوب يتجاوز رصيدك المتاح حالياً.',
            'iban.regex' => 'صيغة الآيبان غير صحيحة، يجب أن يبدأ بـ SA متبوعاً بـ 22 رقماً.',
        ]);

        $amount = (float) $validated['amount'];

        try {
            DB::transaction(function () use ($user, $amount, $validated) {
                $lockedUser = User::where('id', $user->id)->lockForUpdate()->firstOrFail();
                if ($amount > $lockedUser->wallet_balance) {
                    throw new \Exception('المبلغ المطلوب يتجاوز رصيدك المتاح حالياً.');
                }

                // Deduct from wallet balance safely inside transaction
                $lockedUser->decrement('wallet_balance', $amount);

                // Record payout request
                PayoutRequest::create([
                    'user_id' => $lockedUser->id,
                    'amount' => $amount,
                    'bank_name' => $validated['bank_name'],
                    'account_name' => $validated['account_name'],
                    'iban' => strtoupper($validated['iban']),
                    'status' => 'pending',
                ]);
            });

            return back()->with('success', 'تم رفع طلب السحب بنجاح! سيتم التحويل إلى حسابكم البنكي بعد المراجعة.');
        } catch (\Exception $e) {
            return back()->withErrors(['amount' => $e->getMessage()]);
        }
    }

    /**
     * Update customer profile & password.
     */
    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:30',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'يرجى كتابة الاسم.',
            'new_password.min' => 'يجب أن لا تقل كلمة المرور الجديدة عن 8 أحرف.',
            'new_password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        if (!empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة'])->withInput();
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->name = $validated['name'];
        if ($request->filled('phone')) {
            $user->phone = $validated['phone'];
        }
        $user->save();

        return back()->with('success', 'تم تحديث بيانات الحساب بنجاح');
    }
}

