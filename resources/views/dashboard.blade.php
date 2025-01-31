<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Expenses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between">
            <div class="p-3 text-gray-900 bg-white overflow-hidden shadow-xl sm:rounded-lg w-64">
                <h2 class="font-bold">{{ __('Add expense') }}</h2>
                <form method="post" action="{{ route('expense.insert') }}" class="p-3">
                    @csrf

                    <label for="subject" class="block">{{ __("Subject") }}</label>
                    <input type="text" name="subject" id="subject" class="block mb-5 w-48 sm:rounded" required>

                    <label for="quantity" class="block">{{ __("Quantity") }}</label>
                    <input type="number" name="quantity" id="quantity" class="block mb-5 w-48 sm:rounded" required>

                    <label for="date" class="block">{{ __("Date") }}</label>
                    <input type="date" name="date" id="date" class="block mb-5 w-48 sm:rounded" required>

                    <div class="mb-5 flex sm:items-center">
                        <input type="checkbox" name="paid" id="paid" value="1" class="mr-1 sm:rounded">
                        <label for="paid">{{ __("Paid") }}</label>
                    </div>

                    <button type="submit" class="border-b-2 border-gray-500 hover:border-indigo-500 w-48">{{ __("Submit") }}</button>
                </form>
            </div>

            <div class="p-3 text-gray-900 bg-white overflow-hidden shadow-xl sm:rounded-lg w-3/4">
                <h2 class="font-bold">{{ __('Expenses') }}</h2>
                <div>{{ route('expense.get') }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
