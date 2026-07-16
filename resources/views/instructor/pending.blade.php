<x-app-layout>
    <div class="max-w-xl mx-auto mt-20 text-center">
        <h1 class="text-2xl font-bold mb-4">Approval Pending</h1>
        <p class="text-gray-600">
            <h2> Hi {{ auth()->user()->name }},</h2>
            Your instructor account is under review.
            You’ll gain access once an admin approves your account.
        </p>
    </div>
</x-app-layout>
