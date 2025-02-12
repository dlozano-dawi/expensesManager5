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

                    <label for="price" class="block">{{ __("Price") }}</label>
                    <input type="number" name="price" id="price" class="block mb-5 w-48 sm:rounded" required step="0.01">

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

                <!-- Mostrar los gastos en una lista con scroll -->
                <div id="expenses-list" class="max-h-96 overflow-y-auto">
                    @if($expenses->isEmpty())
                        <p>{{ __("No expenses found.") }}</p>
                    @else
                        @foreach($expenses as $expense)
                            <div class="p-2 mb-4 border-b flex justify-between">
                                <h3 class="w-1/3"><strong>{{ $expense->subject }} {{ $expense->price }}€</strong></h3>
                                <span class="w-1/3">{{ $expense->date }}</span>

                                <div class="w-3/3 flex items-center space-x-4">
                                    <!-- Checkbox para actualizar el estado de 'paid' -->
                                    <form action="{{ route('expense.updatePaid', $expense->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <input type="checkbox"
                                               name="paid"
                                               id="paid_{{ $expense->id }}"
                                               value="1"
                                               class="mr-1 sm:rounded"
                                               @if($expense->paid) checked @endif
                                               onchange="this.form.submit()">
                                        <label for="paid_{{ $expense->id }}">{{ __("Paid") }}</label>
                                    </form>

                                    <!-- Icono de la papelera para eliminar el gasto -->
                                    <form action="{{ route('expense.delete', $expense->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <x-heroicon-s-trash class="w-6 h-6 stroke-red-500 fill-none hover:stroke-red-700" />
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Agregar los estilos internos aquí -->
    <x-slot name="styles">
        <style>
            #expenses-list{
                scrollbar-width: thin;          /* "auto" or "thin" */
                scrollbar-color: blue orange;   /* scroll thumb and track */
            }
            #expenses-list::-webkit-scrollbar {
                width: 8px;
            }

            #expenses-list::-webkit-scrollbar-thumb {
                background-color: #4B5563; /* Color gris oscuro suave */
                border-radius: 8px;
            }

            #expenses-list::-webkit-scrollbar-thumb:hover {
                background-color: #9CA3AF; /* Color más claro al pasar el ratón */
            }

            #expenses-list::-webkit-scrollbar-track {
                background-color: #F3F4F6; /* Color de fondo más claro para el track */
                border-radius: 8px;
            }
        </style>
    </x-slot>

</x-app-layout>
