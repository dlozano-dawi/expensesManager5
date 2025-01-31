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
                <form class="p-3">
                    <label for="subject" class="block">{{ __("Subject") }}</label>
                    <input type="text" maxlength="100" id="subject" class="block mb-5 w-48 sm:rounded">

                    <label for="quantity" class="block">{{ __("Quantity") }}</label>
                    <input type="Number" id="quantity" class="block mb-5 w-48 sm:rounded">

                    <label for="date" class="block">{{ __("Date") }}</label>
                    <input type="date" id="date" class="block mb-5 w-48 sm:rounded">

                    <button type="button" id="send" class="border-b-2 border-gray-500 hover:border-indigo-500 w-48">{{ __("Submit") }}</button>
                </form>
            </div>

            <div class="p-3 text-gray-900 bg-white overflow-hidden shadow-xl sm:rounded-lg w-3/4">
                <h2 class="font-bold">{{ __('Expenses') }}</h2>
            </div>
        </div>
    </div>
</x-app-layout>
